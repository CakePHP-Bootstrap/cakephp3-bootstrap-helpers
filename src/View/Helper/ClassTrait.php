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
 * A trait that provides useful methods to generate bootstrap html specific
 * code.
 */
trait ClassTrait {

    /**
     * Adds the given class to the element options.
     *
     * @param array        $options Array of options/attributes to add a class to.
     * @param string|array $class   The class names to be added.
     * @param string       $key     The key to use for class (default to `'class'`).
     *
     * @return array Array of options with `$key` set or updated.
     */
    public function addClass(array $options = [], $class = null, $key = 'class') {
        if (!is_array($class)) {
            $class = explode(' ', trim($class));
        }
        $optClass = [];
        if (isset($options[$key])) {
            $optClass = $options[$key];
            if (!is_array($optClass)) {
                $optClass = explode(' ', trim($optClass));
            }
        }
        $class = array_merge($optClass, $class);
        $class = array_map('trim', $class);
        $class = array_unique($class);
        $class = array_filter($class);
        $options[$key] = implode(' ', $class);
        return $options;
    }

    /**
     * Add classes to options according to the default values of bootstrap-type
     * and bootstrap-size for button (see configuration).
     *
     * @param array $options The initial options with bootstrap-type and/or
     * bootstrat-size values.
     *
     * @return array The new options with class values (btn, and btn-* according to
     * initial options).
     */
    protected function _addButtonClasses($options) {
        $options += [
            'bootstrap-type' => $this->getConfig('buttons.type'),
            'bootstrap-size' => false,
            'bootstrap-block' => false
        ];
        $type = $options['bootstrap-type'];
        $size = $options['bootstrap-size'];
        $block = $options['bootstrap-block'];
        unset($options['bootstrap-type'], $options['bootstrap-size'],
            $options['bootstrap-block']);
        $options = $this->addClass($options, 'btn');
        if (!preg_match('#btn-[a-z]+#', $options['class'])) {
            $options = $this->addClass($options, 'btn-'.$type);
        }
        if ($size) {
            $options = $this->addClass($options, 'btn-'.$size);
        }
        if ($block) {
            $options = $this->addClass($options, 'btn-block');
        }
        return $options;
    }

    /**
     * Check weither the specified array is associative or not.
     *
     * @param array $array The array to check.
     *
     * @return bool `true` if the array is associative, `false` otherwize.
     */
    protected function _isAssociativeArray($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }

}

?>