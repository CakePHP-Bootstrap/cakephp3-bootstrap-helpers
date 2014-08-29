<?php

/**
* Bootstrap Form Helper
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

use Cake\View\Helper\FormHelper;

class BootstrapFormHelper extends FormHelper {

    public $helpers = array('Html','Url') ;
    
    public $horizontal = false ;
    public $inline = false ;
    public $search = false ;
    public $colSize ;
    
    private $defaultColumnSize = [
        'label' => 2,
        'input' => 6,
        'error' => 4
    ];  
    private $defaultButtonType = 'default' ;

    private $buttonTypes = ['primary', 'info', 'success', 'warning', 'danger', 'inverse', 'link'] ;
    private $buttonSizes = ['mini', 'small', 'large'] ;

    public function __construct (\Cake\View\View $view, array $config = []) {
        if (isset($config['buttons'])) {
            if (isset($config['buttons']['type'])) {
                $this->defaultButtonType = $config['buttons']['type'] ;
            }
        }
        if (isset($config['columns'])) {
            $this->defaultColumnSize = $config['columns'] ;
        }
        parent::__construct($view, $config);
    }
    
    /**
     * 
     * Add classes to options according to values of bootstrap-type and bootstrap-size for button.
     * 
     * @param $options The initial options with bootstrap-type and/or bootstrat-size values
     * 
     * @return The new options with class values (btn, and btn-* according to initial options)
     * 
    **/
    protected function _addButtonClasses ($options) {
        $options = $this->addClass($options, 'btn btn-'.$this->defaultButtonType) ;
        foreach ($this->buttonTypes as $type) {
            if (isset($options['bootstrap-type']) && $options['bootstrap-type'] == $type) {
                $options = $this->addClass($options, 'btn-'.$type) ;
                break ;
            }
        }
        foreach ($this->buttonSizes as $size) {
            if (isset($options['bootstrap-size']) && $options['bootstrap-size'] == $size) {
                $options = $this->addClass($options, 'btn-'.$size) ;
                break ;
            }
        }
        unset($options['bootstrap-size']) ;
        unset($options['bootstrap-type']) ;
        return $options ;
    }

    /**
     *
     * Try to match the specified HTML code with a button or a input with submit type.
     *
     * @param $html The HTML code to check
     *
     * @return true if the HTML code contains a button
     *
    **/
    protected function _matchButton ($html) {
        return strpos($html, '<button') !== FALSE || strpos($html, 'type="submit"') !== FALSE ;
    }
	
    /**
     * 
     * Create a Twitter Bootstrap like form. 
     * 
     * New options available:
     * 	- horizontal: boolean, specify if the form is horizontal
     * 	- inline: boolean, specify if the form is inline
     * 	- search: boolean, specify if the form is a search form
     * 
     * Unusable options:
     * 	- inputDefaults
     * 
     * @param $model The model corresponding to the form
     * @param $options Options to customize the form
     * 
     * @return The HTML tags corresponding to the openning of the form
     * 
    **/
    public function create($model = null, Array $options = array()) {
        if (isset($options['cols'])) {
            $this->colSize = $options['cols'] ;
            unset($options['cols']) ;
        }
        else {
            $this->colSize = $this->defaultColumnSize ;
        }
        $this->horizontal = $this->_extractOption('horizontal', $options, false);
		unset($options['horizontal']);
        $this->search = $this->_extractOption('search', $options, false) ;
        unset($options['search']) ;
        $this->inline = $this->_extractOption('inline', $options, false) ;
        unset($options['inline']) ;
		if ($this->horizontal) {
			$options = $this->addClass($options, 'form-horizontal') ;
		}
        else if ($this->inline) {
            $options = $this->addClass($options, 'form-inline') ;
        }
        if ($this->search) {
            $options = $this->addClass($options, 'form-search') ;
        }
        $options['role'] = 'form' ;
        $this->templates([
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</div>',
            'formGroup' => '{{label}}'.($this->horizontal ? '<div class="'.$this->_getColClass('input').'">' : '').'{{input}}'.($this->horizontal ? '</div>' : ''),
            'input' => '<input class="form-control" {{attrs}} type="{{type}}" name="{{name}}" id="{{name}}" />',
            'select' => '<select class="form-control" name="{{name}}"{{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select class="form-control" name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
            'checkboxContainer' => '<div class="form-group">'.($this->horizontal ? '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">' : '').'<div class="checkbox"><label>{{content}}</label>'.($this->horizontal ? '</div>' : '').'</div></div>',
            'radioContainer' => '<div class="form-group">'.($this->horizontal ? '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">' : '').'<div class="checkbox"><label>{{content}}</label>'.($this->horizontal ? '</div>' : '').'</div></div>',
            'label' => '<label class="'.($this->horizontal ? $this->_getColClass('label') : '').' '.($this->inline ? 'sr-only' : 'control-label').'" {{attrs}}>{{text}}</label>',
            'error' => '<span class="help-block '.($this->horizontal ? $this->_getColClass('error') : '').'">{{content}}</span>',
            'submitContainer' => '<div class="form-group">'.($this->horizontal ? '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">' : '').'{{content}}'.($this->horizontal ? '</div>' : '').'</div>',
        ]) ;
		return parent::create($model, $options) ;
	}
    
    /**
     *
     * Return the col size class for the specified column (label, input or error).
     *
    **/
    protected function _getColClass($what, $offset = false) {
        if (isset($this->colSize[$what])) {
            return 'col-md-'.($offset ? 'offset-' : '').$this->colSize[$what] ;
        }
        $classes = [] ;
        foreach ($this->colSize as $cl => $arr) {
            if (isset($arr[$what])) {
                $classes[] = 'col-'.$cl.'-'.($offset ? 'offset-' : '').$arr[$what] ;
            }
        }
        return implode(' ', $classes) ;
    }

    /**
     *
     * Create & return a label message (Twitter Boostrap like).
     *
    **/
    public function label($fieldName, $text = NULL, array $options = []) {
        $nolabel = strpos($options['input'], 'type="checkbox"') !== false 
            || strpos($options['input'], 'type="radio"') !== false  ;
        if ($nolabel) {
            return $text ;
        }
        return parent::label($fieldName, $text, $options) ;
    }
	
    /** 
     * 
     * Create & return an input block (Twitter Boostrap Like).
     * 
     * New options:
     * 	- prepend: 
     * 		-> string: Add <span class="add-on"> before the input
     * 		-> array: Add elements in array before inputs
     * 	- append: Same as prepend except it add elements after input
     *        
    **/
    public function input($fieldName, array $options = array()) {
    
        $prepend = $this->_extractOption('prepend', $options, '') ;
        unset ($options['prepend']) ;
        $append = $this->_extractOption('append', $options, '') ;
        unset ($options['append']) ;

        $oldTemplate = $this->templates('input') ;

        if ($prepend || $append) {
            $before = '' ;
            $after = '' ;
            if ($prepend) {
                if (is_string($prepend)) {
                    $before = '<span class="input-group-'.($this->_matchButton($prepend) ? 'btn' : 'addon').'">'.$prepend.'</span>' ;
                }
                else {
                    $before = '<span class="input-group-btn">'.implode('', $prepend).'</span>' ;
                }
            }
            if ($append) {
                if (is_string($append)) {
                    $after = '<span class="input-group-'.($this->_matchButton($append) ? 'btn' : 'addon').'">'.$append.'</span>' ;
                }
                else {
                    $after = '<span class="input-group-btn">'.implode('', $append).'</span>' ;
                }
            }
            $this->templates([
                'input' => '<div class="input-group">'.$before.'<input class="form-control" {{attrs}} type="{{type}}" name="{{name}}" id="{{name}}" />'.$after.'</div>'
            ]) ;
        }

		$res = parent::input($fieldName, $options) ;

	    $this->templates([
            'input' => $oldTemplate
        ]) ;

        return $res ;
    }
    
    /**
     * 
     * Create & return a Twitter Like button.
     * 
     * New options:
     * 	- bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
     * 	- bootstrap-size: Twitter bootstrap button size (mini, small, large)
     * 
    **/
    public function button($title, array $options = array()) {
        $options = $this->_addButtonClasses($options) ;
        return parent::button($title, $options) ;
    }
    
    /**
     * 
     * Create & return a Twitter Like button group.
     * 
     * @param $buttons The buttons in the group
     * @param $options Options for div method
     *
     * Extra options:
     *  - vertical true/false
     * 
    **/
    public function buttonGroup ($buttons, array $options = array()) {
        $vertical = $this->_extractOption('vertical', $options, false) ;
        unset($options['vertical']) ;
        $options = $this->addClass($options, 'btn-group') ;
        if ($vertical) {
            $options = $this->addClass($options, 'btn-group-vertical') ;
        }
        return $this->Html->tag('div', implode('', $buttons), $options) ;
    }
    
    /**
     * 
     * Create & return a Twitter Like button toolbar.
     * 
     * @param $buttons The groups in the toolbar
     * @param $options Options for div method
     * 
    **/
    public function buttonToolbar (array $buttonGroups, array $options = array()) {
        $options = $this->addClass($options, 'btn-toolbar') ;
        return $this->Html->tag('div', implode('', $buttonGroups), $options) ;
    }
    
    /**
     * 
     * Create & return a twitter bootstrap dropdown button.
     * 
     * @param $title The text in the button
     * @param $menu HTML tags corresponding to menu options (which will be wrapped
     * 		 into <li> tag). To add separator, pass 'divider'.
     * @param $options Options for button
     * 
    **/
    public function dropdownButton ($title, array $menu = array(), array $options = array()) {
    
        $options['type'] = false ;
        $options['data-toggle'] = 'dropdown' ;
        $options = $this->addClass($options, "dropdown-toggle") ;
        
        $outPut = '<div class="btn-group">' ;
        $outPut .= $this->button($title.' <span class="caret"></span>', $options) ;
        $outPut .= '<ul class="dropdown-menu">' ;
        foreach ($menu as $action) {
            if ($action === 'divider') {
                $outPut .= '<li class="divider"></li>' ;
            }
            else {
                $outPut .= '<li>'.$action.'</li>' ;
            }
        }
        $outPut .= '</ul></div>' ;
        return $outPut ;
    }
    
    /**
     * 
     * Create & return a Twitter Like submit input.
     * 
     * New options:
     * 	- bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
     * 	- bootstrap-size: Twitter bootstrap button size (mini, small, large)
     * 
     * Unusable options: div
     * 
    **/    
    public function submit($caption = null, array $options = array()) {
        $options = $this->_addButtonClasses($options) ;
        return parent::submit($caption, $options) ;
    }
    
    /** SPECIAL FORM **/
    
    /**
     * 
     * Create a basic bootstrap search form.
     * 
     * @param $model The model of the form
     * @param $options The options that will be pass to the BootstrapForm::create method
     * 
     * Extra options:
     * 	- label: The input label (default false)
     * 	- placeholder: The input placeholder (default "Search... ")
     * 	- button: The search button text (default: "Search")
     *     
    **/
    public function searchForm ($model = null, $options = array()) {
        
        $label = $this->_extractOption('label', $options, false) ;
        unset($options['label']) ;
        $placeholder = $this->_extractOption('placeholder', $options, 'Search... ') ;
        unset($options['placeholder']) ;
        $button = $this->_extractOption('button', $options, 'Search') ;
        unset($options['button']) ;
        
        $output = '' ;
        
        $output .= $this->create($model, array_merge(array('search' => true, 'inline' => (bool)$label), $options)) ;
        $output .= $this->input('search', array(
            'label' => $label,
            'placeholder' => $placeholder,
            'append' => array(
                $this->button($button, array('style' => 'vertical-align: middle'))
            )
        )) ;
        $output .= $this->end() ;
    
        return $output ;
    }

}

?>
