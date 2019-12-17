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
     * Format a template string with $data
     *
     * @param string $name The template name.
     * @param array  $data The data to insert.
     *
     * @return string
    */
    public function format($name, array $data): string {
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

};
