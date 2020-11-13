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

/**
 * Input widget class for generating a date time input widget.
 *
 * This class is intended as an internal implementation detail
 * of Cake\View\Helper\FormHelper and is not intended for direct use.
 */
class DateTimeWidget extends \Cake\View\Widget\DateTimeWidget {

    /**
     * Renders a date time widget
     *
     * - `name` - Set the input name.
     * - `disabled` - Either true or an array of options to disable.
     * - `val` - A date time string, integer or DateTime object
     * - `empty` - Set to true to add an empty option at the top of the
     *   option elements. Set to a string to define the display value of the
     *   empty option.
     *
     * In addition to the above options, the following options allow you to control
     * which input elements are generated. By setting any option to false you can disable
     * that input picker. In addition each picker allows you to set additional options
     * that are set as HTML properties on the picker.
     *
     * - `year` - Array of options for the year select box.
     * - `month` - Array of options for the month select box.
     * - `day` - Array of options for the day select box.
     * - `hour` - Array of options for the hour select box.
     * - `minute` - Array of options for the minute select box.
     * - `second` - Set to true to enable the seconds input. Defaults to false.
     * - `meridian` - Set to true to enable the meridian input. Defaults to false.
     *   The meridian will be enabled automatically if you choose a 12 hour format.
     *
     * The `year` option accepts the `start` and `end` options. These let you control
     * the year range that is generated. It defaults to +-5 years from today.
     *
     * The `month` option accepts the `name` option which allows you to get month
     * names instead of month numbers.
     *
     * The `hour` option allows you to set the following options:
     *
     * - `format` option which accepts 12 or 24, allowing
     *   you to indicate which hour format you want.
     * - `start` The hour to start the options at.
     * - `end` The hour to stop the options at.
     *
     * The start and end options are dependent on the format used. If the
     * value is out of the start/end range it will not be included.
     *
     * The `minute` option allows you to define the following options:
     *
     * - `interval` The interval to round options to.
     * - `round` Accepts `up` or `down`. Defines which direction the current value
     *   should be rounded to match the select options.
     *
     * @param array $data Data to render with.
     * @param \Cake\View\Form\ContextInterface $context The current form context.
     * @return string A generated select box.
     * @throws \RuntimeException When option data is invalid.
     */
    public function render(array $data, \Cake\View\Form\ContextInterface $context): string {
        $data = $this->_normalizeData($data);
        $count = 0;
        foreach ($this->_selects as $select) {
            if ($data[$select] !== false && $data[$select] !== null) {
                ++$count;
            }
        }
        $data['templateVars'] += [
            'columnSize' => round(12 / $count)
        ];
        return parent::render($data, $context);
    }

};
