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


/**
 * A trait that provides easy icon processing.
 */
trait EasyIconTrait {

    /**
     * Set to false to disable easy icon processing.
     *
     * @var bool
     */
    public $easyIcon = true;

    /**
     * Try to convert the specified string to a bootstrap icon. The string is converted if
     * it matches a format `i:icon-name` (leading and trailing spaces or ignored) and if
     * easy-icon is activated.
     *
     * **Note:** This function will currently fail if the Html helper associated with the
     * view is not BootstrapHtmlHelper.
     *
     * @param string $text      The string to convert.
     * @param bool   $converted If specified, will contains `true` if the text was converted,
     * `false` otherwize.
     *
     * @return string The text after conversion.
     */
    protected function _makeIcon($text, &$converted = false) {
        $converted = false;
        if (!$this->easyIcon) {
            return $text;
        }
        $text = preg_replace_callback(
            '#(^|\s+)i:([a-zA-Z0-9\\-_]+)(\s+|$)#', function ($matches) {
                return $matches[1].$this->Html->icon($matches[2]).$matches[3];
            }, $text, -1, $count);
        $converted = (bool)$count;
        return $text;
    }

    /**
     * This method calls the given callback with the specified argument (`$title` and
     * `$options`) after applying a filter on them.
     *
     * **Note:** Currently this method only works for function that take
     * two arguments ($title and $options).
     *
     * @param callable $callback The callback.
     * @param string   $title    The first argument for the callback.
     * @param array    $options  The second argument for the calback.
     *
     * @return mixed Whatever might be returned by $callback.
     */
    protected function _easyIcon($callback, $title, $options) {
        $title = $this->_makeIcon($title, $converted);
        if ($converted) {
            $options += [
                'escape' => false
            ];
        }
        return call_user_func($callback, $title, $options);
    }

}

?>