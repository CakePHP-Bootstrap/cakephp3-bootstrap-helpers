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
            'tabStart' => '</div>',
            'tabHeadingStart' => '<ul class="nav nav-{{type}}{{attrs.class}}" role="tablist"{{attrs>}}',
            'tabContentStart' => '<div class="tab-content{{attrs.class}}"{{attrs}}>',
            'tabPanelStart' => '<div role="tabpanel" class="tab-pane{{attrs.class}}" id="{{panelId}}"{{attrs}}>',
            'tabEnd' => '</div>',
            'tabHeadingEnd' => '</ul>',
            'tabContentEnd' => '</div>',
            'tabPanelEnd' => '</div>'
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate'
    ];

    /**
     * Open a navigation tabs (or pills) with the specified titles.
     *
     * ### Options:
     *
     * - `type` `'tabs'` or `'pills'`. Default is `'tabs'`.
     * - `fade` Make tabs fade or not. Default is `true`.
     * - Other attributes will be added to the wrapping element.
     *
     * @param array $title Titles for the navigations tabs / pills.
     * @param array $options Array of options. See above.
     *
     * @return string An HTML string containing opening elements for a
     * navigation tabs.
     */
    public function create($titles, $options = []) {
        $options += [
            'type' => 'tabs',
            'fade' => true,
            'templateVars' => []
        ];
    }

    /**
     * Closes a navigation tabs, cleans part that have not been closed correctly.
     *
     * @return string An HTML string containing closing tags.
     */
    public function end() {
        return $this->_cleanCurrent().$this->formatTemplate('tabEnd', []);
    }

}

?>
