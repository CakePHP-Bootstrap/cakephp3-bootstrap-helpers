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

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Tab helper library.
 *
 * Automatic generation of Bootstrap HTML tabs.
 *
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 */
class PanelHelper extends Helper {

    use ClassTrait;
    use EasyIconTrait;
    use StringTemplateTrait;

    /**
     * Other helpers used by PanelHelper.
     *
     * @var array
     */
    public $helpers = [
        'Html'
    ];

    /**
     * Default configuration for the helper.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate'
    ];

}

?>
