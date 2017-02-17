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

use Bootstrap\View\FlexibleStringTemplateTrait;

/**
 * Form helper library.
 *
 * Automatic generation of HTML FORMs from given data.
 *
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 *
 * @link http://book.cakephp.org/3.0/en/views/helpers/form.html
 */
class FormHelper extends \Cake\View\Helper\FormHelper {

    use ClassTrait;
    use EasyIconTrait;
    use FlexibleStringTemplateTrait;

    /**
     * Other helpers used by FormHelper.
     *
     * @var array
     */
    public $helpers = [
        'Url', 'Html'
    ];

    /**
     * Default configuration for the helper.
     *
     * - `idPrefix` See CakePHP `FormHelper`.
     * - `errorClass` See CakePHP `FormHelper`. Overriden by `'has-error'`.
     * - `typeMap` See CakePHP `FormHelper`.
     * - `templates` Templates for the various form elements.
     * - `templateClass` Class used to format the various template. Do not override!
     * - `buttons` Default options for buttons.
     * - `columns` Default column sizes for horizontal forms.
     * - `useCustomFileInput` Set to `true` to use the custom file input. Default is `false`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'idPrefix' => null,
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
            'checkboxContainer' => '<div class="checkbox {{required}}">{{content}}</div>',
            'checkboxContainerHorizontal' => '<div class="form-group"><div class="{{inputColumnOffsetClass}} {{inputColumnClass}}"><div class="checkbox {{required}}">{{content}}</div></div></div>',
            'dateWidget' => '{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}',
            'error' => '<span class="help-block error-message">{{content}}</span>',
            'errorHorizontal' => '<span class="help-block error-message {{errorColumnClass}}">{{content}}</span>',
            'errorList' => '<ul>{{content}}</ul>',
            'errorItem' => '<li>{{text}}</li>',
            'file' => '<input type="file" name="{{name}}" {{attrs}}>',
            'fieldset' => '<fieldset{{attrs}}>{{content}}</fieldset>',
            'formStart' => '<form{{attrs}}>',
            'formEnd' => '</form>',
            'formGroup' => '{{label}}{{prepend}}{{input}}{{append}}',
            'formGroupHorizontal' => '{{label}}<div class="{{inputColumnClass}}">{{prepend}}{{input}}{{append}}</div>',
            'hiddenBlock' => '<div style="display:none;">{{content}}</div>',
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}}" {{attrs}} />',
            'inputSubmit' => '<input type="{{type}}"{{attrs}}>',
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</div>',
            'label' => '<label class="control-label{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'labelHorizontal' => '<label class="control-label {{labelColumnClass}}{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'labelInline' => '<label class="sr-only{{attrs.class}}"{{attrs}}>{{text}}</label>',
            'nestingLabel' => '{{hidden}}<label{{attrs}}>{{input}}{{text}}</label>',
            'legend' => '<legend>{{text}}</legend>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'selectColumn' => '<div class="col-md-{{columnSize}}"><select name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select></div>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control{{attrs.class}}" {{attrs}}>{{content}}</select>',
            'radio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'radioWrapper' => '<div class="radio">{{label}}</div>',
            'radioContainer' => '<div class="form-group">{{content}}</div>',
            'inlineRadio' => '<input type="radio" name="{{name}}" value="{{value}}"{{attrs}}>',
            'inlineRadioWrapper' => '{{label}}',
            'inlineRadioContainer' => '<div class="form-group">{{content}}</div>',
            'inlineRadioNestingLabel' => '{{hidden}}<label{{attrs}} class="radio-inline">{{input}}{{text}}</label>',
            'textarea' => '<textarea name="{{name}}" class="form-control{{attrs.class}}" {{attrs}}>{{value}}</textarea>',
            'submitContainer' => '<div class="form-group">{{submitContainerHorizontalStart}}{{content}}{{submitContainerHorizontalEnd}}</div>',
            'submitContainerHorizontal' => '<div class="form-group"><div class="{{inputColumnOffsetClass}} {{inputColumnClass}}">{{content}}</div></div>',

            'inputGroup' => '{{inputGroupStart}}{{input}}{{inputGroupEnd}}',
            'inputGroupStart' => '<div class="input-group">{{prepend}}',
            'inputGroupEnd' => '{{append}}</div>',
            'inputGroupAddons' => '<span class="input-group-addon">{{content}}</span>',
            'inputGroupButtons' => '<span class="input-group-btn">{{content}}</span>',
            'helpBlock' => '<p class="help-block">{{content}}</p>',
            'buttonGroup' => '<div class="btn-group{{attrs.class}}"{{attrs}}>{{content}}</div>',
            'buttonToolbar' => '<div class="btn-toolbar{{attrs.class}}"{{attrs}}>{{content}}</div>',
            'fancyFileInput' => '{{fileInput}}<div class="input-group"><div class="input-group-btn">{{button}}</div>{{input}}</div>'
        ],
        'buttons' => [
            'type' => 'default'
        ],
        'columns' => [
            'label' => 2,
            'input' => 10,
            'error' => 0
        ],
        'useCustomFileInput' => false
    ];

    /**
     * Default widgets.
     *
     * @var array
     */
    protected $_defaultWidgets = [
        '_default' => ['Cake\View\Widget\BasicWidget'],
        'button' => ['Cake\View\Widget\ButtonWidget'],
        'checkbox' => ['Cake\View\Widget\CheckboxWidget'],
        'file' => ['Cake\View\Widget\FileWidget'],
        'fancyFile' => ['Bootstrap\View\Widget\FancyFileWidget', 'file', 'button', 'basic'],
        'label' => ['Cake\View\Widget\LabelWidget'],
        'nestingLabel' => ['Cake\View\Widget\NestingLabelWidget'],
        'multicheckbox' => ['Cake\View\Widget\MultiCheckboxWidget', 'nestingLabel'],
        'radio' => ['Cake\View\Widget\RadioWidget', 'nestingLabel'],
        'inlineRadioNestingLabel' => ['Bootstrap\View\Widget\InlineRadioNestingLabelWidget'],
        'inlineRadio' => ['Bootstrap\View\Widget\InlineRadioWidget', 'inlineRadioNestingLabel'],
        'select' => ['Cake\View\Widget\SelectBoxWidget'],
        'selectColumn' => ['Bootstrap\View\Widget\ColumnSelectBoxWidget'],
        'textarea' => ['Cake\View\Widget\TextareaWidget'],
        'datetime' => ['Bootstrap\View\Widget\DateTimeWidget', 'selectColumn']
    ];

