<?php

/**
* Bootstrap Trait
*
*
* PHP 5
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
*
* @copyright Copyright (c) Mikaël Capelle (http://mikael-capelle.fr)
* @link http://mikael-capelle.fr
* @package app.View.Helper
* @since Apache v2
* @license http://www.apache.org/licenses/LICENSE-2.0
*/

namespace Bootstrap\View\Helper;

trait BootstrapTrait {

    /**
     * Set to false to disable easy icon processing.
     *
     * @var boolean
     */
    public $easyIcon = true ;

    /**
     * Adds the given class to the element options
     *
     * @param array $options Array options/attributes to add a class to
     * @param string|array $class The class name being added.
     * @param string $key the key to use for class.
     *
     * @return array Array of options with $key set.
     */
    public function addClass(array $options = [], $class = null, $key = 'class') {
        if (is_array($class)) {
            $class = implode(' ', array_unique(array_map('trim', $class))) ;
        }
        if (isset($options[$key])) {
            $optClass = $options[$key];
            if (is_array($optClass)) {
                $optClass = trim(implode(' ', array_unique(array_map('trim', $optClass))));
            }
        }
        if (isset($optClass) && $optClass) {
            $options[$key] = $optClass.' '.$class ;
        }
        else {
            $options[$key] = $class ;
        }
        return $options ;
    }

    /**
     * Add classes to options according to values of bootstrap-type and bootstrap-size for button.
     *
     * @param $options The initial options with bootstrap-type and/or bootstrat-size values
     *
     * @return The new options with class values (btn, and btn-* according to initial options)
     *
     */
    protected function _addButtonClasses ($options) {
        $type = $this->_extractOption('bootstrap-type', $options, $this->_defaultButtonType);
        $size = $this->_extractOption('bootstrap-size', $options, FALSE);
        unset($options['bootstrap-size']) ;
        unset($options['bootstrap-type']) ;
        $options = $this->addClass($options, 'btn') ;
        if (in_array($type, $this->buttonTypes)) {
            $options = $this->addClass($options, 'btn-'.$type) ;
        }
        if (in_array($size, $this->buttonSizes)) {
            $options = $this->addClass($options, 'btn-'.$size) ;
        }
        return $options ;
    }

    /**
     * Extract options from $options, returning $default if $key is not found.
     *
     * @param $key     The key to search for.
     * @param $options The array from which to extract the value.
     * @param $default The default value returned if the key is not found.
     *
     * @return mixed $options[$key] if $key is in $options, otherwize $default.
     *
     **/
    protected function _extractOption ($key, $options, $default = null) {
        if (isset($options[$key])) {
            return $options[$key] ;
        }
        return $default ;
    }

    /**
     *
     * Check weither the specified array is associative or not.
     *
     * @param $array The array to check.
     *
     * @return true if the array is associative, false otherwize.
     *
     **/
    protected function _isAssociativeArray ($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Check type values in $options, returning null if no option is found or if
     * option is not in $avail.
     * If type == $default, $default is returned (even if it is not in $avail).
     *
     * @param $options The array from which to extract the type.
     * @param $key     The key of the value.
     * @param $default The default value if the key is not present or if the value is not correct.
     * @param $avail   An array of possible values.
     *
     * @return mixed
     *
    **/
    protected function _extractType ($options, $key = 'type', $default = 'info',
                                      $avail = array('info', 'success', 'warning', 'error')) {
        $type = $this->_extractOption($key, $options, $default) ;
        if ($default !== false && $type == $default) {
            return $default ;
        }
        if (!in_array($type, $avail)) {
            return null ;
        }
        return $type ;
    }

    /**
     * Try to convert the specified $text to a bootstrap icon. The $text is converted if it matches
     * a format "i:icon-name".
     *
     * @param $title     The text to convert.
     * @param $converted If specified, will contains true if the text was converted,
     *                   false otherwize.
     *
     * @return The icon element if the conversion was successful, otherwize $text.
     *
     * Note: This function will currently fail if the Html helper associated with the view is not
     * BootstrapHtmlHelper.
     *
    **/
    protected function _makeIcon ($title, &$converted = false) {
        $converted = false ;
        if (!$this->easyIcon) {
            return $title ;
        }
        $title = preg_replace_callback('#(^|\s+)i:([a-zA-Z0-9\\-_]+)(\s+|$)#', function ($matches) {
            return $matches[1].$this->_View->Html->icon($matches[2]).$matches[3];
        }, $title, -1, $count);
        $converted = (bool)$count;
        return $title ;
    }

    /**
     * This method will the function $callback with the specified argument ($title and $options)
     * after applying a filter on them.
     *
     * @param $callback The method to call.
     * @param $title    The first argument ($title).
     * @param $options  The second argument ($options).
     *
     * @return Whatever might be returned by $callback.
     *
     * Note: Currently this method only works for function that take
     * two arguments ($title and $options).
     *
    **/
    protected function _easyIcon ($callback, $title, $options) {
        $title = $this->_makeIcon ($title, $converted);
        if ($converted) {
            $options += [
                'escape' => false
            ];
        }
        return call_user_func ($callback, $title, $options) ;
    }

}

?>