<?php
/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @link        https://holt59.github.io/cakephp3-bootstrap-helpers/
 */

$helper = new \Bootstrap\View\Helper\HtmlHelper ($this) ;
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
echo $helper->alert($message, 'danger', $params) ;

?>
