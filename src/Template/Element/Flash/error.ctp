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

$helper = new \Bootstrap\View\Helper\BootstrapHtmlHelper ($this) ;
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
echo $helper->alert($message, 'danger', $params) ;

?>
