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
namespace Bootstrap\TestApp;

use Bootstrap\View\Helper\EasyIconTrait;
use Bootstrap\View\Helper\HtmlHelper;

class PublicEasyIconTrait
{
    use EasyIconTrait;

    public function __construct($view)
    {
        $this->Html = new HtmlHelper($view);
    }

    public function publicMakeIcon($title, &$converted)
    {
        return $this->_makeIcon($title, $converted);
    }
}
