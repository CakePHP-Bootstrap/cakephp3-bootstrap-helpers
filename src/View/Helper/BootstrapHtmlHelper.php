<?php

/**
* Bootstrap Html Helper
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

namespace Bootstrap3\View\Helper;

use Cake\View\Helper\HtmlHelper;

class BootstrapHtmlHelper extends HtmlHelper {    

    protected function _extractOption ($key, $options, $default = null) {
        if (isset($options[$key])) {
            return $options[$key] ;
        }
        return $default ;
    }
    
    /**
     * 
     * Check type values in $options, returning null if no option is found or if
     * option is not in $avail. 
     * If type == $default, $default is returned (even if it is not in $avail).
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
     * 
     * Create a Twitter Bootstrap icon.
     * 
     * @param $icon The type of the icon (search, pencil, etc.)
     * 
    **/
    public function icon ($icon) {
        return '<i class="glyphicon glyphicon-'.$icon.'"></i>' ; 
    }
    
    /**
     *
     * Create a Twitter Bootstrap span label.
     * 
     * @param text The label text
     * @param type The label type (default, primary, success, warning, info, danger)
     * @param options Options for span
     * 
     * The second parameter may either be $type or $options (in this case, the third parameter
     * is useless, and the label type can be specified in the $options array).
     *
     * Extra options
     *  - type The type of the label (useless if $type specified)
     *
    **/
    public function label ($text, $type = 'default', $options = array()) {
        if (is_string($type)) {
            $options['type'] = $type ;
        }
        else if (is_array($type)) {
            $options = $type ;
        }
        $type = $this->_extractType($options, 'type', $default = 'default',
                    array('default', 'primary', 'success', 'warning', 'info', 'danger')) ;
        unset ($options['type']) ;
        $options = $this->addClass($options, 'label') ;
        $options = $this->addClass($options, 'label-'.$type) ;
        return $this->tag('span', $text, $options) ;
    }
    
    /**
     *
     * Create a Twitter Bootstrap span badge.
     * 
     * @param text The badge text
     * @param options Options for span
     *
     *
    **/
    public function badge ($text, $options = array()) {
        $options = $this->addClass($options, 'badge') ;
        return $this->tag('span', $text, $options) ;
    }

    /**
     * 
     * Get crumb lists in a HTML list, with bootstrap like style.
     *
     * @param $options Options for list
     * @param $startText Text to insert before list
     * 
     * Unusable options:
     * 	- Separator
    **/
    public function getCrumbList(array $options = array(), $startText = false) {
        $options['separator'] = '' ;
        $options = $this->addClass($options, 'breadcrumb') ;
        return parent::getCrumbList ($options, $startText) ;
    }
    
    /**
     *  
     * Create a Twitter Bootstrap style alert block, containing text.
     *  
     * @param $text The alert text
     * @param $type The type of the alert
     * @param $options Options that will be passed to Html::div method
     *
     * The second parameter may either be $type or $options (in this case, the third parameter
     * is useless, and the label type can be specified in the $options array).
     * 
     * Available BootstrapHtml options:
     * 	- type: string, type of alert (default, error, info, success ; useless if 
     *    $type is specified)
     *     
    **/
    public function alert ($text, $type = 'warning', $options = array()) {
        if (is_string($type)) {
            $options['type'] = $type ;
        }
        else if (is_array($type)) {
            $options = $type ;
        }
        $button = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' ;
        $type = $this->_extractType($options, 'type', 'warning', array('info', 'warning', 'success', 'danger')) ;
        unset($options['type']) ;
        $options = $this->addClass($options, 'alert') ;
        if ($type) {
            $options = $this->addClass($options, 'alert-'.$type) ;
        }
        $class = $options['class'] ;
        unset($options['class']) ;
        return $this->div($class, $button.$text, $options) ;
    }
    
    /**
     * 
     * Create a Twitter Bootstrap style progress bar.
     * 
     * @param $widths 
     * 	- The width (in %) of the bar (style primary, without display)
     * 	- An array of bar, with (for each bar) : 
     *        - width (only field required)
     *        - type (primary, info, danger, success, warning, default is primary)
     *        - min (integer, default 0)
     *        - max (integer, default 100)
     *        - display (boolean, default false, for text display)
     * @param $options Options that will be passed to Html::div method (only for main div)
     *  
     * If $widths is only a integer (first case), $options may contains value for the fields
     * specified above.
     *
     * Available BootstrapHtml options:
     * 	- striped: boolean, specify if progress bar should be striped
     * 	- active: boolean, specify if progress bar should be active
     *     
    **/
    public function progress ($widths, $options = array()) {
        $striped = $this->_extractOption('striped', $options, false) ;
        unset($options['striped']) ;
        $active = $this->_extractOption('active', $options, false) ;
        unset($options['active']) ;
        $bars = '' ;
        if (is_array($widths)) {
            foreach ($widths as $w) {
                $type = $this->_extractType($w, 'type', 'primary', array('info', 'primary', 'success', 'warning', 'danger')) ;
                $class = 'progress-bar progress-bar-'.$type ;
                $min = $this->_extractOption('min', $w, 0);
                $max = $this->_extractOption('max', $w, 100);
                $display = $this->_extractOption('display', $w, false);
                $bars .= $this->div($class, $display ? $w['width'].'%' : '', array(
                    'aria-valuenow' => $w['width'],
                    'aria-valuemin' => $min,
                    'aria-valuemax' => $max,
                    'role' => 'progressbar', 
                    'style' => 'width: '.$w['width'].'%;'
                )) ;
            }
        }
        else {
            $type = $this->_extractType($options, 'type', 'primary', array('info', 'primary', 'success', 'warning', 'danger')) ;
            unset($options['type']) ;
            $class = 'progress-bar progress-bar-'.$type ;
            $min = $this->_extractOption('min', $options, 0);
            unset ($options['min']) ;
            $max = $this->_extractOption('max', $options, 100);
            unset ($options['max']) ;
            $display = $this->_extractOption('display', $options, false);
            unset ($options['display']) ;
            $bars = $this->div($class, $display ? $widths.'%' : '', array(
                'aria-valuenow' => $widths,
                'aria-valuemin' => $min,
                'aria-valuemax' => $max,
                'role' => 'progressbar', 
                'style' => 'width: '.$widths.'%;'
            )) ;
        }
        $options = $this->addClass($options, 'progress') ;
        if ($active) {
            $options = $this->addClass($options, 'active') ;
        }
        if ($striped) {
            $options = $this->addClass($options, 'progress-striped') ;
        }
        $classes = $options['class'];
        unset($options['class']) ;
        return $this->div($classes, $bars, $options) ;
    }
    
}

?>