    /**
     * Indicates if horizontal mode is enabled.
     *
     * @var bool
     */
    public $horizontal = false;

    /**
     * Indicates if inline mode is enabled.
     *
     * @var bool
     */
    public $inline = false;

    /**
     * {@inheritDoc}
     */
    public function __construct(\Cake\View\View $View, array $config = []) {
        if (!isset($config['templateCallback'])) {
            $that = $this;
            $config['templateCallback'] = function ($name, $data) use ($that) {
                $data['templateName'] = $name;
                if ($that->horizontal) $data['templateName'] .= 'Horizontal';
                else if ($that->inline) $data['templateName'] .= 'Inline';
                $data += [
                    'inputColumnClass' => $this->_getColumnClass('input'),
                    'labelColumnClass' => $this->_getColumnClass('label'),
                    'errorColumnClass' => $this->_getColumnClass('error'),
                    'inputColumnOffsetClass' => $this->_getColumnClass('label', true),
                ];
                if (!$that->templates($data['templateName'])) {
                    $data['templateName'] = $name;
                }
                return $data;
            };
        }
        parent::__construct($View, $config);
    }

    /**
     * Check if the given HTML string corresponds to a button or a submit input.
     *
     * @param string $html The HTML code to check
     *
     * @return bool `true` if the HTML code contains a button or a submit input,
     * false otherwize.
     */
    protected function _matchButton($html) {
        return strpos($html, '<button') !== FALSE || strpos($html, 'type="submit"') !== FALSE;
    }

