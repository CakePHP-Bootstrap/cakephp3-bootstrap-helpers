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
 * BreadcrumbsHelper to register and display a breadcrumb trail for your views.
 *
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class BreadcrumbsHelper extends \Cake\View\Helper\BreadcrumbsHelper{

    /**
     * Default config for the helper.
     *
     * @var array
     * @link https://api.cakephp.org/3.3/class-Cake.View.Helper.BreadcrumbsHelper.html
     */
    protected $_defaultConfig = [
        'templates' => [
            'wrapper' => '<ol class="breadcrumb{{attrs.class}}"{{attrs}}>{{content}}</ol>',
            'item' => '<li class="breadcrumb-item{{attrs.class}}"{{attrs}}><a href="{{url}}"{{innerAttrs}}>{{title}}</a></li>',
            'itemWithoutLink' => '<li class="breadcrumb-item active{{attrs.class}}"{{attrs}}>{{title}}</li>',
            'separator' => ''
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate'
    ];

};
