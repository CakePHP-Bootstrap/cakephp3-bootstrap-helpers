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
namespace Bootstrap\Utility;

/**
 * A class that providing stacking states.
 */
class StackedStates
{
    /**
     * Default values for a new state.
     *
     * @var mixed
     */
    protected $_defaults = [];

    /**
     * Stack of states.
     *
     * @var array
     */
    protected $_states = [];

    /**
     * Construct a new StackState with the given default values.
     *
     * @param mixed $defaults Default values for the states.
     *
     */
    public function __construct($defaults = [])
    {
        $this->_defaults = $defaults;
    }

    /**
     * Check if the stack is empty.
     *
     * @return bool true if the stack is empty (i.e. contains no states).
     */
    public function isEmpty(): bool
    {
        return empty($this->_states);
    }

    /**
     * Pop the current state.
     *
     * @return mixed An array [type, state] containing the removed state.
     */
    public function pop()
    {
        return array_pop($this->_states);
    }

    /**
     * Push a new state, merging given values with the default
     * ones.
     *
     * @param   string $type Type of the new state.
     * @param   mixed $state New state.
     * @return  void
     */
    public function push($type, $state = []): void
    {
        if (isset($this->_defaults[$type])) {
            $state = array_merge($this->_defaults[$type], $state);
        }
        array_push($this->_states, [$type, $state]);
    }

    /**
     * Retrieve the type of the current sate.
     *
     * @return string Type of the current state.
     */
    public function type(): string
    {
        return end($this->_states)[0];
    }

    /**
     * Return the current state.
     *
     * @return mixed Current values of the state.
     */
    public function current()
    {
        return end($this->_states)[1];
    }

    /**
     * Set a value of the current state.
     *
     * @param   mixed $name Name of the attribute to set.
     * @param   mixed $value New value for the attribute.
     * @return  void
     */
    public function setValue($name, $value)
    {
        $this->_states[count($this->_states) - 1][1][$name] = $value;
    }

    /**
     * Get a value from the current state.
     *
     * @param mixed $name Name of the attribute to retrieve.
     *
     * @return mixed Value retrieved from the current state.
     */
    public function getValue($name)
    {
        return end($this->_states)[1][$name];
    }

    /**
     * Check if the current state is of the given type. If there is no
     * current state, this function returns false.
     *
     * @param string $type Type to check
     * @return bool true if the current state is of the given type,
     *      false if the types do not match or if there is no current state.
     */
    public function is($type): bool
    {
        if (empty($this->_states)) {
            return false;
        }

        return $this->type() == $type;
    }
}