    /**
     * Returns an HTML form element.
     *
     * ### Options
     *
     * - `context` Additional options for the context class. For example the
     * EntityContext accepts a 'table' option that allows you to set the specific Table
     * class the form should be based on.
     * - `encoding` Set the accept-charset encoding for the form. Defaults to
     * `Configure::read('App.encoding')`.
     * - `enctype` Set the form encoding explicitly. By default `type => file` will set
     * `enctype` to `multipart/form-data`.
     * - `horizontal` Boolean specifying if the form should be horizontal.
     * - `idPrefix` Prefix for generated ID attributes.
     * - `inline` Boolean specifying if the form should be inlined.
     * - `method` Set the form's method attribute explicitly.
     * - `templates` The templates you want to use for this form. Any templates will be
     * merged on top of the already loaded templates. This option can either be a filename
     * in /config that contains the templates you want to load, or an array of templates
     * to use.
     * - `templateVars` Provide template variables for the formStart template.
     * - `type` Form method defaults to autodetecting based on the form context. If
     *   the form context's isCreate() method returns false, a PUT request will be done.
     * - `url` The URL the form submits to. Can be a string or a URL array. If you use 'url'
     *    you should leave 'action' undefined.
     *
     * @param mixed $model The context for which the form is being defined. Can
     *   be an ORM entity, ORM resultset, or an array of meta data. You can use false or null
     *   to make a model-less form.
     * @param array $options An array of html attributes and options.
     *
     * @return string An formatted opening FORM tag.
     */
    public function create($model = null, Array $options = array()) {
        $options += [
            'horizontal' => false,
            'inline' => false
        ];
        $this->horizontal = $options['horizontal'];
        $this->inline = $options['inline'];
        unset($options['horizontal'], $options['inline']);
        if ($this->horizontal) {
            $options = $this->addClass($options, 'form-horizontal');
        }
        else if ($this->inline) {
            $options = $this->addClass($options, 'form-inline');
        }
        $options['role'] = 'form';
        return parent::create($model, $options);
    }

    /**
     * Retrieve classes for the size of the specified column (label, input or error),
     * optionally adding the offset prefix to the classes.
     *
     * @param string $what The type of the column (`'label'`, `'input'`, `'error'`).
     * @param bool   $offset Set to `true` to add the offset prefix.
     *
     * @return string The classes for the size or offset of the specified column.
     */
    protected function _getColumnClass($what, $offset = false) {
        $columns = $this->getConfig('columns');
        if ($what === 'error'
            && isset($columns['error']) && $columns['error'] == 0) {
            return $this->_getColumnClass('label', true).' '.$this->_getColumnClass('input');
        }
        if (isset($columns[$what])) {
            return 'col-md-'.($offset ? 'offset-' : '').$columns[$what];
        }
        $classes = [];
        foreach ($columns as $cl => $arr) {
            if (isset($arr[$what])) {
                $classes[] = 'col-'.$cl.'-'.($offset ? 'offset-' : '').$arr[$what];
            }
        }
        return implode(' ', $classes);
    }

    /**
     * Wraps the given string corresponding to add-ons or buttons inside a HTML wrapper
     * element.
     *
     * If `$addonOrButtons` is an array, it should contains buttons and will be wrapped
     * accordingly. If `$addonOrButtons` is a string, the wrapper will be chosen depending
     * on the content (see `_matchButton()`).
     *
     * @param string|array $addonOrButtons Content to be wrapped or array of buttons to be
     * wrapped.
     *
     * @return string The elements wrapped in a suitable HTML element.
     */
    protected function _wrapInputGroup($addonOrButtons) {
        if ($addonOrButtons) {
            $template = 'inputGroupButtons';
            if (is_string($addonOrButtons)) {
                $addonOrButtons = $this->_makeIcon($addonOrButtons);
                if (!$this->_matchButton($addonOrButtons)) {
                    $template = 'inputGroupAddons';
                }
            }
            else {
                $addonOrButtons = implode('', $addonOrButtons);
            }
            $addonOrButtons = $this->formatTemplate($template, [
                'content' => $addonOrButtons
            ]);
        }
        return $addonOrButtons;
    }

