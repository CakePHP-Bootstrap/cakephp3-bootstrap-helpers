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
     * @param string $text The string to convert.
     * @param bool $converted If specified, will contains `true` if the text was converted,
     * `false` otherwize.
     *
     * @return string The text after conversion.
     */
    protected function _makeIcon($text, &$converted = false, $options = []) {
        $converted = false;

        // If easyIcon mode is disable.
        if (!$this->easyIcon) {
            return $text;
        }

        // If text is not a string!
        if (!is_string($text)) {
            return $text;
        }

        // Use $this->icon if available, otherwize fall back to $this->Html->icon.
        if (method_exists($this, 'icon')) {
            $ficon = [$this, 'icon'];
        }
        else {
            $ficon = [$this->Html, 'icon'];
        }

        // Replace occurences.
        $text = preg_replace_callback(
            '#(^|\s+)i:([a-zA-Z0-9\\-_]+)(\s+|$)#', function ($matches) use ($ficon, $options) {
                return $matches[1].call_user_func($ficon, $matches[2], $options).$matches[3];
            }, $text, -1, $count);
        $converted = (bool)$count;
        return $text;
    }

    /**
     * This method calls the given callback with the given list of parameters after
     * applying an easy-icon filter on them.
     *
     * Note: For compatibility issue, this function can still be called with a callback,
     * a string (title) and an array of options.
     *
     * @param callable $callback The callback.
     * @param int $indexTitle Index of the title to which the easy-icon processing
     * will be applied.
     * @param int $indexOptions Index of the options in the $args array.
     * @param array $args Arguments for the callback.
     *
     * @return mixed Whatever might be returned by $callback.
     */
    protected function _easyIcon(callable $callback, $indexTitle, $indexOptions, $args = null) {
        if ($args === null) {
            $args = [$indexTitle, $indexOptions];
            $indexTitle = 0;
            $indexOptions = 1;
        }
        $title = &$args[$indexTitle];
        $options = &$args[$indexOptions];
        $title = $this->_makeIcon($title, $converted);
        if ($converted) {
            $options += [
                'escape' => false
            ];
        }
        return call_user_func_array($callback, $args);
    }

}

?>
