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
namespace Bootstrap\View;

class FlexibleStringTemplate extends EnhancedStringTemplate
{
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
     * @param \Bootstrap\View\arra $callbacks An array of callbacks.
     */
    public function __construct(array $config = [], ?callable $callback = null, array $callbacks = [])
    {
        parent::__construct($config);
        $this->_callback = $callback;
        $this->_callbacks = $callbacks;
    }

    /**
     * Format a template string with $data
     *
     * @param string $name The template name.
     * @param array  $data The data to insert.
     *
     * @return string
     */
    public function format($name, array $data): string
    {
        $name = $this->_getTemplateName($name, $data);

        return parent::format($name, $data);
    }

    /**
     * Retrieve a template name after checking the various callbacks.
     *
     * @param string $name The original name of the template.
     * @param array $data The data to update.
     *
     * @return string The new name of the template.
     */
    protected function _getTemplateName($name, array &$data = [])
    {
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
}