    /**
     * Concatenates and wraps `$input`, `$prepend` and `$append` inside an input group.
     *
     * @param string $input The input content.
     * @param string $prepend The content to prepend to `$input`.
     * @param string $append The content to append to `$input`.
     *
     * @return string A string containing the three elements concatenated an wrapped inside
     * an input group `<div>`.
     */
    protected function _wrap($input, $prepend, $append) {
        return $this->formatTemplate('inputGroup', [
            'inputGroupStart' => $this->formatTemplate('inputGroupStart', [
                'prepend' => $prepend
            ]),
            'input' => $input,
            'inputGroupEnd' => $this->formatTemplate('inputGroupEnd', [
                'append' => $append
            ])
        ]);
    }

    /**
     * Prepend the given content to the given input or create an opening input group.
     *
     * @param string|null $input Input to which `$prepend` will be prepend, or
     * null to create an opening input group.
     * @param string|array $prepend The content to prepend.,
     *
     * @return string The input with the content of `$prepend` prepended or an
     * opening `<div>` for an input group.
     */
    public function prepend($input, $prepend) {
        $prepend = $this->_wrapInputGroup($prepend);
        if ($input === null) {
            return $this->formatTemplate('inputGroupStart', ['prepend' => $prepend]);
        }
        return $this->_wrap($input, $prepend, null);
    }

    /**
     * Append the given content to the given input or close an input group.
     *
     * @param string|null $input Input to which `$append` will be append, or
     * null to create a closing element for an input group.
     * @param string|array $append The content to append.,
     *
     * @return string The input with the content of `$append` appended or a
     * closing `</div>` for an input group.
     */
    public function append($input, $append) {
        $append = $this->_wrapInputGroup($append);
        if ($input === null) {
            return $this->formatTemplate('inputGroupEnd', ['append' => $append]);
        }
        return $this->_wrap($input, null, $append);
    }

    /**
     * Wrap the given `$input` between `$prepend` and `$append`.
     *
     * @param string $input The input to be wrapped (see `prepend()` and `append()`).
     * @param string|array $prepend The content to prepend (see `prepend()`).
     * @param string|array $append The content to append (see `append()`).
     *
     * @return string A string containing the given `$input` wrapped between `$prepend` and
     * `$append` according to the behavior of `prepend()` and `append()`.
     */
    public function wrap($input, $prepend, $append) {
        return $this->prepend(null, $prepend).$input.$this->append(null, $append);
    }

    /**
     * Generates a form input element complete with label and wrapper div.
     *
     * ### Options
     *
     * See each field type method for more information. Any options that are part of
     * `$attributes` or `$options` for the different **type** methods can be included
     * in `$options` for input().
     * Additionally, any unknown keys that are not in the list below, or part of the
     * selected type's options will be treated as a regular HTML attribute for the
     * generated input.
     *
     * - `append` Content to append to the input, may be a string or an array of buttons.
     * - `empty` String or boolean to enable empty select box options.
     * - `error` Control the error message that is produced. Set to `false` to disable
     * any kind of error reporting (field error and error messages).
     * - `help` Help message to add below the input.
     * - `label` Either a string label, or an array of options for the label.
     * See FormHelper::label().
     * - `labelOptions` - Either `false` to disable label around nestedWidgets e.g. radio, multicheckbox or an array
     *   of attributes for the label tag. `selected` will be added to any classes e.g. `class => 'myclass'` where
     *   widget is checked.
     * - `nestedInput` Used with checkbox and radio inputs. Set to false to render
     * inputs outside of label elements. Can be set to true on any input to force the
     * input inside the label. If you enable this option for radio buttons you will also
     * need to modify the default `radioWrapper` template.
     * - `inline` Only used with radio inputs, set to `true` to have inlined radio buttons.
     * - `options` For widgets that take options e.g. radio, select.
     * - `templates` The templates you want to use for this input. Any templates will be
     * merged on top of the already loaded templates. This option can either be a filename
     * in /config that contains the templates you want to load, or an array of templates
     * to use.
     * - `prepend` Content to prepend to the input, may be a string or an array of buttons.
     * - `templateVars` Array of template variables.
     * - `type` Force the type of widget you want. e.g. `type => 'select'`
     *
     * @param string $fieldName This should be "modelname.fieldname"
     * @param array $options Each type of input takes different options.
     *
     * @return string Completed form widget.
     */
    public function control($fieldName, array $options = array()) {

        $options += [
            'templateVars' => [],
            'prepend'      => false,
            'append'       => false,
            'help'         => false,
            'inline'       => false
        ];

        $options = $this->_parseOptions($fieldName, $options);

        $prepend = $options['prepend'];
        $append = $options['append'];
        $help = $options['help'];
        $inline = $options['inline'];
        unset($options['prepend'], $options['append'],
            $options['help'], $options['inline']);

        if ($prepend || $append) {
            $prepend = $this->prepend(null, $prepend);
            $append  = $this->append(null, $append);
        }

        if ($help) {
            $append .= $this->formatTemplate('helpBlock', ['content' => $help]);
        }

        if ($options['type'] === 'radio' && $inline) {
            $options['type'] = 'inlineRadio';
        }

        $options['templateVars'] += [
            'prepend' => $prepend,
            'append' => $append
        ];

        return parent::control($fieldName, $options);
    }

