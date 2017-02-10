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

/**
 * FlashHelper class to render flash messages.
 *
 * After setting messages in your controllers with FlashComponent, you can use
 * this class to output your flash messages in your views.
 */
class BootstrapFlashHelper extends FlashHelper {

    /**
     * Available bootstrap templates for alert.
     *
     * @var array
     */
    protected $_bootstrapTemplates = ['info', 'error', 'success', 'warning'];

    /**
     * Used to render the message set in FlashComponent::set().
     *
     * @param string $key     The [Flash.]key you are rendering in the view.
     * @param array  $options Additional options to use for the creation of this
     * flash message. Supports the 'params', and 'element' keys that are used in the helper.
     *
     * @return string|void Rendered flash message or null if flash key does not exist
     *   in session.
     * @throws \UnexpectedValueException If value for flash settings key is not an array.
     *
     * @link https://book.cakephp.org/3.0/en/views/helpers/flash.html
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
