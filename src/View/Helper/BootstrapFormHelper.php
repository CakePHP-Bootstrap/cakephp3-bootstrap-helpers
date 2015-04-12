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

    public $helpers = [
        'Html', 
        'Url',
        'bHtml' => [
            'className' => 'Bootstrap3.BootstrapHtml'
        ]
    ] ;

    /**
     * Default config for the helper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'errorClass' => 'has-error',
        'typeMap' => [
            'string' => 'text', 'datetime' => 'datetime', 'boolean' => 'checkbox',
            'timestamp' => 'datetime', 'text' => 'textarea', 'time' => 'time',
            'date' => 'date', 'float' => 'number', 'integer' => 'number',
            'decimal' => 'number', 'binary' => 'file', 'uuid' => 'string'
        ],
        'templates' => [
            'button' => '<button{{attrs}}>{{text}}</button>',
            'checkbox' => '<input type="checkbox" name="{{name}}" value="{{value}}"{{attrs}}>',
            'checkboxFormGroup' => '{{label}}',
            'checkboxWrapper' => '<div class="checkbox">{{label}}</div>',
            'checkboxContainer' => '<div class="form-group"><div class="checkbox">{{content}}</div></div>',
            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
            'error' => '<span class="help-block">{{content}}</span>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input type="file" name="{{name}}" {{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{prepend}}{{input}}{{append}}',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control {{attrs.class}}" {{attrs}} />',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}>',
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</div>',
            'label' => '<label class="control-label" {{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}" class="form-control {{attrs.class}}" {{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control {{attrs.class}}" {{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '<div class="radio">{{label}}</div>',
            'radioContainer' => '<div class="form-group">{{content}}</div>',
            'textarea' => '<textarea name="{{name}}" class="form-control {{attrs.class}}" {{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<div class="form-group">{{content}}</div>',
        ]
    ];

    /**
     * Default widgets
     *
     * @var array
     */
    protected $_defaultWidgets = [
        'button' => ['Cake\View\Widget\ButtonWidget'],
        'checkbox' => ['Cake\View\Widget\CheckboxWidget'],
        'file' => ['Cake\View\Widget\FileWidget'],
        'label' => ['Cake\View\Widget\LabelWidget'],
        'nestingLabel' => ['Cake\View\Widget\NestingLabelWidget'],
        'multicheckbox' => ['Cake\View\Widget\MultiCheckboxWidget', 'nestingLabel'],
        'radio' => ['Cake\View\Widget\RadioWidget', 'nestingLabel'],
        'select' => ['Cake\View\Widget\SelectBoxWidget'],
        'textarea' => ['Cake\View\Widget\TextareaWidget'],
        'datetime' => ['Cake\View\Widget\DateTimeWidget', 'select'],
        '_default' => ['Cake\View\Widget\BasicWidget'],
    ];
    
    public $horizontal = false ;
    public $inline = false ;
    public $search = false ;
    public $colSize ;

    /**
     * Use custom file inputs (bootstrap style, with javascript).
     *
     * @var boolean
     */
    protected $_customFileInput = false ;

    /**
     * Default type for buttons.
     *
     * @var string
     */
    protected $_defaultButtonType = 'default' ;

    /**
     * Default colums size.
     *
     * @var array
     */
    protected $_defaultColumnSize = [
        'label' => 2,
        'input' => 6,
        'error' => 4
    ];

    private $buttonTypes = ['default', 'primary', 'info', 'success', 'warning', 'danger', 'link'] ;
    private $buttonSizes = ['xs', 'sm', 'lg'] ;

    public function __construct (\Cake\View\View $view, array $config = []) {
        if (isset($config['buttons'])) {
            if (isset($config['buttons']['type'])) {
                $this->_defaultButtonType = $config['buttons']['type'] ;
            }
        }
        if (isset($config['columns'])) {
            $this->_defaultColumnSize = $config['columns'] ;
        }
        if (isset($config['useCustomFileInput'])) {
            $this->_customFileInput = $config['useCustomFileInput'];
        }
        $this->colSize = $this->_defaultColumnSize ;
        $this->_defaultConfig['templateClass'] = 'Bootstrap3\View\BootstrapStringTemplate' ;
        parent::__construct($view, $config);
    }
    
    /**
     * Adds the given class to the element options
     *
     * @param array $options Array options/attributes to add a class to
     * @param string|array $class The class name being added.
     * @param string $key the key to use for class.
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
     * 
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
            $this->colSize = $this->_defaultColumnSize ;
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
            'formGroup' => '{{label}}'.($this->horizontal ? '<div class="'.$this->_getColClass('input').'">' : '').'{{prepend}}{{input}}{{append}}'.($this->horizontal ? '</div>' : ''),
            'checkboxContainer' => '<div class="form-group">'
                    .($this->horizontal ? '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">' : '')
                        .'<div class="checkbox">{{content}}</div>'
                    .($this->horizontal ? '</div>' : '')
                .'</div>',
            'radioContainer' => '<div class="form-group">'
                    .($this->horizontal ? '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">' : '')
                        .'{{content}}'
                    .($this->horizontal ? '</div>' : '')
                .'</div>',
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
    protected function _getColClass ($what, $offset = false) {
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

        $options = $this->_parseOptions($fieldName, $options);

        $prepend = $this->_extractOption('prepend', $options, '') ;
        unset($options['prepend']);
        $append = $this->_extractOption('append', $options, '') ;
        unset($options['append']);
        if ($prepend || $append) {
            if ($prepend) {
                if (is_string($prepend)) {
                    $prepend = '<span class="input-group-'.($this->_matchButton($prepend) ? 'btn' : 'addon').'">'.$prepend.'</span>' ;
                }
                else {
                    $prepend = '<span class="input-group-btn">'.implode('', $prepend).'</span>' ;
                }
            }
            if ($append) {
                if (is_string($append)) {
                    $append = '<span class="input-group-'.($this->_matchButton($append) ? 'btn' : 'addon').'">'.$append.'</span>' ;
                }
                else {
                    $append = '<span class="input-group-btn">'.implode('', $append).'</span>' ;
                }
            }
            $prepend = '<div class="input-group">'.$prepend;
            $append .= '</div>';
        }

        $help = $this->_extractOption('help', $options, '');
        unset($options['help']);
        if ($help) {
            $append .= '<p class="help-block">'.$help.'</p>' ;
        }

        $inline = $this->_extractOption('inline', $options, '') ;
        unset ($options['inline']) ;
            
        if ($options['type'] === 'radio') {
            $options['templates'] = [] ;
            if ($inline) {
                $options['templates'] = [
                    'label' => $this->templates('label').'<div></div>',
                    'radioWrapper' => '{{label}}',
                    'nestingLabel' => '{{hidden}}<label{{attrs}} class="radio-inline">{{input}}{{text}}</label>'
                ] ;
            }
            if ($this->horizontal) {
                $options['templates']['radioContainer'] = '<div class="form-group">{{content}}</div>';
            }
            if (empty($options['templates'])) {
                unset($options['templates']);
            }
        }

        $options['_data'] = [
            'prepend' => $prepend,
            'append' => $append
        ];

        return parent::input($fieldName, $options) ;
    }

    /**
     * Generates an group template element
     *
     * @param array $options The options for group template
     * @return string The generated group template
     */
    protected function _groupTemplate($options) {
        $groupTemplate = $options['options']['type'] . 'FormGroup';
        if (!$this->templater()->get($groupTemplate)) {
            $groupTemplate = 'formGroup';
        }
        $data = [
            'input' => $options['input'],
            'label' => $options['label'],
            'error' => $options['error']
        ];
        if (isset($options['options']['_data'])) {
            $data = array_merge($data, $options['options']['_data']);
            unset($options['options']['_data']);
        }
        return $this->templater()->format($groupTemplate, $data);
    }

    /**
     * Generates an input element
     *
     * @param string $fieldName the field name
     * @param array $options The options for the input element
     * @return string The generated input element
     */
    protected function _getInput($fieldName, $options) {
        unset($options['_data']);
        return parent::_getInput($fieldName, $options);
    }

    protected function _getDatetimeTemplate ($fields, $options) {
        $inputs = [] ;
        foreach ($fields as $field => $in) {
            if ($this->_extractOption($field, $options, $in)) {
                array_push($inputs, '<div class="col-md-{{colsize}}">{{'.($field == 'timeFormat' ? 'meridian' : $field).'}}</div>');
            }
        }
        return str_replace('{{colsize}}', round(12 / count($inputs)), '<div class="row">'.implode('', $inputs).'</div>') ;
    }

    /**
     * Creates file input widget.
     *
     * @param string $fieldName Name of a field, in the form "modelname.fieldname"
     * @param array $options Array of HTML attributes.
     * @return string A generated file input.
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-file-inputs
     */
    public function file($fieldName, array $options = []) {
        if (!$this->_customFileInput || (isset($options['default']) && $options['default'])) {
            return parent::file($fieldName, $options);
        }
        if (!isset($options['id'])) {
            $options['id'] = $fieldName ;
        }
        $options += ['secure' => true];
        $options = $this->_initInputField($fieldName, $options);
        unset($options['type']);
        $fileInput = $this->widget('file', array_merge($options, [
            'style' => 'display: none;',
            'onchange' => "document.getElementById('".$options['id']."-input').value = this.files[0].name;"
        ]));
        $fakeInput = $this->text($fieldName, array_merge($options, [
            'readonly' => 'readonly',
            'id' => $options['id'].'-input',
            'onclick' => "document.getElementById('".$options['id']."').click();"
        ]));
        $fakeButton = $this->button(__('Choose File'), [
            'type' => 'button',
            'onclick' => "document.getElementById('".$options['id']."').click();"
        ]);
        return $fileInput.$this->Html->div('input-group', $this->Html->div('input-group-btn', $fakeButton).$fakeInput) ;
    }

    /**
     * Returns a set of SELECT elements for a full datetime setup: day, month and year, and then time.
     *
     * ### Date Options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     *   that string is displayed as the empty element.
     * - `value` | `default` The default value to be used by the input. A value in `$this->data`
     *   matching the field name will override this value. If no default is provided `time()` will be used.
     * - `monthNames` If false, 2 digit numbers will be used instead of text.
     *   If an array, the given array will be used.
     * - `minYear` The lowest year to use in the year select
     * - `maxYear` The maximum year to use in the year select
     * - `orderYear` - Order of year values in select options.
     *   Possible values 'asc', 'desc'. Default 'desc'.
     *
     * ### Time options:
     *
     * - `empty` - If true, the empty select option is shown. If a string,
     * - `value` | `default` The default value to be used by the input. A value in `$this->data`
     *   matching the field name will override this value. If no default is provided `time()` will be used.
     * - `timeFormat` The time format to use, either 12 or 24.
     * - `interval` The interval for the minutes select. Defaults to 1
     * - `round` - Set to `up` or `down` if you want to force rounding in either direction. Defaults to null.
     * - `second` Set to true to enable seconds drop down.
     *
     * To control the order of inputs, and any elements/content between the inputs you
     * can override the `dateWidget` template. By default the `dateWidget` template is:
     *
     * `{{month}}{{day}}{{year}}{{hour}}{{minute}}{{second}}{{meridian}}`
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for the date and time formats chosen.
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-date-and-time-inputs
     */
    public function dateTime($fieldName, array $options = []) {
        $fields = ['year' => true, 'month' => true, 'day' => true, 'hour' => true, 'minute' => true, 'second' => false, 'timeFormat' => false];
        $this->templates([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ]);
        return parent::dateTime($fieldName, $options);
    }

    /**
     * Generate time inputs.
     *
     * ### Options:
     *
     * See dateTime() for time options.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for time formats chosen.
     * @see Cake\View\Helper\FormHelper::dateTime() for templating options.
     */
    public function time($fieldName, array $options = []) {
        $fields = ['hour' => true, 'minute' => true, 'second' => false, 'timeFormat' => false];
        $this->templates([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ]);
        return parent::time($fieldName, $options);
    }

    /**
     * Generate date inputs.
     *
     * ### Options:
     *
     * See dateTime() for date options.
     *
     * @param string $fieldName Prefix name for the SELECT element
     * @param array $options Array of Options
     * @return string Generated set of select boxes for time formats chosen.
     * @see Cake\View\Helper\FormHelper::dateTime() for templating options.
     */
    public function date($fieldName, array $options = []) {
        $fields = ['year' => true, 'month' => true, 'day' => true];
        $this->templates([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ]);
        return parent::date($fieldName, $options);
    }

    /**
     *
     * Create & return a Cakephp options array from the $options specified.
     *
     */
    protected function _createButtonOptions (array $options = array()) {
        $options = $this->_addButtonClasses($options);
        $block = $this->_extractOption('bootstrap-block', $options, false) ;
        unset($options['bootstrap-block']);
        if ($block) {
            $options = $this->addClass($options, 'btn-block') ;
        }
        return $options ;
    }
    
    /**
     * 
     * Create & return a Twitter Like button.
     * 
     * ### New options:
     *
     * - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
     * - bootstrap-size: Twitter bootstrap button size (mini, small, large)
     * 
     */
    public function button($title, array $options = []) {
        return parent::button($title, $this->_createButtonOptions($options)) ;
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
     * Create & return a twitter bootstrap dropdown button. This function is a shortcut for:
     * 
     *   $this->Form->$buttonGroup([
     *     $this->Form->button($title, $options), 
     *     $this->Html->dropdown($menu, [])
     *   ]);
     * 
     * @param $title The text in the button
     * @param $menu HTML tags corresponding to menu options (which will be wrapped
     * 		 into <li> tag). To add separator, pass 'divider'.
     * @param $options Options for button
     * 
     */
    public function dropdownButton ($title, array $menu = [], array $options = []) {
    
        $options['type'] = false ;
        $options['data-toggle'] = 'dropdown' ;
        $options = $this->addClass($options, "dropdown-toggle") ;
        
        return $this->buttonGroup([
            $this->button($title.' <span class="caret"></span>', $options),
            $this->bHtml->dropdown($menu)
        ]);

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
        return parent::submit($caption, $this->_createButtonOptions($options)) ;
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
