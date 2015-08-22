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

namespace Bootstrap\View\Helper;

use Cake\View\Helper\HtmlHelper;

class BootstrapHtmlHelper extends HtmlHelper {

    use BootstrapTrait ;

    /**
     * Count the number of created dropdown for id.
     *
     * @var int
     */
    protected $_dropDownCount = 0 ;

    /**
     * Use font awesome icons instead of glyphicons.
     *
     * @var boolean
     */
    protected $_useFontAwesome = true;

    public function __construct (\Cake\View\View $view, array $config = []) {
        if (isset($config['useFontAwesome'])) {
            $this->_useFontAwesome = $config['useFontAwesome'];
        }
		if (isset($config['useGlyphicon'])) {
            $this->_useFontAwesome = !$config['useGlyphicon'];
        }
        parent::__construct($view, $config);
    }

    /**
     *
     * Create a glyphicon or font awesome icon depending on $this->_useFontAwesome.
     *
     * @param $icon Name of the icon.
     *
    **/
    public function icon ($icon, $options = []) {
        return $this->_useFontAwesome ? $this->faIcon($icon, $options) : $this->glIcon($icon, $options);
    }

    /**
     * Create a font awesome icon.
     *
     * @param $icon Name of the icon.
     */
    public function faIcon ($icon, $options = []) {
        return $this->tag('i', '', $this->addClass($options, ['fa', 'fa-'.$icon]));
    }

