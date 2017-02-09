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

class BootstrapStringTemplate extends StringTemplate {

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
        if (!isset($this->_compiled[$name])) {
            return '';
        }
        list($template, $placeholders) = $this->_compiled[$name];
        // If there is a {{attrs.xxxx}} block in $template, remove the xxxx attribute
        // from $data['attrs'] and add its content to $data['attrs.class'].
        if (isset($data['attrs'])) {
            foreach ($placeholders as $placeholder) {
                if (substr($placeholder, 0, 6) == 'attrs.'
                    && in_array('attrs.'.substr($placeholder, 6), $placeholders)
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
        if ($template === null) {
            return '';
        }
        if (isset($data['templateVars'])) {
            $data += $data['templateVars'];
            unset($data['templateVars']);
        }
        $replace = [];
        foreach ($placeholders as $placeholder) {
            $replacement = isset($data[$placeholder]) ? $data[$placeholder] : null;
            if (is_array($replacement)) {
                $replacement = implode('', $replacement);
            }
            $replace[] = $replacement;
        }
        return vsprintf($template, $replace);
    }

};