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

use Cake\View\Widget\RadioWidget;

/**
 * Input widget class for generating a set of inline radio buttons.
 *
 * This class is intended as an internal implementation detail
 * of Cake\View\Helper\BootstrapFormHelper and is not intended for direct use.
 */
class InlineRadioWidget extends RadioWidget {

   /**
     * Renders a single radio input and label.
     *
     * @param string|int $val The value of the radio input.
     * @param string|array $text The label text, or complex radio type.
     * @param array $data Additional options for input generation.
     * @param \Cake\View\Form\ContextInterface $context The form context
     * @return string
     */
    protected function _renderInput($val, $text, $data, $context) {
        $escape = $data['escape'];
        if (is_int($val) && isset($text['text'], $text['value'])) {
            $radio = $text;
        } else {
            $radio = ['value' => $val, 'text' => $text];
        }
        $radio['name'] = $data['name'];
        if (!isset($radio['templateVars'])) {
            $radio['templateVars'] = [];
        }
        if (!empty($data['templateVars'])) {
            $radio['templateVars'] = array_merge($data['templateVars'], $radio['templateVars']);
        }
        if (empty($radio['id'])) {
            $radio['id'] = $this->_id($radio['name'], $radio['value']);
        }
        if (isset($data['val']) && is_bool($data['val'])) {
            $data['val'] = $data['val'] ? 1 : 0;
        }
        if (isset($data['val']) && (string)$data['val'] === (string)$radio['value']) {
            $radio['checked'] = true;
        }
        if (!is_bool($data['label']) && isset($radio['checked']) && $radio['checked']) {
            $data['label'] = $this->_templates->addClass($data['label'], 'selected');
        }
        $radio['disabled'] = $this->_isDisabled($radio, $data['disabled']);
        if (!empty($data['required'])) {
            $radio['required'] = true;
        }
        if (!empty($data['form'])) {
            $radio['form'] = $data['form'];
        }
        $input = $this->_templates->format('inlineRadio', [
            'name' => $radio['name'],
            'value' => $escape ? h($radio['value']) : $radio['value'],
            'templateVars' => $radio['templateVars'],
            'attrs' => $this->_templates->formatAttributes($radio + $data, ['name', 'value', 'text', 'options', 'label', 'val', 'type']),
        ]);
        $label = $this->_renderLabel(
            $radio,
            $data['label'],
            $input,
            $context,
            $escape
        );
        if ($label === false &&
            strpos($this->_templates->get('inlineRadioWrapper'), '{{input}}') === false
        ) {
            $label = $input;
        }
        return $this->_templates->format('inlineRadioWrapper', [
            'input' => $input,
            'label' => $label,
            'templateVars' => $data['templateVars'],
        ]);
    }

};