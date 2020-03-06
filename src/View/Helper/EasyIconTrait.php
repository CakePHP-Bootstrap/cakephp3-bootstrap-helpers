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

/**
 * A trait that provides easy icon processing.
 */
trait EasyIconTrait
{
    /**
     * Set to false to disable easy icon processing.
     *
     * @var bool
     */
    public $easyIcon = true;

    /**
     * Remove the `easyIcon` option from the given array and return it together with
     * the array.
     *
     * @param array $options Array of options from which the easy-icon option should
     * be extracted.
     * @return array An array containing the options and the easy-icon option.
     */
    protected function _easyIconOption(array $options): array
    {
        $options += [
            'easyIcon' => $this->easyIcon,
        ];
        $easyIcon = $options['easyIcon'];
        unset($options['easyIcon']);

        return [$options, $easyIcon];
    }

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
     * @return string The text after conversion.
     */
    protected function _makeIcon(string $text, &$converted = false): string
    {
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
        } else {
            $ficon = [$this->Html, 'icon'];
        }

        // Replace occurences.
        $text = preg_replace_callback(
            '#(^|[>\s]\s*)i:([a-zA-Z0-9\\-_]+)(\s*[\s<]|$)#',
            function ($matches) use ($ficon) {
                return $matches[1] . call_user_func($ficon, $matches[2]) . $matches[3];
            },
            $text,
            -1,
            $count
        );
        $converted = (bool)$count;

        return $text;
    }

    /**
     * Inject icon into the given string.
     *
     * @param   string $title Input string where icon should be injected
     *                        following the easy-icon process.
     * @param   bool $easyIcon Boolean indicating if the easy-icon process
     *                         should be applied.
     * @return  string
     */
    protected function _injectIcon(string $title, bool $easyIcon): string
    {
        if (!$easyIcon) {
            return $title;
        }

        return $this->_makeIcon($title);
    }
}