    /**
     * {@inheritDoc}
     */
    protected function _getInput($fieldName, $options) {
        $label = $options['labelOptions'];
        switch (strtolower($options['type'])) {
            case 'inlineradio':
                $opts = $options['options'];
                unset($options['options'], $options['labelOptions']);
                return $this->inlineRadio($fieldName, $opts, $options + ['label' => $label]);
        }
        return parent::_getInput($fieldName, $options);
    }


    /**
     * Creates a set of inline radio widgets.
     *
     * ### Attributes:
     *
     * - `value` - Indicates the value when this radio button is checked.
     * - `label` - Either `false` to disable label around the widget or an array of attributes for
     *    the label tag. `selected` will be added to any classes e.g. `'class' => 'myclass'` where widget
     *    is checked
     * - `hiddenField` - boolean to indicate if you want the results of radio() to include
     *    a hidden input with a value of ''. This is useful for creating radio sets that are non-continuous.
     * - `disabled` - Set to `true` or `disabled` to disable all the radio buttons.
     * - `empty` - Set to `true` to create an input with the value '' as the first option. When `true`
     *   the radio label will be 'empty'. Set this option to a string to control the label value.
     *
     * @param string $fieldName Name of a field, like this "modelname.fieldname"
     * @param array|\Traversable $options Radio button options array.
     * @param array $attributes Array of attributes.
     *
     * @return string Completed radio widget set.
     */
    public function inlineRadio($fieldName, $options = [], array $attributes = []) {
        $attributes['options'] = $options;
        $attributes['idPrefix'] = $this->_idPrefix;
        $attributes = $this->_initInputField($fieldName, $attributes);
        $hiddenField = isset($attributes['hiddenField']) ? $attributes['hiddenField'] : true;
        unset($attributes['hiddenField']);
        $radio = $this->widget('inlineRadio', $attributes);
        $hidden = '';
        if ($hiddenField) {
            $hidden = $this->hidden($fieldName, [
                'value' => '',
                'form' => isset($attributes['form']) ? $attributes['form'] : null,
                'name' => $attributes['name'],
            ]);
        }
        return $hidden . $radio;
    }

    /**
     * Creates file input widget.
     *
     * **Note:** If the configuration value of `useCustomFileInput` is `false`, this methods
     * is equivalent to `FormHelper::file`.
     *
     * @param string $fieldName Name of a field, in the form "modelname.fieldname"
     * @param array $options Array of HTML attributes.
     *
     * @return string A generated file input.
     */
    public function file($fieldName, array $options = []) {
        $options += ['secure' => true];
        $options = $this->_initInputField($fieldName, $options);
        unset($options['type']);
        if (!$this->getConfig('useCustomFileInput')) {
            return $this->widget('file', $options);
        }
        $options += ['_button' => []];
        $options['_button'] = $this->_addButtonClasses($options['_button']);
        return $this->widget('fancyFile', $options);
    }

