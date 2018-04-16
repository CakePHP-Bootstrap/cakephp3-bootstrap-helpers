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

use Bootstrap\Utility\StackedStates;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Panel helper library.
 *
 * Automatic generation of Bootstrap HTML panels.
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
     * - `collapsible` Default behavior for collapsible panel.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'panelGroupStart' => '<div class="panel-group{{attrs.class}}" role="tablist" aria-multiselectable="true"{{attrs}}>',
            'panelGroupEnd' => '</div>',
            'panelStart' => '<div class="panel panel-{{type}}{{attrs.class}}"{{attrs}}>',
            'panelEnd' => '</div>',
            'headerStart' => '<div class="panel-heading{{attrs.class}}"{{attrs}}>',
            'headerCollapsibleStart' => '<div class="panel-heading{{attrs.class}}" role="tab"{{attrs}}>',
            'headerTitle' => '<h4 class="panel-title{{attrs.class}}"{{attrs}}>{{content}}</h4>',
            'headerCollapsibleLink' =>
'<a role="button" data-toggle="collapse" href="#{{target}}" aria-expanded="{{expanded}}" aria-controls="{{target}}"{{attrs}}>{{content}}</a>',
            'headerEnd' => '</div>',
            'bodyStart' => '<div class="panel-body{{attrs.class}}"{{attrs}}>',
            'bodyEnd' => '</div>',
            'bodyCollapsibleStart' =>
                '<div class="panel-collapse collapse{{attrs.class}}" role="tabpanel" aria-labelledby="{{headId}}"{{attrs}}>{{bodyStart}}',
            'bodyCollapsibleEnd' => '{{bodyEnd}}</div>',
            'footerStart' => '<div class="panel-footer{{attrs.class}}"{{attrs}}>',
            'footerEnd' => '</div>'
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate',
        'collapsible' => false
    ];

    /**
     * States of the panel helper (contains states of type 'group' and 'panel').
     *
     * @var StackedStates
     */
    protected $_states;

    /**
     * Panel counter (for collapsible groups).
     *
     * @var int
     */
    protected $_panelCount = 0;

    /**
     * Panel groups counter (for panel groups).
     *
     * @var int
     */
    protected $_groupCount = 0;

    public function __construct(\Cake\View\View $View, array $config = []) {
        $this->_states = new StackedStates([
            'group' => [
                'groupPanelOpen' => false,
                'groupPanelCount' => -1,
                'groupId' => false,
                'groupCollapsible' => true
            ],
            'panel' => [
                'part' => null,
                'bodyId' => null,
                'headId' => null,
                'collapsible' => false,
                'open' => false,
                'inGroup' => false
            ]
        ]);
        parent::__construct($View, $config);
    }
    /**
     * Open a panel group.
     *
     * ### Options
     *
     * - `collapsible` Set to `false` if panels should not be collapsible. Default is `true`.
     * - `id` Identifier for the group. Default is automatically generated.
     * - `open` If `collapsible` is `true`, indicate the panel that should be open by default.
     * Set to `false` to have no panels open. You can also indicate if a panel should be open
     * in the `create()` method. Default is `0`.
     *
     * - Other attributes will be passed to the `Html::div()` method.
     *
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening HTML tag for panel groups.
     *
     * @link http://getbootstrap.com/javascript/#collapse-example-accordion
     */
    public function startGroup($options = []) {
        $options += [
            'id'                   => 'panelGroup-'.(++$this->_groupCount),
            'collapsible'          => true,
            'open'                 => 0,
            'templateVars' => []
        ];
        $this->_states->push('group', [
            'groupPanelOpen' => $options['open'],
            'groupPanelCount' => -1,
            'groupId' => $options['id'],
            'groupCollapsible' => $options['collapsible']
        ]);
        return $this->formatTemplate('panelGroupStart', [
            'attrs' => $this->templater()->formatAttributes($options, ['open', 'collapsible']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Closes a panel group, closes the last panel if it has not already been closed.
     *
     * @return string An HTML string containing closing tags.
     */
    public function endGroup() {
        $out = '';
        while ($this->_states->is('panel')) { // panels were not closed
            $out .= $this->end();
        }
        $out .= $this->formatTemplate('panelGroupEnd', []);
        $this->_states->pop();
        return $out;
    }

    /**
     * Open a panel.
     *
     * If `$title` is a string, the panel header is created using `$title` as its
     * content and default options (except for the `title` options that can be specified
     * inside `$options`).
     *
     * ```php
     * echo $this->Panel->create('My Panel Title', ['title' => ['tag' => 'h2']]);
     * ```
     *
     * If the panel header is created, the panel body is automatically opened after
     * it, except if the `no-body` options is specified (see below).
     *
     * If `$title` is an array, it is used as `$options`.
     *
     * ```php
     * echo $this->Panel->create(['class' => 'my-panel-class']);
     * ```
     *
     * If the `create()` method is used inside a panel group, the previous panel is
     * automatically closed.
     *
     * ### Options
     *
     * - `collapsible` Set to `true` if the panel should be collapsible. Default is fetch
     * from configuration/
     * - `body` If `$title` is a string, set to `false` to not open the body after the
     * panel header. Default is `true`.
     * - `open` Indicate if the panel should be open. If the panel is not inside a group, the
     * default is `true`, otherwize the default is `false` and the panel is open if its
     * count matches the specified value in `startGroup()` (set to `true` inside a group to
     * force the panel to be open).
     * - `panel-count` Panel counter, can be used to override the default counter when inside
     * a group. This value is used to generate the panel, header and body ID attribute.
     * - `title` Array of options for the title. Default is [].
     * - `type` Type of the panel (`'default'`, `'primary'`, ...). Default is `'default'`.
     * - Other options will be passed to the `Html::div` method for creating the
     * panel `<div>`.
     *
     * @param array|string $title   The panel title or an array of options.
     * @param array        $options Array of options. See above.
     *
     * @return string An HTML string containing opening elements for a panel.
     */
    public function create($title = null, $options = []) {

        if (is_array($title)) {
            $options = $title;
            $title   = null;
        }

        $out = '';

        // close previous panel if in group
        if ($this->_states->is('panel') && $this->_states->getValue('inGroup')) {
            $out .= $this->end();
        }

        $options += [
            'body'     => true,
            'type'        => 'default',
            'collapsible' => $this->_states->is('group') ?
                $this->_states->getValue('groupCollapsible') : $this->getConfig('collapsible'),
            'open'        => !$this->_states->is('group'),
            'panel-count' => $this->_panelCount,
            'title' => [],
            'templateVars' => []
        ];

        $this->_panelCount = intval($options['panel-count']) + 1;

        // check open
        $open = $options['open'];
        if ($this->_states->is('group')) {
            // increment count inside
            $this->_states->setValue('groupPanelCount',
                $this->_states->getValue('groupPanelCount') + 1);
            $open = $open
                || $this->_states->getValue('groupPanelOpen')
                    == $this->_states->getValue('groupPanelCount');
        }

        $out .= $this->formatTemplate('panelStart', [
            'type' => $options['type'],
            'attrs' => $this->templater()->formatAttributes(
                $options, ['body', 'type', 'collapsible', 'open', 'panel-count', 'title']),
            'templateVars' => $options['templateVars']
        ]);

        $this->_states->push('panel', [
            'part' => null,
            'bodyId' => 'collapse-'.$options['panel-count'],
            'headId' => 'heading-'.$options['panel-count'],
            'collapsible' => $options['collapsible'],
            'open' => $open,
            'inGroup' => $this->_states->is('group'),
            'groupId' => $this->_states->is('group') ?
                $this->_states->getValue('groupId') : 0
        ]);

        if (is_string($title) && $title) {
            $out .= $this->_createHeader($title, [
                'title' => $options['title']
            ]);
            if ($options['body']) {
                $out .= $this->_createBody();
            }
        }

        return $out;
    }

    /**
     * Closes a panel, cleans part that have not been closed correctly and optionaly adds a
     * footer to the panel.
     *
     * If `$content` is not null, the `footer()` methods will be used to create the panel
     * footer using `$content` and `$options`.
     *
     * ```php
     * echo $this->Panel->end('Footer Content', ['my-class' => 'my-footer-class']);
     * ```
     *
     * @param string|null $content   Footer content, or `null`.
     * @param array       $options Array of options for the footer.
     *
     * @return string An HTML string containing closing tags.
     */
    public function end($content = null, $options = []) {
        $this->_lastPanelClosed = true;
        $res = '';
        $res .= $this->_cleanCurrent();
        if ($content !== null) {
            $res .= $this->footer($content, $options);
        }
        $res .= $this->formatTemplate('panelEnd', []);
        $this->_states->pop();
        return $res;
    }

    /**
     * Cleans the current panel part and return necessary HTML closing elements.
     *
     * @return string An HTML string containing closing elements.
     */
    protected function _cleanCurrent() {
        if (!$this->_states->is('panel')) {
            return '';
        }
        $current = $this->_states->getValue('part');
        if ($current === null) {
            return '';
        }
        $out = $this->formatTemplate($current.'End', []);
        if ($this->_states->getValue('collapsible')) {
            $ctplt = $current.'CollapsibleEnd';
            if ($this->getTemplates($ctplt)) {
                $out = $this->formatTemplate($ctplt, [
                    $current.'End' => $out
                ]);
            }
        }
        $this->_states->setValue('part', null);
        return $out;
    }

    /**
     * Check if the current panel should be open or not.
     *
     * @return bool `true` if the current panel should be open, `false` otherwize.
     */
    protected function _isOpen() {
        return $this->_states->getValue('open');
    }

    /**
     * Create or open a panel header.
     *
     * ### Options
     *
     * - `title` See `header()`.
     * - `templateVars` Provide template variables for the header template.
     * - Other attributes will be assigned to the header element.
     *
     * @param string $text The panel header content, or null to only open the header.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the panel header or the complete panel
     * header.
     */
    protected function _createHeader($title, $options = [], $titleOptions = []) {
        $title = $this->_makeIcon($title, $converted);
        $options += [
            'escape' => !$converted,
            'templateVars' => []
        ];
        if (empty($titleOptions)) {
            $titleOptions = $options['title'];
        }
        $out = $this->formatTemplate('headerStart', [
            'attrs' => $this->templater()->formatAttributes($options, ['title']),
            'templateVars' => $options['templateVars']
        ]);
        if ($this->_states->getValue('collapsible')) {
            $out = $this->formatTemplate('headerCollapsibleStart', [
                'attrs' => $this->templater()->formatAttributes(['id' => $this->_states->getValue('headId')]),
                'templateVars' => $options['templateVars']
            ]);
            if ($title) {
                $title = $this->formatTemplate('headerCollapsibleLink', [
                    'expanded' => json_encode($this->_isOpen()),
                    'target' => $this->_states->getValue('bodyId'),
                    'content' => $options['escape'] ? h($title) : $title,
                    'attrs' => $this->templater()->formatAttributes($this->_states->getValue('inGroup') ? [
                        'data-parent' => '#'.$this->_states->getValue('groupId')
                    ] : []),
                    'templateVars' => $options['templateVars']
                ]);
            }
            $options['escape'] = false;
        }
        $out = $this->_cleanCurrent().$out;
        $this->_states->setValue('part', 'header');
        if ($titleOptions === false) {
            $title = null;
        }
        if ($title) {
            if (!is_array($titleOptions)) {
                $titleOptions = [];
            }
            $titleOptions += [
                'templateVars' => []
            ];
            $out .= $this->formatTemplate('headerTitle', [
                'content' => $options['escape'] ? h($title) : $title,
                'attrs' => $this->templater()->formatAttributes($titleOptions),
                'templateVars' => $titleOptions['templateVars']
            ]);
            $out .= $this->_cleanCurrent();
        }
        return $out;
    }

    /**
     * Create or open a panel body.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the body template.
     * - Other attributes will be assigned to the body element.
     *
     * @param string|null $text The panel body content, or null to only open the body.
     * @param array $options Array of options for the body `<div>`.
     *
     * @return string A formated opening tag for the panel body or the complete panel
     * body.
     */
    protected function _createBody($text = null, $options = []) {
        $options += [
            'templateVars' => []
        ];

        $out = $this->formatTemplate('bodyStart', [
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
        if ($this->_states->getValue('collapsible')) {
            $out = $this->formatTemplate('bodyCollapsibleStart', [
                'bodyStart' => $out,
                'headId' => $this->_states->getValue('headId'),
                'attrs' => $this->templater()->formatAttributes([
                    'id' => $this->_states->getValue('bodyId'),
                    'class' => $this->_isOpen() ? 'in' : ''
                ]),
                'templateVars' => $options['templateVars']
            ]);
        }
        $out = $this->_cleanCurrent().$out;
        $this->_states->setValue('part', 'body');
        if ($text) {
            $out .= $text;
            $out .= $this->_cleanCurrent();
        }
        return $out;
    }

    /**
     * Create or open a panel footer.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the footer template.
     * - Other attributes will be assigned to the footer element.
     *
     * @param string $text The panel footer content, or null to only open the footer.
     * @param array $options Array of options for the footer `<div>`.
     *
     * @return string A formated opening tag for the panel footer or the complete panel
     * footer.
     */
    protected function _createFooter($text = null, $options = []) {
        $options += [
            'templateVars' => []
        ];
        $out = $this->_cleanCurrent();
        $this->_states->setValue('part', 'footer');
        $out .= $this->formatTemplate('footerStart', [
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
        if ($text) {
            $out .= $text;
            $out .= $this->_cleanCurrent();
        }
        return $out;
    }

    /**
     * Create or open a panel header.
     *
     * If `$text` is a string, create a panel header using the specified content
     * and `$options`.
     *
     * ```php
     * echo $this->Panel->header('Header Content', ['class' => 'my-class']);
     * ```
     *
     * If `$text` is `null`, create a formated opening tag for a panel header using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Panel->header(null, ['class' => 'my-class']);
     * ```
     *
     * If `$text` is an array, used it as `$options` and create a formated opening tag for
     * a panel header.
     *
     * ```php
     * echo $this->Panel->header(['class' => 'my-class']);
     * ```
     *
     * You can use the `title` option to wrap the content:
     *
     * ```php
     * echo $this->Panel->header('My Title', ['title' => false]);
     * echo $this->Panel->header('My Title', ['title' => true]);
     * echo $this->Panel->header('My <Title>', ['title' => ['tag' => 'h2', 'class' => 'my-class', 'escape' => true]]);
     * ```
     *
     * ### Options
     *
     * - `title` Can be used to wrap the header content into a title tag (default behavior):
     *   - If `true`, wraps the content into a `<h4>` tag. You can specify an array instead
     *     of `true` to control the `tag`. See example above.
     *   - If `false`, does not wrap the content.
     * - `templateVars` Provide template variables for the header template.
     * - Other attributes will be assigned to the header element.
     *
     * @param string|array $text The header content, or `null`, or an array of options.
     * @param array        $options Array of options. See above.
     *
     * @return string A formated opening tag for the panel header or the complete panel
     * header.
     */
    public function header($info = null, $options = []) {
        if (is_array($info)) {
            $options = $info;
            $info    = null;
        }
        $options += [
            'title' => true
        ];
        return $this->_createHeader($info, $options);
    }

    /**
     * Create or open a panel body.
     *
     * If `$content` is a string, create a panel body using the specified content and
     * `$options`.
     *
     * ```php
     * echo $this->Panel->body('Panel Content', ['class' => 'my-class']);
     * ```
     *
     * If `$content` is `null`, create a formated opening tag for a panel body using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Panel->body(null, ['class' => 'my-class']);
     * ```
     *
     * If `$content` is an array, used it as `$options` and create a formated opening tag for
     * a panel body.
     *
     * ```php
     * echo $this->Panel->body(['class' => 'my-class']);
     * ```
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the body template.
     * - Other attributes will be assigned to the body element.
     *
     * @param array|string $info The body content, or `null`, or an array of options.
     * `$options`.
     * @param array $options Array of options for the panel body `<div>`.
     *
     * @return string
     */
    public function body($content = null, $options = []) {
        if (is_array($content)) {
            $options = $content;
            $content    = null;
        }
        return $this->_createBody($content, $options);
    }

    /**
     * Create or open a panel footer.
     *
     * If `$text` is a string, create a panel footer using the specified content
     * and `$options`.
     *
     * ```php
     * echo $this->Panel->footer('Footer Content', ['class' => 'my-class']);
     * ```
     *
     * If `$text` is `null`, create a formated opening tag for a panel footer using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Panel->footer(null, ['class' => 'my-class']);
     * ```
     *
     * If `$text` is an array, used it as `$options` and create a formated opening tag for
     * a panel footer.
     *
     * ```php
     * echo $this->Panel->footer(['class' => 'my-class']);
     * ```
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the footer template.
     * - Other attributes will be assigned to the footer element.
     *
     * @param string|array $text The footer content, or `null`, or an array of options.
     * @param array        $options Array of options for the panel footer `<div>`.
     *
     * @return string A formated opening tag for the panel footer or the complete panel
     * footer.
     */
    public function footer($text = null, $options = []) {
        if (is_array($text)) {
            $options = $text;
            $text    = null;
        }
        return $this->_createFooter($text, $options);
    }

}

?>
