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
class TabHelper extends Helper {

    use ClassTrait;
    use EasyIconTrait;
    use SectionTrait;
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
            'tabStart' => '<div>',
            'tabEnd' => '</div>',
            'headerStart' => '<ul class="nav nav-{{type}}{{attrs.class}}" role="tablist"{{attrs>}}',
            'headerEnd' => '</ul>',
            'contentStart' => '<div class="tab-content{{attrs.class}}"{{attrs}}>',
            'contentEnd' => '</div>',
            'contentPanelStart' => '<div role="tabpanel" class="tab-pane{{attrs.class}}" id="{{panelId}}"{{attrs}}>',
            'contentPanelEnd' => '<div>'
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate'
    ];

    /**
     * Fade enabled or not.
     *
     * @var boolean
     */
    protected $_fade = true;

    /**
     * Type of the tab.
     *
     * @var string
     */
    protected $_type = 'tabs';


    /**
     * Create or open the tab header.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the header template.
     * - Other attributes will be assigned to the header element.
     *
     * @param array $titles The panel header content, or null to only open the header.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the panel header or the complete panel
     * header.
     */
    protected function _createHeader($titles, $options = []) {
        $options += [
            'type' => $this->_type,
            'templateVars' => []
        ];
        $out = $this->_openSection('header', [
            'type' => $options['type'],
            'attrs' => $this->templater()->formatAttributes($options, ['type'])
        ]);
        if ($titles) {
            foreach ($titles as $key => $val) {
                // if key is numeric, there are no options specified
                if (is_numeric($key)) {
                    $key = $val;
                    $val = [];
                }
//                $out .= $this->_createTab($key, null, )
            }
        }
        return $out;
    }

    /**
     * Open a navigation tabs (or pills) with the specified titles.
     *
     * ### Options:
     *
     * - `type` `'tabs'` or `'pills'`. Default is `'tabs'`.
     * - `fade` Make tabs fade or not. Default is `true`.
     * - Other attributes will be added to the wrapping element.
     *
     * @param array $title Titles for the navigations tabs / pills. See `header()`.
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
        $this->_fade = $options['fade'];
        $this->_type = $options['type'];
        $out = $this->_openSection('tab', [
            'attrs' => $this->templater()->formatAttributes($options, ['type', 'fade']),
            'templateVars' => $options['templateVars']
        ]);
        if ($titles) {
            $out .= $this->_createHeader($titles);
        }
        return $out;
    }

    /**
     * Closes a navigation tabs, cleans part that have not been closed correctly.
     *
     * @return string An HTML string containing closing tags.
     */
    public function end() {
        return $this->_clearSections();
    }

}

?>
