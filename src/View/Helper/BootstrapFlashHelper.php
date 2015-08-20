<?php
    
/**
* Bootstrap Flash Helper
*
*
* PHP 5
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
*
* @copyright Copyright (c) Mikaël Capelle (http://mikael-capelle.fr)
* @link http://mikael-capelle.fr
* @package app.View.Helper
* @since Apache v2
* @license http://www.apache.org/licenses/LICENSE-2.0
*/

namespace Bootstrap\View\Helper;

use Cake\View\Helper\FlashHelper;

class BootstrapFlashHelper extends FlashHelper {

    use BootstrapTrait ;

    protected $_bootstrapTemplates = ['info', 'error', 'success', 'warning'] ;

    /**
     * Used to render the message set in FlashComponent::set()
     *
     * In your view: $this->Flash->render('somekey');
     * Will default to flash if no param is passed
     *
     * You can pass additional information into the flash message generation. This allows you
     * to consolidate all the parameters for a given type of flash message into the view.
     *
     * ```
     * echo $this->Flash->render('flash', ['params' => ['name' => $user['User']['name']]]);
     * ```
     *
     * This would pass the current user's name into the flash message, so you could create personalized
     * messages without the controller needing access to that data.
     *
     * Lastly you can choose the element that is used for rendering the flash message. Using
     * custom elements allows you to fully customize how flash messages are generated.
     *
     * ```
     * echo $this->Flash->render('flash', ['element' => 'my_custom_element']);
     * ```
     *
     * If you want to use an element from a plugin for rendering your flash message
     * you can use the dot notation for the plugin's element name:
     *
     * ```
     * echo $this->Flash->render('flash', [
     *   'element' => 'MyPlugin.my_custom_element',
     * ]);
     * ```
     *
     * @param string $key The [Flash.]key you are rendering in the view.
     * @param array $options Additional options to use for the creation of this flash message.
     *    Supports the 'params', and 'element' keys that are used in the helper.
     * @return string|void Rendered flash message or null if flash key does not exist
     *   in session.
     * @throws \UnexpectedValueException If value for flash settings key is not an array.
     */
    public function render($key = 'flash', array $options = []) {
        if (!$this->request->session()->check("Flash.$key")) {
            return;
        }

        $flash = $this->request->session()->read("Flash.$key");
        if (!is_array($flash)) {
            throw new \UnexpectedValueException(sprintf(
                'Value for flash setting key "%s" must be an array.',
                $key
            ));
        }
        $flash = $options + $flash;
        $this->request->session()->delete("Flash.$key");

        $element = $flash['element'] ;
        if (in_array(basename($element), $this->_bootstrapTemplates)) {
            $flash['element'] = 'Bootstrap3.'.$element ;
        }

        return $this->_View->element($flash['element'], $flash);
    }

}

?>