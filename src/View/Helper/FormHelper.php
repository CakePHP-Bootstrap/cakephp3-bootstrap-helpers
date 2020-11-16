<?php
declare(strict_types=1);

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @link        https://holt59.github.io/cakephp3-bootstrap-helpers/
 */
namespace Bootstrap\View\Helper;

use Bootstrap\View\FlexibleStringTemplateTrait;
use Cake\Utility\Hash;
use Cake\View\View;

/**
 * Form helper library.
 *
 * Automatic generation of HTML FORMs from given data.
 *
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 * @link http://book.cakephp.org/3.0/en/views/helpers/form.html
 */
class FormHelper extends \Cake\View\Helper\FormHelper
{
    use ClassTrait;
    use EasyIconTrait;
    use FlexibleStringTemplateTrait;

    /**
     * Other helpers used by FormHelper.
     *
     * @var array
     */
    public $helpers = [
        'Url', 'Html',
    ];

    /**
     * Default configuration for this helper.
     * Don't override parent::$_defaultConfig for robustness
     *
     * @var array
     */
    protected $helperConfig = [
        'errorClass' => 'is-invalid',
        'templates' => [
            // Used for checkboxes in checkbox() and multiCheckbox().
            'checkbox' => '<input type="checkbox" class="form-check-input{{attrs.class}}" name="{{name}}" value="{{value}}"{{attrs}}>',
            // Wrapper container for checkboxes.
            'checkboxWrapper' => '<div class="form-check">{{label}}</div>',
            'checkboxContainer' => '<div class="form-check checkbox{{required}}">{{content}}</div>',
            'checkboxContainerHorizontal' => '<div class="form-group row"><div class="{{labelColumnClass}}"></div><div class="{{inputColumnClass}}"><div class="form-check checkbox{{required}}">{{content}}</div></div></div>',
            'multicheckboxContainer' => '<fieldset class="form-group {{type}}{{required}}">{{content}}</fieldset>',
            'multicheckboxContainerHorizontal' => '<fieldset class="form-group {{type}}{{required}}"><div class="row">{{content}}</div></fieldset>','dateWidget' => '<div class="row">{{year}}{{month}}{{day}}{{hour}}{{minute}}{{second}}{{meridian}}</div>',
            // Error message wrapper elements.
            'error' => '<div class="error-message invalid-feedback">{{content}}</div>',
            'errorInline' => '<div class="error-message invalid-feedback">{{content}}</div>',
            // General grouping container for control(). Defines input/label ordering.
            'formGroup' => '{{label}}{{prepend}}{{input}}{{append}}',
            'formGroupHorizontal' => '{{label}}<div class="{{inputColumnClass}}">{{prepend}}{{input}}{{append}}{{error}}</div>',
            // Generic input element.
            'input' => '<input type="{{type}}" name="{{name}}" class="form-control{{attrs.class}}" {{attrs}} />',
            // Container element used by control().
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}</div>',
            'inputContainerHorizontal' => '<div class="form-group row {{type}}{{required}}">{{content}}</div>',
            // Container element used by control() when a field has an error.
            'inputContainerError' => '<div class="form-group has-error {{type}}{{required}}">{{content}}{{error}}</div>',
            'inputContainerErrorHorizontal' => '<div class="form-group row has-error {{type}}{{required}}">{{content}}</div>',
            // Label horizontal
            'labelHorizontal' => '<label class="col-form-label {{labelColumnClass}}{{attrs.class}}"{{attrs}}>{{text}}</label>',
            // Label inline
            'labelInline' => '<label class="sr-only{{attrs.class}}"{{attrs}}>{{text}}</label>',
            // Label element used for radio and multi-checkbox inputs.
            'nestingLabel' => '{{hidden}}<label class="form-check-label{{attrs.class}}"{{attrs}}>{{input}} {{text}}</label>',
            'labelLegend' => '<label{{attrs}}>{{text}}</label>',
            'labelLegendHorizontal' => '<legend class="col-form-label pt-0 {{labelColumnClass}}{{attrs.class}}"{{attrs}}>{{text}}</legend>',
            // Select element,
            'select' => '<select name="{{name}}" class="form-control{{attrs.class}}"{{attrs}}>{{content}}</select>',
            'selectColumn' => '<div class="col-md-{{columnSize}}"><select name="{{name}}" class="form-control{{attrs.class}}"{{attrs}}>{{content}}</select></div>',
            // Multi-select element,
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple" class="form-control{{attrs.class}}"{{attrs}}>{{content}}</select>',
            // Radio input element,
            'radio' => '<input type="radio" class="form-check-input{{attrs.class}}" name="{{name}}" value="{{value}}"{{attrs}}>',
            // Wrapping container for radio input/label,
            'radioWrapper' => '<div class="form-check">{{label}}</div>',
            'radioContainer' => '<fieldset class="form-group {{type}}{{required}}">{{content}}</fieldset>',
            'radioContainerHorizontal' => '<fieldset class="form-group {{type}}{{required}}"><div class="row">{{content}}</div></fieldset>',
            'inlineRadio' => '<input type="radio" class="form-check-input{{attrs.class}}" name="{{name}}" value="{{value}}"{{attrs}}>',
            'inlineRadioWrapper' => '<div class="form-check form-check-inline">{{label}}</div>',
            'inlineradioContainer' => '<fieldset class="form-group {{type}}{{required}}">{{content}}</fieldset>',
            'inlineradioContainerHorizontal' => '<fieldset class="form-group {{type}}{{required}}"><div class="row">{{content}}</div></fieldset>',
            // Textarea input element,
            'textarea' => '<textarea name="{{name}}" class="form-control{{attrs.class}}"{{attrs}}>{{value}}</textarea>',
            // Container for submit buttons.
            'submitContainer' => '<div class="form-group">{{content}}</div>',
            'submitContainerHorizontal' => '<div class="form-group row"><div class="{{labelColumnClass}}"></div><div class="{{inputColumnClass}}">{{content}}</div></div>',

            'inputGroup' => '{{inputGroupStart}}{{input}}{{inputGroupEnd}}',
            'inputGroupStart' => '<div class="input-group">{{prepend}}',
            'inputGroupEnd' => '{{append}}</div>',
            'inputGroupAddons' => '<div class="input-group-{{type}}">{{content}}</div>',
            'inputGroupText' => '<span class="input-group-text">{{content}}</span>',
            'helpBlock' => '<small class="help-block form-text text-muted">{{content}}</small>',
            'buttonGroup' => '<div class="btn-group{{attrs.class}}" role="group"{{attrs}}>{{content}}</div>',
            'buttonGroupVertical' => '<div class="btn-group-vertical{{attrs.class}}" role="group"{{attrs}}>{{content}}</div>',
            'buttonToolbar' => '<div class="btn-toolbar{{attrs.class}}" role="toolbar"{{attrs}}>{{content}}</div>',
            'fancyFileInput' => '{{fileInput}}<div class="input-group"><div class="input-group-btn">{{button}}</div>{{input}}</div>',
        ],
        'buttons' => [
            'type' => 'primary',
        ],
        'columns' => [
            'md' => [
                'label' => 2,
                'input' => 10,
            ],
        ],
        'useCustomFileInput' => false,
    ];

    /**
     * Default widgets for this helper.
     * Don't override parent::$_defaultWidgets for robustness
     *
     * @var array
     */
    protected $helperWidgets = [
        'fancyFile' => ['Bootstrap\View\Widget\FancyFileWidget', 'file', 'button', 'basic'],
        'label' => ['Bootstrap\View\Widget\LabelLegendWidget'],
        'inlineRadio' => ['Bootstrap\View\Widget\InlineRadioWidget', 'nestingLabel'],
        'selectColumn' => ['Bootstrap\View\Widget\ColumnSelectBoxWidget'],
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
     * @inheritDoc
     */
    public function __construct(View $View, array $config = [])
    {
        // Default config. Use Hash::merge() to keep default values
        $this->_defaultConfig = Hash::merge($this->_defaultConfig, $this->helperConfig);

        // Default widgets. Use array_merge to avoid digit key problems
        $this->_defaultWidgets = array_merge($this->_defaultWidgets, $this->helperWidgets);

        if (!isset($config['templateCallback'])) {
            $that = $this;
            $config['templateCallback'] = function ($name, $data) use ($that) {
                $data['templateName'] = $name;
                if ($that->horizontal) {
                    $data['templateName'] .= 'Horizontal';
                } elseif ($that->inline) {
                    $data['templateName'] .= 'Inline';
                }
                $data += [
                    'inputColumnClass' => $this->getColumnClass('input'),
                    'labelColumnClass' => $this->getColumnClass('label'),
                ];
                if (!$that->getTemplates($data['templateName'])) {
                    $data['templateName'] = $name;
                }

                return $data;
            };
        }
        parent::__construct($View, $config);
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
     * @return string An formatted opening FORM tag.
     */
    public function create($model = null, array $options = []): string
    {
        $options += [
            'horizontal' => false,
            'inline' => false,
        ];
        $this->horizontal = $options['horizontal'];
        $this->inline = $options['inline'];
        unset($options['horizontal'], $options['inline']);
        if ($this->horizontal) {
            $options = $this->addClass($options, 'form-horizontal');
        } elseif ($this->inline) {
            $options = $this->addClass($options, 'form-inline');
        }
        $options['role'] = 'form';

        return parent::create($model, $options);
    }

    /**
     * Get the column sizes configuration associated with the
     * form helper.
     *
     * @return array
     */
    public function getColumnSizes(): array
    {
        return $this->getConfig('columns');
    }

    /**
     * Set the column sizes configuration associated with the
     * form helper.
     *
     * @param array $columns Array of columns options to set
     * @return $this
     */
    public function setColumnSizes(array $columns)
    {
        return $this->setConfig('columns', $columns, false);
    }

    /**
     * Retrieve classes for the size of the specified column (label, input or error),
     * optionally adding the offset prefix to the classes.
     *
     * @param string $what The type of the column (`'label'`, `'input'`, `'error'`).
     * @return string The classes for the size or offset of the specified column.
     */
    public function getColumnClass(string $what): string
    {
        $columns = $this->getConfig('columns');
        $classes = [];
        foreach ($columns as $cl => $arr) {
            if (!isset($arr[$what])) {
                continue;
            }
            $value = $arr[$what];
            $classes[] = 'col-' . $cl . '-' . $value;
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
     * @param string|array|null $addonOrButtons Content to be wrapped or array of buttons to be
     * wrapped.
     * @param string $type Input group type
     * @return string|null The elements wrapped in a suitable HTML element.
     */
    protected function wrapInputGroup($addonOrButtons, string $type): ?string
    {
        if ($addonOrButtons) {
            if (is_array($addonOrButtons)) {
                $addonOrButtons = implode('', $addonOrButtons);
            } else {
                $addonOrButtons = $this->_makeIcon($addonOrButtons);
            }

            $isButton = strpos($addonOrButtons, '<button') === 0;
            $isDropdown = strpos($addonOrButtons, 'data-toggle="dropdown"');
            if (!$isButton && !$isDropdown) {
                $addonOrButtons = $this->formatTemplate('inputGroupText', [
                    'content' => $addonOrButtons,
                ]);
            }

            $addonOrButtons = $this->formatTemplate('inputGroupAddons', [
                'type' => strtolower($type),
                'content' => $addonOrButtons,
            ]);
        }

        return $addonOrButtons;
    }

    /**
     * Concatenates and wraps `$input`, `$prepend` and `$append` inside an input group.
     *
     * @param string $input The input content.
     * @param string|null $prepend The content to prepend to `$input`.
     * @param string|null $append The content to append to `$input`.
     * @return string A string containing the three elements concatenated an wrapped inside
     *                  an input group `<div>`.
     */
    protected function formatWrap(string $input, ?string $prepend = null, ?string $append = null): string
    {
        return $this->formatTemplate('inputGroup', [
            'inputGroupStart' => $this->formatTemplate('inputGroupStart', [
                'prepend' => $prepend,
            ]),
            'input' => $input,
            'inputGroupEnd' => $this->formatTemplate('inputGroupEnd', [
                'append' => $append,
            ]),
        ]);
    }

    /**
     * Prepend the given content to the given input or create an opening input group.
     *
     * @param string|null $input Input to which `$prepend` will be prepend, or
     * null to create an opening input group.
     * @param string|array|null $prepend The content to prepend.,
     * @return string The input with the content of `$prepend` prepended or an
     * opening `<div>` for an input group.
     */
    public function prepend(?string $input = null, $prepend = null): string
    {
        $prepend = $this->wrapInputGroup($prepend, 'prepend');
        if ($input === null) {
            return $this->formatTemplate('inputGroupStart', ['prepend' => $prepend]);
        }

        return $this->formatWrap($input, $prepend, null);
    }

    /**
     * Append the given content to the given input or close an input group.
     *
     * @param string|null $input Input to which `$append` will be append, or
     * null to create a closing element for an input group.
     * @param string|array|null $append The content to append.,
     * @return string The input with the content of `$append` appended or a
     * closing `</div>` for an input group.
     */
    public function append(?string $input = null, $append = null): string
    {
        $append = $this->wrapInputGroup($append, 'append');
        if ($input === null) {
            return $this->formatTemplate('inputGroupEnd', ['append' => $append]);
        }

        return $this->formatWrap($input, null, $append);
    }

    /**
     * Wrap the given `$input` between `$prepend` and `$append`.
     *
     * @param string $input The input to be wrapped (see `prepend()` and `append()`).
     * @param string|array $prepend The content to prepend (see `prepend()`).
     * @param string|array $append The content to append (see `append()`).
     * @return string A string containing the given `$input` wrapped between `$prepend` and
     * `$append` according to the behavior of `prepend()` and `append()`.
     */
    public function wrap(string $input, $prepend, $append): string
    {
        return $this->prepend(null, $prepend) . $input . $this->append(null, $append);
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
     * @return string Completed form widget.
     */
    public function control(string $fieldName, array $options = []): string
    {
        $options += [
            'type' => null,
            'label' => null,
            'error' => null,
            'required' => null,
            'options' => null,
            'templates' => [],
            'templateVars' => [],
            'labelOptions' => true,
        ];

        $options += [
            'prepend' => null,
            'append' => null,
            'help' => null,
            'inline' => false,
        ];

        $options = $this->_parseOptions($fieldName, $options);

        $prepend = $options['prepend'];
        $append = $options['append'];
        $help = $options['help'];
        $inline = $options['inline'];
        unset(
            $options['prepend'],
            $options['append'],
            $options['help'],
            $options['inline']
        );

        if ($prepend || $append) {
            $prepend = $this->prepend(null, $prepend);
            $append = $this->append(null, $append);
        }

        if ($help) {
            $append .= $this->formatTemplate('helpBlock', ['content' => $help]);
        }

        if ($options['type'] === 'radio' && $inline) {
            $options['type'] = 'inlineradio';
        }

        $options['templateVars'] += [
            'prepend' => $prepend,
            'append' => $append,
        ];

        return parent::control($fieldName, $options);
    }

    /**
     * @inheritDoc
     */
    protected function _getInput(string $fieldName, array $options)
    {
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
     * @inheritDoc
     */
    protected function _inputLabel(string $fieldName, $label = null, array $options = []): string
    {
        $groupTypes = ['radio', 'inlineradio', 'multicheckbox', 'date', 'time', 'datetime'];
        if (in_array($options['type'], $groupTypes, true)) {
            $options['id'] = false;
        }

        return parent::_inputLabel($fieldName, $label, $options);
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
     * @return string Completed radio widget set.
     */
    public function inlineRadio(string $fieldName, $options = [], array $attributes = []): string
    {
        $attributes['options'] = $options;
        $attributes['idPrefix'] = $this->_idPrefix;
        $attributes = $this->_initInputField($fieldName, $attributes);
        $hiddenField = $attributes['hiddenField'] ?? true;
        unset($attributes['hiddenField']);
        $radio = $this->widget('inlineRadio', $attributes);
        $hidden = '';
        if ($hiddenField) {
            $hidden = $this->hidden($fieldName, [
                'value' => '',
                'form' => $attributes['form'] ?? null,
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
     * @return string A generated file input.
     */
    public function file(string $fieldName, array $options = []): string
    {
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
     * - `block` Twitter bootstrap button block (true or false, default false).
     * - `btype` Twitter bootstrap button type (primary, danger, info, etc.).
     * - `size` Twitter bootstrap button size (mini, small, large).
     * - `escape` HTML entity encode the $title of the button. Defaults to `false`.
     *
     * @param string $title The button's caption. Not automatically HTML encoded.
     * @param array $options Button options
     * @return string A HTML button tag.
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-button-elements
     */
    public function button(string $title, array $options = []): string
    {
        [$options, $easyIcon] = $this->_easyIconOption($options);

        return $this->_injectIcon(parent::button($title, $this->_addButtonClasses($options)), $easyIcon);
    }

    /**
     * Alternative to the `button` method allowing the type of the button to be set as an
     * argument.
     *
     * @param string $title The button's caption. Not automatically HTML encoded.
     * @param string|array $type If array, behaves like options, otherwize will be used as
     *                           the `btype` option.
     * @param array $options Array of options and HTML attributes.
     * @return string A HTML button tag.
     */
    public function cbutton(string $title, $type = [], array $options = []): string
    {
        if (is_array($type)) {
            $options = $type;
            $type = null;
        }
        if ($type !== null) {
            $options += [
                'btype' => $type,
            ];
        }

        return $this->button($title, $options);
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
     * @return string A HTML string containing the button group.
     */
    public function buttonGroup(array $buttons, array $options = []): string
    {
        $options += [
            'vertical' => false,
            'templateVars' => [],
        ];
        $template = 'buttonGroup';
        if ($options['vertical']) {
            $template = 'buttonGroupVertical';
        }

        return $this->formatTemplate($template, [
            'content' => implode('', $buttons),
            'attrs' => $this->templater()->formatAttributes($options, ['vertical']),
            'templateVars' => $options['templateVars'],
        ]);
    }

    /**
     * Creates a button toolbar using the given button groups.
     *
     * @param array $buttonGroups Array of groups for the toolbar
     * @param array $options Array of options for the `Html::div` method.
     * @return string A HTML string containing the button toolbar.
     */
    public function buttonToolbar(array $buttonGroups, array $options = []): string
    {
        $options += [
            'templateVars' => [],
        ];

        return $this->formatTemplate('buttonToolbar', [
            'content' => implode('', $buttonGroups),
            'attrs' => $this->templater()->formatAttributes($options, ['vertical']),
            'templateVars' => $options['templateVars'],
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
     * @return string A HTML string containing the button dropdown.
     */
    public function dropdownButton(string $title, array $menu = [], array $options = []): string
    {
        // List of options to send to the dropdown() method
        $optsForHtml = ['align'];
        $ulOptions = [];
        foreach ($optsForHtml as $opt) {
            if (isset($options[$opt])) {
                $ulOptions[$opt] = $options[$opt];
                unset($options[$opt]);
            }
        }
        $options += [
            'type' => false,
            'dropup' => false,
            'data-toggle' => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false',
        ];
        $dropup = $options['dropup'];
        unset($options['dropup']);

        $bGroupOptions = [];
        if ($dropup) {
            $bGroupOptions = $this->addClass($bGroupOptions, 'dropup');
        }

        $options = $this->addClass($options, 'dropdown-toggle');

        return $this->buttonGroup([
            $this->button($title, $options),
            $this->Html->dropdown($menu, $ulOptions),
        ], $bGroupOptions);
    }

    /**
     * Creates a submit button element. This method will generate `<input />` elements that
     * can be used to submit, and reset forms by using $options. image submits can be
     * created by supplying an image path for $caption.
     *
     * ### Options
     *
     * - `block` Twitter bootstrap button block (true or false, default false).
     * - `btype` Twitter bootstrap button type (primary, danger, info, etc.).
     * - `size` Twitter bootstrap button size (mini, small, large).
     * - `templateVars` Additional template variables for the input element and its container.
     * - `type` Set to 'reset' for reset inputs. Defaults to 'submit'
     * - Other attributes will be assigned to the input element.
     *
     * @param string|null $caption The label appearing on the button OR if string
     *                             contains :// or the  extension .jpg, .jpe, .jpeg, .gif, .png
     *                             use an image if the extension exists, AND the first character
     *                             is /, image is relative to webroot, OR if the first character
     *                             is not /, image is relative to webroot/img.
     * @param array $options Array of options. See above.
     * @return string A HTML submit button
     * @link http://book.cakephp.org/3.0/en/views/helpers/form.html#creating-buttons-and-submit-elements
     */
    public function submit(?string $caption = null, array $options = []): string
    {
        return parent::submit($caption, $this->_addButtonClasses($options));
    }

    /**
     * See FormHelper::submit.
     *
     * @param string $caption See `FormHelper::submit` documentation.
     * @param string|array $type If array, behaves like options, otherwize will be used as
     *                           the `btype` option.
     * @param array $options Array of options and HTML attributes, see `FormHelper::submit`.
     * @return string A HTML submit button.
     */
    public function csubmit(?string $caption = null, $type = [], array $options = []): string
    {
        if (is_array($type)) {
            $options = $type;
            $type = null;
        }
        if ($type !== null) {
            $options += [
                'btype' => $type,
            ];
        }

        return $this->submit($caption, $options);
    }
}