    /**
     * Create a glyphicon icon.
     *
     * @param $icon Name of the icon.
     */
    public function glIcon ($icon, $options = []) {
        return $this->tag('i', '', $this->addClass($options, ['glyphicon', 'glyphicon-'.$icon]));
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
    public function label ($text, $type = 'default', $options = []) {
        if (is_string($type)) {
            $options['type'] = $type ;
        }
        else if (is_array($type)) {
            $options = $type ;
        }
        $type = $this->_extractType($options, 'type', $default = 'default',
                    ['default', 'primary', 'success', 'warning', 'info', 'danger']) ;
        unset ($options['type']) ;
        return $this->tag('span', $text, $this->addClass($options, ['label', 'label-'.$type])) ;
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
    public function badge ($text, $options = []) {
        return $this->tag('span', $text, $this->addClass($options, 'badge')) ;
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
    public function getCrumbList(array $options = [], $startText = false) {
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
    public function alert ($text, $type = 'warning', $options = []) {
        if (is_string($type)) {
            $options['type'] = $type ;
        }
        else if (is_array($type)) {
            $options = $type ;
        }
        $button = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' ;
        $type = $this->_extractType($options, 'type', 'warning', ['info', 'warning', 'success', 'danger']) ;
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
    public function progress ($widths, $options = []) {
        $striped = $this->_extractOption('striped', $options, false) || in_array('striped', $options);
        unset($options['striped']) ;
        $active = $this->_extractOption('active', $options, false) || in_array('active', $options);
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

    /**
     *
     * Create & return a twitter bootstrap dropdown menu.
     *
     * @param $menu HTML tags corresponding to menu options (which will be wrapped
     * 		 into <li> tag). To add separator, pass 'divider'.
     * @param $options Attributes for the wrapper (change it with tag)
     *
     */
    public function dropdown ($title, array $menu = [], array $options = [], array $buttonOptions = [], array $menuOptions = [], array $defaultItemOptions = []) {

        if (isset($options['_button'])) {
            $buttonOptions = $options['_button'] ;
            unset ($options['_button']) ;
        }

        if (isset($options['_menu'])) {
            $menuOptions = $options['_menu'] ;
            unset ($options['_menu']) ;
        }

        if (isset($menuOptions['_item'])) {
            $defaultItemOptions = $menuOptions['_item'] ;
            unset($menuOptions['_item']) ;
        }

        $options = $this->addClass($options, 'dropdown') ;
        $options += ['tag' => 'div'] ;

        $buttonOptions += [
            'data-toggle'   => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false',
            'id'            => 'dropdownMenu'.(++$this->_dropDownCount),
            'tag'           => 'a'
        ] ;
        $buttonOptions = $this->addClass ($buttonOptions, 'dropdown-toggle'); 
        $buttonOptions = $this->_addButtonClasses ($buttonOptions);

        $menuOptions   = $this->addClass ($menuOptions, 'dropdown-menu') ;
        $menuOptions  += [
            'aria-labelledby' => $buttonOptions['id'],
            'tag'             => 'div'
        ] ;

        $innerMenu = '' ;
        foreach ($menu as $action) {
            $content = '' ;
            $itemOptions = [] ;
            if ($action === 'divider' || (is_array($action) && $action[0] === 'divider')) {
                if (is_array($action) && isset($action[1]))
                    $itemOptions = $action[1] ;
                $itemOptions += ['tag' => 'div'] ;
                $itemOptions = $this->addClass ($itemOptions, 'dropdown-divider') ;
            }
            elseif (is_array($action)) {
                if ($action[0] === 'header') {
                    if (isset($action[2]))
                        $itemOptions = $action[2] ;
                    $itemOptions += ['tag' => 'h6'] ;
                    $itemOptions = $this->addClass ($itemOptions, 'dropdown-header') ;
                    $content     = $action[1] ;
                }
                else {
                    if ($action[0] === 'link') {
                        array_shift($action); // Remove first cell
                    }
                    $name = array_shift($action) ;
                    $url  = array_shift($action) ;
                    if (!empty($action))
                        $itemOptions = $action ;
                    $itemOptions = $this->addClass ($itemOptions, 'dropdown-item') ;
                    $itemOptions += ['tag' => 'a'] ;
                    $content = $this->link($name, $url, $itemOptions) ;
                }
            }
            else {
                $content = $action ;
                if (preg_match ('#<([a-z])+(.*)? (class="(.*)?")>(.*)*</\1>#i', $content, $matches)) {
                    $content = str_replace ('class="', 'class="dropdown-item ', $content);
                }
                else {
                    $content = preg_replace('#<([a-z]+)#i', '<\1 class="dropdown-item" ', $content) ;
                }
            }
            if (!empty($itemOptions)) {
                $itemTag = $itemOptions['tag'] ;
                unset ($itemOptions['tag']) ;
                $innerMenu .= $this->tag($itemTag, $content, $itemOptions);
            }
            else {
                $innerMenu .= $content ;
            }
        }
        // Create button
        $buttonTag = $buttonOptions['tag'] ;
        unset ($buttonOptions['tag']) ;
        $button = $this->tag($buttonTag, $title, $buttonOptions) ;
        // Create menu
        $menuTag = $menuOptions['tag'] ;
        unset ($menuOptions['tag']) ;
        $menu = $this->tag($menuTag, $innerMenu, $menuOptions) ;
        // Create and return dropdown
        $tag = $options['tag'] ;
        unset($options['tag']);
        return $this->tag($tag, $button.$menu, $options) ;
    }

    /**
     * Create a formatted collection of elements while
     * maintaining proper bootstrappy markup. Useful when
     * displaying, for example, a list of products that would require
     * more than the maximum number of columns per row.
     *
     * @param $breakIndex int|string divisible index that will trigger a new row
     * @param $data array collection of data used to render each column
     * @param $determineContent callable a callback that will be called with the
     * data required to render an individual column
     * @return string
     */
    public function splicedRows ($breakIndex, array $data, callable $determineContent) {
        $rowsHtml = '<div class="row">';

        $count = 1;
        foreach ($data as $index => $colData) {
            $rowsHtml .= $determineContent($colData);

            if ($count % $breakIndex === 0) {
                $rowsHtml .= '<div class="clearfix hidden-xs hidden-sm"></div>';
            }

            $count++;
        }

        $rowsHtml .= '</div>';
        return $rowsHtml;

    }

}

?>
