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
namespace Bootstrap\View\Widget;

use Cake\View\Widget\WidgetInterface;

/**
 * Form 'widget' for creating fancy file widgets made of a button
 * and a text input.
 *
 * Generally this element is used by other widgets,
 * and FormHelper itself.
 */
class FancyFileWidget implements WidgetInterface {

    /**
     * Templates
     *
     * @var \Cake\View\StringTemplate
     */
    protected $_templates;

    /**
     * FileWidget instance.
     *
     * @var Cake\View\Widget\FileWidget
     */
    protected $_file;

    /**
     * ButtonWidget instance.
     *
     * @var Cake\View\Widget\ButtonWidget
     */
    protected $_button;

    /**
     * Text widget instance.
     *
     * @var Cake\View\Widget\BasicWidget
     */
    protected $_input;


    /**
     * Constructor
     *
     * @param \Cake\View\StringTemplate $templates Templates list.
     * @param \Cake\View\Widget\FileWidget $file A file widget.
     * @param \Cake\View\Widget\ButtonWidget $button A button widget.
     * @param \Cake\View\Widget\BasicWidget $input A text input widget.
     */
    public function __construct($templates, $file, $button, $input) {
        $this->_templates = $templates;
        $this->_file = $file;
        $this->_button = $button;
        $this->_input = $input;
    }


    /**
     * Render a custom file upload form widget.
     *
     * Data supports the following keys:
     *
     * - `_input` - Options for the input element.
     * - `_button` - Options for the button element.
     * - `name` - Set the input name.
     * - `count-label` - Label for multiple files. Default is `__('files selected')`.
     * - `button-label` - Button text. Default is `__('Choose File')`.
     * - `escape` - Set to false to disable HTML escaping.
     *
     * All other keys will be converted into HTML attributes.
     * Unlike other input objects the `val` property will be specifically
     * ignored.
     *
     * @param array $data The data to build a file input with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string HTML elements.
     */
    public function render(array $data, \Cake\View\Form\ContextInterface $context): string {

        $data += [
            '_input'  => [],
            '_button' => [],
            'id' => $data['name'],
            'count-label' => __('files selected'),
            'button-label' => (isset($data['multiple']) && $data['multiple']) ? __('Choose Files') : __('Choose File'),
            'templateVars' => []
        ];

        $fakeInputCustomOptions = $data['_input'];
        $fakeButtonCustomOptions = $data['_button'];
        $countLabel = $data['count-label'];
        $buttonLabel = $data['button-label'];
        unset($data['_input'], $data['_button'],
            $data['type'], $data['count-label'],
            $data['button-label']);
        // avoid javascript errors due to invisible control
        unset($data['required']);

        $fileInput = $this->_file->render($data + [
            'style' => 'display: none;',
            'onchange' => "document.getElementById('".$data['id']."-input').value = " .
                "(this.files.length <= 1) ? " .
                "(this.files.length ? this.files[0].name : '') " .
                ": this.files.length + ' ' + '" . $countLabel . "';",
            'escape' => false
        ], $context);

        if (!empty($data['val']) && is_array($data['val'])) {
            if (isset($data['val']['name']) || count($data['val']) == 1) {
                $fakeInputCustomOptions += [
                    'value' => (isset($data['val']['name'])) ? $data['val']['name'] : $data['val'][0]['name']
                ];
            }
            else {
                $fakeInputCustomOptions += [
                    'value' => count($data['val']) . ' ' . $countLabel
                ];
            }
        }

        $fakeInput = $this->_input->render($fakeInputCustomOptions + [
            'name' => $this->_fakeFieldName($data['name']),
            'readonly' => 'readonly',
            'id' => $data['id'].'-input',
            'onclick' => "document.getElementById('".$data['id']."').click();",
            'escape' => false
        ], $context);

        $fakeButton = $this->_button->render($fakeButtonCustomOptions + [
            'type' => 'button',
            'text' => $buttonLabel,
            'onclick' => "document.getElementById('".$data['id']."').click();"
        ], $context);

        return $this->_templates->format('fancyFileInput', [
            'fileInput' => $fileInput,
            'button' => $fakeButton,
            'input' => $fakeInput,
            'attrs' => $this->_templates->formatAttributes($data),
            'templateVars' => $data['templateVars']
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function secureFields(array $data): array {
        // the extra input for display
        $fields = [$this->_fakeFieldName($data['name'])];
        // the file array
        foreach (['name', 'type', 'tmp_name', 'error', 'size'] as $suffix) {
            $fields[] = $data['name'] . '[' . $suffix . ']';
        }
        return $fields;
    }

    /**
     * Determine name of fake input field
     * @param string $fieldName original field name
     * @return string fake field name
     */
    protected static function _fakeFieldName($fieldName) {
        return preg_replace('/(\]?)$/', '-text$1', $fieldName, 1);
    }
};
