<?php
/**
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 * You may obtain a copy of the License at
 *
 *     https://opensource.org/licenses/mit-license.php
 *
 *
 * @copyright Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license https://opensource.org/licenses/mit-license.php MIT License
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
        if (!is_array($class)) {
            $class = explode(' ', trim($class));
        }
        $optClass = [];
        if (isset($options[$key])) {
            $optClass = $options[$key];
            if (!is_array($optClass)) {
                $optClass = explode(' ', trim($optClass));
            }
        }
        $class = array_merge($optClass, $class);
        $class = array_map('trim', $class);
        $class = array_unique($class);
        $class = array_filter($class);
        $options[$key] = implode(' ', $class);
        return $options ;
    }

    /**
     * Add classes to options according to values of bootstrap-type and bootstrap-size
     * for button.
     *
     * @param $options The initial options with bootstrap-type and/or bootstrat-size values
     *
     * @return The new options with class values (btn, and btn-* according to initial options)
     *
     */
    protected function _addButtonClasses ($options) {
        $options += [
            'bootstrap-type' => $this->config('buttons.type'),
            'bootstrap-size' => false
        ];
        $type = $options['bootstrap-type'];
        $size = $options['bootstrap-size'];
        unset($options['bootstrap-type'], $options['bootstrap-size']);
        $options = $this->addClass($options, 'btn');
        if (!preg_match('#btn-[a-z]+#', $options['class'])) {
            $options = $this->addClass($options, 'btn-'.$type);
        }
        if ($size) {
            $options = $this->addClass($options, 'btn-'.$size);
        }
        return $options ;
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