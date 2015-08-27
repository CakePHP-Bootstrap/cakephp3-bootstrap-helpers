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

namespace Bootstrap\View\Helper;

use Cake\View\Helper\FormHelper;

class BootstrapFormHelper extends FormHelper {

    use BootstrapTrait ;

    public $helpers = [
        'Html', 
        'Url',
        'bHtml' => [
            'className' => 'Bootstrap.BootstrapHtml'
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
            'checkboxContainer' => '<div class="checkbox">{{content}}</div>', 
            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
            'error' => '<p class="help-block {{attrs.class}}">{{content}}</p>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input class="form-control-file" type="file" name="{{name}}" {{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{prepend}}{{input}}{{append}}',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control {{attrs.class}}" {{attrs}} />',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}>',
            'inputContainer' => '<fieldset class="form-group {{required}}">{{content}}</fieldset>',
            'inputContainerError' => '<fieldset class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</fieldset>',
            'label' => '<label{{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label class="c-input {{attrs.class}}" {{attrs}}>{{input}}<span class="c-indicator"></span>{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}" class="form-control c-select {{attrs.class}}" {{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control {{attrs.class}}" {{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '{{label}}',
            'radioContainer' => '{{content}}', 
            'textarea' => '<textarea name="{{name}}" class="form-control {{attrs.class}}" {{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<fieldset class="form-group">{{content}}</fieldset>',
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
    public $colSize ;

    /**
     * Use custom file inputs (bootstrap style, with javascript).
     *
     * @var boolean
     */
    protected $_customFileInput = false ;

    /**
     * Default colums size.
     *
     * @var array
     */
    protected $_defaultColumnSize = [
        'label' => 2,
        'input' => 10,
        'error' => false
    ];

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
        $this->_defaultConfig['templateClass'] = 'Bootstrap\View\BootstrapStringTemplate' ;
        parent::__construct($view, $config);
    }
    
    /**
     *
     * Replace the templates with the ones specified by newTemplates, call the specified function
     * with the specified parameters, and then restore the old templates.
     *
     * @params $templates The new templates
     * @params $callback  The function to call
     * @params $params    The arguments for the $callback function
     *
     * @return The return value of $callback
     *
    **/
    protected function _wrapTemplates ($templates, $callback, $params) {
        $oldTemplates = array_map ([$this, 'templates'], array_combine(array_keys($templates), array_keys($templates))) ;
        $this->templates ($templates) ;
        $result = call_user_func_array ($callback, $params) ;
        $this->templates ($oldTemplates) ;
        return $result ;
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
        return strpos($html, '<button') !== FALSE || strpos($html, 'type="submit"') !== FALSE || preg_match('#class="(.*)? btn (.*)?"#', $html) ;
    }

    /**
     *
     * Return the col size class for the specified column (label, input or error).
     *
    **/
    protected function _getColClass ($what, $offset = false) {
        if ($what === 'error' && $this->colSize[$what] === false) {
            return $this->_getColClass('label', true).' '.$this->_getColClass ('input') ;
        }
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
     * Set the default templates according to the inner properties of the form ($this->horizontal and $this->inline).
     *
    **/
    protected function _setDefaultTemplates () {
        $this->templates ($this->_defaultConfig['templates']);
        if ($this->horizontal) {
            $this->templates([
                'formGroup' => '{{label}}<div class="'.$this->_getColClass('input').'">{{prepend}}{{input}}{{append}}</div>',
                'label' => str_replace('{{attrs}}', 
                    ' class="form-control-label '.$this->_getColClass('label').' {{attrs.class}}"{{attrs}}', 
                    $this->templates('label')
                ),
                'error' => str_replace('{{attrs.class}}', $this->_getColClass('error').' {{attrs.class}}', $this->templates('error')),
                'checkboxContainer' => '<fieldset class="form-group row"><div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">'
                    .$this->templates('checkboxContainer').'</div></fieldset>',
                'radioContainer' => '<fieldset class="form-group row">'.$this->templates('radioContainer').'</fieldset>',
                'submitContainer' => str_replace('form-group', 'form-group row', str_replace('{{content}}', 
                    '<div class="'.$this->_getColClass('label', true).' '.$this->_getColClass('input').'">{{content}}</div>',
                    $this->templates('submitContainer')
                )),
                'inputContainer' => str_replace('form-group', 'form-group row', $this->templates('inputContainer')),
                'inputContainerError' => str_replace('form-group', 'form-group row', $this->templates('inputContainerError')),
            ]);
        }
        if ($this->inline) {
            $this->templates([
                'label' => str_replace('{{attrs.class}}', 'sr-only {{attrs.class}}', $this->templates('label')),
                'inputContainer' => $this->templates('inputContainer').'&nbsp;'
            ]) ;
        }
    }
    
    /**
     * 
     * Create a Twitter Bootstrap like form. 
     * 
     * New options available:
     *     - horizontal: boolean, specify if the form is horizontal
     *     - inline: boolean, specify if the form is inline
     * 
     * Unusable options:
     *     - inputDefaults
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
        $this->horizontal = $this->inline = false ;
        if (isset($options['type'])) {
            $this->{$options['type']} = true ;
            unset ($options['type']) ;
        }
        else {
            $this->horizontal = $this->_extractOption('horizontal', $options, false);
            unset($options['horizontal']);
            $this->inline = $this->_extractOption('inline', $options, false) ;
            unset($options['inline']) ;
        }
        if ($this->inline) {
            $options = $this->addClass($options, 'form-inline') ;
        }
        $options['role'] = 'form' ;
        $this->_setDefaultTemplates () ;
        return parent::create($model, $options) ;
    }

    /**
     *
     * Switch horizontal mode on or off.
     *
    **/
    public function setHorizontal ($horizontal) {
        $this->horizontal = $horizontal ;
        $this->_setDefaultTemplates () ;
    }

    /**
     *
     * Wrap the input into span or div with input-group-addon or input-group-btn depending of the
     * type of the inputs.
     *
     * @param $content Inputs - Maybe false (return false), a string or an array.
     *
     * @return An HTML string containing the input wrapped into correct span or div.
     */
    public function _inputGroup ($content) {
        if ($content === false) {
            return $content ;
        }
        $tag = 'span' ;
        $options = [] ;
        if (is_string($content)) {
            $content = [$content] ;
        }
        $inner = '' ;
        foreach ($content as $ctn) {
            $options['class'] = 'input-group-'.($this->_matchButton($ctn) ? 'btn' : 'addon') ;
            if (preg_match('#^<div(.*)?class="(.*)?dropdown(.*)?"(.*)?</div>$#', $ctn)) {
                $tag = 'div' ;
                $ctn = substr($ctn, strpos($ctn, '>') + 1);
                $ctn = substr($ctn, 0, strlen($ctn) - 6);
            }
            $inner .= $ctn ;
        }
        return $this->Html->tag ($tag, $inner, $options) ;
    }

    public function prepend ($input, $prepend) {
        if ($prepend) {
            $prepend = $this->_inputGroup ($prepend) ;
        }
        if ($input === null) {
            return '<div class="input-group">'.$prepend ;
        }
        return $this->_wrap($input, $prepend, null);
    }

    public function append ($input, $append) {
        $append = $this->_inputGroup ($append) ;
        if ($input === null) {
            return $append.'</div>' ;
        }
        return $this->_wrap($input, null, $append);
    }

    public function wrap ($input, $prepend, $append) {
        return $this->prepend(null, $prepend).$input.$this->append(null, $append);
    }

    protected function _wrap ($input, $prepend, $append) {
        return '<div class="input-group">'.$prepend.$input.$append.'</div>' ;
    }
    
    /** 
     * 
     * Create & return an input block (Twitter Boostrap Like).
     * 
     * New options:
     *     - prepend: 
     *         -> string: Add <span class="add-on"> before the input
     *         -> array: Add elements in array before inputs
     *     - append: Same as prepend except it add elements after input
     *        
    **/
    public function input($fieldName, array $options = []) {

        $options = $this->_parseOptions($fieldName, $options);

        $prepend = $this->_extractOption('prepend', $options, false) ;
        unset($options['prepend']);
        $append = $this->_extractOption('append', $options, false) ;
        unset($options['append']);
        if ($prepend || $append) {
            $prepend = $this->prepend(null, $prepend);
            $append  = $this->append(null, $append);
        }

        $help = $this->_extractOption('help', $options, '');
        unset($options['help']);
        if ($help) {
            $append .= '<p class="help-block">'.$help.'</p>' ;
        }
        
        $type = strtolower($options['type']) ;

        $inline = $this->_extractOption('inline', $options, $type === 'multicheckbox' ? true : false) ;
        unset ($options['inline']) ;
            
        if (!isset($options['templates'])) $options['templates'] = [] ;
        if (in_array($type, ['radio', 'checkbox', 'multicheckbox'])) {
            $custom = $type === 'multicheckbox' ? 'checkbox' : $type ;
            $options['templates'] += [
                'nestingLabel' => str_replace('c-input', 'c-input c-'.$custom, $this->templates('nestingLabel')) 
            ] ;
        }
        if ($type === 'radio') {
            $options['templates'] += [
                'formGroup' => str_replace('{{input}}', 
                    '<div class="radio'.($inline ? '' : ' c-inputs-stacked').'">{{input}}</div>',
                    $this->templates('formGroup'))
            ] ;
        }
        if ($type === 'multicheckbox' && $inline) {
            $options['templates'] += [
                'checkboxWrapper' => '{{label}}',
                'formGroup' => str_replace('{{input}}', '<div class="checkbox">{{input}}</div>', $this->templates('formGroup'))
            ] ;
        }
        
        $label = $this->_extractOption('label', $options, true) ;
        if ($this->horizontal && !$label) {
            if (!isset($options['templates']['formGroup'])) $options['templates']['formGroup'] = $this->templates('formGroup') ;
            $options['templates']['formGroup'] = str_replace($this->_getColClass('input'), 
                $this->_getColClass('label', true).' '.$this->_getColClass('input'),
                $options['templates']['formGroup']
            ) ;
        }
        
        if (empty($options['templates'])) {
            unset($options['templates']);
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
                if ($field === 'timeFormat') $field = 'meridian' ; // Template uses "meridian" instead of timeFormat
                $inputs[$field] = '<div class="col-md-{{colsize}}">{{'.$field.'}}</div>';
            }
        }
        $tplt = $this->templates('dateWidget');
        $tplt = explode('}}{{', substr($tplt, 2, count($tplt) - 3));
        $html = '' ;
        foreach ($tplt as $v) {
            if (isset($inputs[$v])) {
                $html .= $inputs[$v] ;
            }
        }
        return str_replace('{{colsize}}', round(12 / count($inputs)), '<div class="row">'.$html.'</div>') ;
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
        $countLabel = $this->_extractOption('count-label', $options, __('files selected'));
        unset($options['count-label']);
        $fileInput = $this->widget('file', array_merge($options, [
            'style' => 'display: none;',
            'onchange' => "document.getElementById('".$options['id']."-input').value = (this.files.length <= 1) ? this.files[0].name : this.files.length + ' ' + '" . $countLabel . "';"
        ]));
        $fakeInput = $this->text($fieldName, array_merge($options, [
            'readonly' => 'readonly',
            'id' => $options['id'].'-input',
            'onclick' => "document.getElementById('".$options['id']."').click();"
        ]));
        $buttonLabel = $this->_extractOption('button-label', $options, __('Choose File'));
        unset($options['button-label']) ;
        $fakeButton = $this->button($buttonLabel, [
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
        return $this->_wrapTemplates ([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ], 'parent::dateTime', [$fieldName, $options]);
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
        return $this->_wrapTemplates ([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ], 'parent::time', [$fieldName, $options]);
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
        return $this->_wrapTemplates ([
            'dateWidget' => $this->_getDatetimeTemplate($fields, $options)
        ], 'parent::date', [$fieldName, $options]);
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
     */
    public function buttonGroup ($buttons, array $options = []) {
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
     */
    public function buttonToolbar (array $buttonGroups, array $options = []) {
        $options = $this->addClass($options, 'btn-toolbar') ;
        return $this->Html->tag('div', implode('', $buttonGroups), $options) ;
    }
    
    /**
     * 
     * Create & return a Twitter Like submit input.
     * 
     * New options:
     *     - bootstrap-type: Twitter bootstrap button type (primary, danger, info, etc.)
     *     - bootstrap-size: Twitter bootstrap button size (mini, small, large)
     * 
     * Unusable options: div
     * 
    **/    
    public function submit($caption = null, array $options = array()) {
        return parent::submit($caption, $this->_createButtonOptions($options)) ;
    }

}

?>
