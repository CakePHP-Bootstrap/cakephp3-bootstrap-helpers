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
namespace Bootstrap\View;

use Cake\View\StringTemplate;
use Cake\Utility\Hash;
use RuntimeException;

class EnhancedStringTemplate extends StringTemplate {

    /**
     * General callback function.
     *
     * @var callable
     */
    protected $_callback = null;

    /**
     * Array of callback function for specific templates.
     *
     * @var array
     */
    protected $_callbacks = null;

    /**
     * Constructor.
     *
     * @param array $config A set of templates to add.
     * @param callable $callback A general callback that will be called before
     * retrieving any templates.
     * @param arra $callbacks An array of callbacks.
     */
    public function __construct(array $config = [], callable $callback = null, array $callbacks = [])
    {
        $this->add($config);
        $this->_callback = $callback;
        $this->_callbacks = $callbacks;
    }

    /**
     * Compile templates into a more efficient printf() compatible format.
     *
     * @param array $templates The template names to compile. If empty all templates will
     * be compiled.
     *
     * @return void
     */
    protected function _compileTemplates(array $templates = []) {
        if (empty($templates)) {
            $templates = array_keys($this->_config);
        }
        foreach ($templates as $name) {
            $template = $this->get($name);
            if ($template === null) {
                $this->_compiled[$name] = [null, null];
            }
            $template = str_replace('%', '%%', $template);
            preg_match_all('#\{\{([\w.]+)\}\}#', $template, $matches);
            $this->_compiled[$name] = [
                str_replace($matches[0], '%s', $template),
                $matches[1]
            ];
        }
    }

    /**
     * Format a template string with $data
     *
     * @param string $name The template name.
     * @param array  $data The data to insert.
     *
     * @return string
    */
    public function format($name, array $data) {
        $name = $this->_getTemplateName($name, $data);
        if (!isset($this->_compiled[$name])) {
            throw new RuntimeException("Cannot find template named '$name'.");
        }
        list($template, $placeholders) = $this->_compiled[$name];
        // If there is a {{attrs.xxxx}} block in $template, remove the xxxx attribute
        // from $data['attrs'] and add its content to $data['attrs.class'].
        if (isset($data['attrs'])) {
            foreach ($placeholders as $placeholder) {
                if (substr($placeholder, 0, 6) == 'attrs.'
                    && preg_match('#'.substr($placeholder, 6).'="([^"]*)"#',
                                  $data['attrs'], $matches) > 0) {
                    $data['attrs'] = preg_replace('#'.substr($placeholder, 6).'="[^"]*"#',
                                                  '', $data['attrs']);
                    $data[$placeholder] = trim($matches[1]);
                    if ($data[$placeholder]) {
                        $data[$placeholder] = ' '.$data[$placeholder];
                    }
                }
            }
            $data['attrs'] = trim($data['attrs']);
            if ($data['attrs']) {
                $data['attrs'] = ' '.$data['attrs'];
            }
        }
        return parent::format($name, $data);
    }

    /**
     * Returns a space-delimited string with items of the $options array. If a key
     * of $options array happens to be one of those listed
     * in `StringTemplate::$_compactAttributes` and its value is one of:
     *
     * - '1' (string)
     * - 1 (integer)
     * - true (boolean)
     * - 'true' (string)
     *
     * Then the value will be reset to be identical with key's name.
     * If the value is not one of these 4, the parameter is not output.
     *
     * 'escape' is a special option in that it controls the conversion of
     * attributes to their HTML-entity encoded equivalents. Set to false to disable HTML-encoding.
     *
     * If value for any option key is set to `null` or `false`, that option will be excluded from output.
     *
     * This method uses the 'attribute' and 'compactAttribute' templates. Each of
     * these templates uses the `name` and `value` variables. You can modify these
     * templates to change how attributes are formatted.
     *
     * @param array|null $options Array of options.
     * @param array|null $exclude Array of options to be excluded, the options here will not be part of the return.
     * @return string Composed attributes.
     */
    public function formatAttributes($options, $exclude = null) {
        if (!is_array($exclude)) {
            $exclude = [];
        }
        $exclude += ['callbackVars'];
        return parent::formatAttributes($options, $exclude);
    }

    /**
     * Retrieve a template name after checking the various callbacks.
     *
     * @param string $name The original name of the template.
     * @param array $data The data to update.
     *
     * @return string The new name of the template.
     */
    protected function _getTemplateName(string $name, array &$data = []) {
        if (isset($this->_callbacks[$name])) {
            $data = call_user_func($this->_callbacks[$name], $data);
        }
        if ($this->_callback) {
            $data = call_user_func($this->_callback, $name, $data);
        }
        if (isset($data['templateName'])) {
            $name = $data['templateName'];
            unset($data['templateName']);
        }
        return $name;
    }


};