    /**
     * Creates a `<button>` tag.
     *
     * The type attribute defaults to `type="submit"`
     * You can change it to a different value by using `$options['type']`.
     *
     * ### Options
     *
     * - `bootstrap-type` Twitter bootstrap button type (primary, danger, info, etc.)
     * - `bootstrap-size` Twitter bootstrap button size (mini, small, large)
     * - `escape` HTML entity encode the $title of the button. Defaults to `false`.
     *
     * @param string $title The button's caption. Not automatically HTML encoded
     * @param array $options Array of options and HTML attributes.
     *
     * @return string A HTML button tag.
     *
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-button-elements
     */
    public function button($title, array $options = []) {
        return $this->_easyIcon('parent::button', $title,
                                $this->_addButtonClasses($options));
    }

    /**
     * Creates a button group using the given buttons.
     *
     * ### Options
     *
     * - `vertical` Specifies if the group should be vertical. Default to `false`.
     * Other options are passed to the `Html::tag` method.
     *
     * @param array $buttons Array of buttons for the group.
     * @param array $options Array of options. See above.
     *
     * @return string A HTML string containing the button group.
     */
    public function buttonGroup($buttons, array $options = []) {
        $options += [
            'vertical' => false,
            'templateVars' => []
        ];
        if ($options['vertical']) {
            $options = $this->addClass($options, 'btn-group-vertical');
        }
        return $this->formatTemplate('buttonGroup', [
            'content' => implode('', $buttons),
            'attrs' => $this->templater()->formatAttributes($options, ['vertical']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Creates a button toolbar using the given button groups.
     *
     * @param array $buttonGroups Array of groups for the toolbar
     * @param array $options Array of options for the `Html::div` method.
     *
     * @return string A HTML string containing the button toolbar.
     */
    public function buttonToolbar(array $buttonGroups, array $options = array()) {
        $options += [
            'templateVars' => []
        ];
        return $this->formatTemplate('buttonToolbar', [
            'content' => implode('', $buttonGroups),
            'attrs' => $this->templater()->formatAttributes($options, ['vertical']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Creates a dropdown button.
     *
     * This function is a shortcut for:
     *
     * ```php
     *   $this->Form->$buttonGroup([
     *     $this->Form->button($title, $options),
     *     $this->Html->dropdown($menu, [])
     *   ]);
     * ```
     *
     * @param string $title The text for the button.
     * @param array $menu HTML elements corresponding to menu options (which will be wrapped
     * into `<li>` tag). To add separator, pass 'divider'. See `BootstrapHtml::dropdown()`.
     * @param array $options Array of options for the button. See `button()`.
     *
     * @return string A HTML string containing the button dropdown.
     */
    public function dropdownButton($title, array $menu = [], array $options = []) {
        $options['type'] = false;
        $options['data-toggle'] = 'dropdown';
        $options = $this->addClass($options, "dropdown-toggle");
        return $this->buttonGroup([
            $this->button($title.' <span class="caret"></span>', $options),
            $this->Html->dropdown($menu)
        ]);

    }

    /**
     * Creates a submit button element. This method will generate `<input />` elements that
     * can be used to submit, and reset forms by using $options. image submits can be
     * created by supplying an image path for $caption.
     *
     * ### Options
     *
     * - `bootstrap-size` Twitter bootstrap button size (mini, small, large)
     * - `bootstrap-type` Twitter bootstrap button type (primary, danger, info, etc.)
     * - `templateVars` Additional template variables for the input element and its container.
     * - `type` Set to 'reset' for reset inputs. Defaults to 'submit'
     * - Other attributes will be assigned to the input element.
     *
     * @param string|null $caption The label appearing on the button OR if string
     * contains :// or the  extension .jpg, .jpe, .jpeg, .gif, .png use an image if
     * the extension exists, AND the first character is /, image is relative to webroot,
     *  OR if the first character is not /, image is relative to webroot/img.
     *
     * @param array $options Array of options. See above.
     *
     * @return string A HTML submit button
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-buttons-and-submit-elements
     */
    public function submit($caption = null, array $options = array()) {
        return parent::submit($caption, $this->_addButtonClasses($options));
    }

}

?>
