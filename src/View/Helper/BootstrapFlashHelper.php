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
        foreach ($flash as &$message) {
            if (in_array(basename($message['element']), $this->_bootstrapTemplates)) {
                $message['element'] = 'Bootstrap.'.$message['element'];
            }
        }
        $this->request->session()->write("Flash.$key", $flash);

        return parent::render($key, $options);
    }

}

?>
