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
     * Use font awesome icons instead of glyphicons.
     *
     * @var boolean
     */
    protected $_useFontAwesome = FALSE;

    public function __construct (\Cake\View\View $view, array $config = []) {
        if (isset($config['useFontAwesome'])) {
            $this->_useFontAwesome = $config['useFontAwesome'];
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
        $options = $this->addClass($options, 'fa');
        $options = $this->addClass($options, 'fa-'.$icon);

        return $this->tag('i', '', $options);
    }

    /**
     * Create a glyphicon icon.
     *
     * @param $icon Name of the icon.
     */
    public function glIcon ($icon, $options = []) {
        $options = $this->addClass($options, 'glyphicon');
        $options = $this->addClass($options, 'glyphicon-'.$icon);

        return $this->tag('i', '', $options);
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
    public function badge ($text, $options = []) {
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
     *              into <li> tag). To add separator, pass 'divider'.
     * @param $options Attributes for the wrapper (change it with tag)
     *
     */
    public function dropdown (array $menu = [], array $options = []) {
        $output = '' ;
        foreach ($menu as $action) {
            if ($action === 'divider' || (is_array($action) && $action[0] === 'divider')) {
                $output .= '<li role="presentation" class="divider"></li>' ;
            }
            elseif (is_array($action)) {
                if ($action[0] === 'header') {
                    $output .= '<li role="presentation" class="dropdown-header">'.$action[1].'</li>' ;
                }
                else {
                    if ($action[0] === 'link') {
                        array_shift($action); // Remove first cell
                    }
                    $name = array_shift($action) ;
                    $url  = array_shift($action) ;
                    $action['role'] = 'menuitem' ;
                    $action['tabindex'] = -1 ;
                    $output .= '<li role="presentation">'.$this->link($name, $url, $action).'</li>';
                }
            }
            else {
                $output .= '<li role="presentation">'.$action.'</li>' ;
            }
        }
        $options = $this->addClass($options, 'dropdown-menu');
        $options['role'] = 'menu';
        $options += ['tag' => 'ul'];
        $tag = $options['tag'];
        unset($options['tag']);
        return $this->tag($tag, $output, $options) ;
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

    /**
     * Provide Bootstrap tooltip for text/element
     *
     * If the text starts with a HTML tag, the tooltip is inserted into this tag.
     * Otherwise, the text is wrapped with a <span> containing the tooltip.
     *
     * Bootstrap Tooltips are an opt-in functionality. You can initialize this
     * functionality (also for dynamically appearing content) via this JS:
     * $("body").tooltip({ selector: '[data-toggle="tooltip"]' });
     *
     * @param $text string text to be outfitted with a tooltip
     * @param $tooltip string tooltip text
     * @param string $placement tooltip placement (left, top, bottom, right)
     * @return mixed|string
     */
    public function tooltip($text, $tooltip, $placement = 'right') {
        $attrs = " data-toggle='tooltip' data-placement='$placement' title='$tooltip'";
        if ($text[0] === '<') {
            $pos = strpos($text, '>');
            return substr_replace($text, $attrs, $pos, 0);
        }
        return "<span$attrs>$text</span>";
    }
}

?>
