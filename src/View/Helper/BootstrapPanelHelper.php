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

class BootstrapPanelHelper extends Helper {

    use BootstrapTrait;

    /**
     * Other helpers used by BootstrapPanelHelper.
     *
     * @var array
     */
    public $helpers = [
        'Html' => [
            'className' => 'Bootstrap.BootstrapHtml'
        ]
    ];

    /**
     * Default configuration for the helper.
     *
     * - `collapsible` Default behavior for collapsible panel.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'collapsible' => false
    ];

    /**
     * Current part of the panel (`null`, `'header'`, `'body'`, `'footer'`).
     *
     * @var string
     */
    protected $_current = NULL ;

    /**
     * Panel counter (for collapsible groups).
     *
     * @var int
     */
    protected $_panelCount = 0;

    /**
     * Body HTML id (for collapsible panel).
     *
     * @var string
     */
    protected $_bodyId = null;

    /**
     * Header HTML id (for collapsible panel).
     *
     * @var string
     */
    protected $_headId = null;

    /**
     * Panel groups counter (for panel groups).
     *
     * @var int
     */
    protected $_groupCount = 0;

    /**
     * Panel group HTML id.
     *
     * @var mixed
     */
    protected $_groupId = false;

    /**
     * Panel counter inside a group (count the number of panels in a group).
     *
     * @var int
     */
    protected $_groupPanelCount = 0;

    /**
     * Panel to open inside a group.
     *
     * @var mixed
     */
    protected $_groupPanelOpen = false;


    /**
     * Indicate if in group mode or not.
     *
     * @var bool
     */
    protected $_groupInGroup = false;

    /**
     * Indicate if the last panel was closed (in a group).
     *
     * @var bool
     */
    protected $_lastPanelClosed    = true;

    /**
     * Determine if previous panel should be automatically closed when calling `create()`.
     *
     * @var bool
     */
    protected $_autoCloseOnCreate  = false;

    /**
     * Indicate if panel should be collapsible or not.
     *
     * @var bool
     */
    protected $_collapsible = false;

    /**
     * Open a panel group.
     *
     * ### Options
     *
     * - `aria-multiselectable` HTML attribute. Default is `true`.
     * - `collapsible` Set to `false` if panels should not be collapsible. Default is `true`.
     * - `id` Identifier for the group. Default is automatically generated.
     * - `open` If `collapsible` is `true`, indicate the panel that should be open by default.
     * Set to `false` to have no panels open. You can also indicate if a panel should be open
     * in the `create()` method. Default is `0`.
     * - `role` HTML attribute. Default is `'tablist'`.
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
            'role'                 => 'tablist',
            'aria-multiselectable' => true,
            'id'                   => 'panelGroup-'.(++$this->_groupCount),
            'collapsible'          => true,
            'open'                 => 0
        ];
        $this->config('saved.collapsible', $this->config('collapsible'));
        $this->config('collapsible', $options['collapsible']);
        $this->_autoCloseOnCreate  = true;
        $this->_lastPanelClosed    = true;
        $this->_groupPanelCount    = -1;
        $this->_groupPanelOpen     = $options['open'];
        $this->_groupId            = $options['id'];
        $this->_groupInGroup       = true;
        $options = $this->addClass($options, 'panel-group');
        unset($options['open'], $options['collapsible']);
        return $this->Html->tag('div', null, $options);
    }

    /**
     * Closes a panel group, closes the last panel if it has not already been closed.
     *
     * @return string An HTML string containing closing tags.
     */
    public function endGroup() {
        $this->config('collapsible', $this->config('saved.collapsible'));
        $this->_autoCloseOnCreate  = false;
        $this->_groupId            = false;
        $this->_groupPanelOpen     = false;
        $this->_groupInGroup       = false;
        $out = '';
        if (!$this->_lastPanelClosed) {
            $out = $this->end();
        }
        return $out.'</div>';
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
     * - `no-body` If `$title` is a string, set to `true` to not open the body after the
     * panel header. Default is `false`.
     * - `open` Indicate if the panel should be open. If the panel is not inside a group, the
     * default is `true`, otherwize the default is `false` and the panel is open if its
     * count matches the specified value in `startGroup()` (set to `true` inside a group to
     * force the panel to be open).
     * - `panel-count` Panel counter, can be used to override the default counter when inside
     * a group. This value is used to generate the panel, header and body ID attribute.
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

        $options += [
            'no-body'     => false,
            'type'        => 'default',
            'collapsible' => $this->config('collapsible'),
            'open'        => !$this->_groupInGroup,
            'panel-count' => $this->_panelCount
        ];

        $nobody = $options['no-body'];
        $type   = $options['type'];
        $open   = $options['open'];
        $this->_collapsible = $options['collapsible'];
        $panelCount = $options['panel-count'];
        unset ($options['no-body'], $options['collapsible'],
               $options['type'], $options['open'], $options['panel-count']);

        $options = $this->addClass($options, ['panel', 'panel-'.$type]);

        if ($this->_collapsible) {
            $this->_headId = 'heading-'.$panelCount;
            $this->_bodyId = 'collapse-'.$panelCount;
            $this->_panelCount = intval($panelCount) + 1;
            if ($open) {
                $this->_groupPanelOpen = $this->_bodyId;
            }
        }

        $out = '';

        if ($this->_autoCloseOnCreate && !$this->_lastPanelClosed) {
            $out .= $this->end();
        }
        $this->_lastPanelClosed = false;

        /* Increment panel counter for the current group. */
        $this->_groupPanelCount++;

        $out .= $this->Html->tag('div', null, $options);
        if (is_string($title) && $title) {
            $out .= $this->_createHeader($title, [
                'title' => isset($options['title']) ? $options['title'] : true
            ]) ;
            if (!$nobody) {
                $out .= $this->_createBody();
            }
        }

        return $out ;
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
        $res = '' ;
        $res .= $this->_cleanCurrent();
        if ($content !== null) {
            $res .= $this->footer($content, $options) ;
        }
        $res .= '</div>' ;
        return $res ;
    }

    /**
     * Cleans the current panel part and return necessary HTML closing elements.
     *
     * @return string An HTML string containing closing elements.
     */
    protected function _cleanCurrent() {
        $res = '';
        if ($this->_current) {
            $res .= '</div>';
            if ($this->_collapsible && $this->_current == 'body') {
                $res .= '</div>';
            }
            $this->_current = null ;
        }
        return $res;
    }

    /**
     * Check if the current panel should be open or not.
     *
     * @return bool `true` if the current panel should be open, `false` otherwize.
     */
    protected function _isOpen() {
        return (is_int($this->_groupPanelOpen)
                && $this->_groupPanelOpen === $this->_groupPanelCount)
            || $this->_groupPanelOpen === $this->_bodyId;
    }

    /**
     * Create or open a panel header.
     *
     * ### Options
     *
     * - `title` See `header()`.
     * - Other attributes for the panel header `<div>`.
     *
     * @param string|null $text The panel header content, or null to only open the header.
     * @param array       $options Array of options. See above.
     *
     * @return string A formated opening tag for the panel header or the complete panel
     * header.
     */
    protected function _createHeader ($title, $options = [], $titleOptions = []) {
        if (empty($titleOptions)) {
            $titleOptions = $options['title'] ;
        }
        unset($options['title']);
        $title   = $this->_makeIcon($title, $converted);
        $options += [
            'escape' => !$converted
        ];
        $options = $this->addClass($options, 'panel-heading');
        if ($this->_collapsible) {
            $options += [
                'role' => 'tab',
                'id'   => $this->_headId,
                'open' => $this->_isOpen()
            ];
            $this->_headId = $options['id'];
            $title = $this->Html->link($title, '#'.$this->_bodyId, [
                'data-toggle'   => 'collapse',
                'data-parent'   => $this->_groupId ? '#'.$this->_groupId : false,
                'aria-expanded' => json_encode($options['open']),
                'aria-controls' => '#'.$this->_bodyId,
                'escape'        => $options['escape']
            ]);
            $options['escape'] = false; // Should not escape after
        }
        if ($titleOptions !== false) {
            if (!is_array($titleOptions)) {
                $titleOptions = [];
            }
            $titleOptions += [
                'tag'    => 'h4',
                'escape' => $options['escape']
            ];
            $titleOptions = $this->addClass($titleOptions, 'panel-title');
            $tag = $titleOptions['tag'];
            unset($titleOptions['tag']);
            $title = $this->Html->tag($tag, $title, $titleOptions);
        }
        unset($options['escape'], $options['open']);
        return $this->_cleanCurrent().$this->Html->tag('div', $title, $options);
    }

    /**
     * Create or open a panel body.
     *
     * @param string|null $text The panel body content, or null to only open the body.
     * @param array       $options Array of options for the body `<div>`.
     *
     * @return string A formated opening tag for the panel body or the complete panel
     * body.
     */
    protected function _createBody ($text = null, $options = []) {
        $options = $this->addClass($options, 'panel-body');
        $body = $this->Html->tag('div', $text, $options);
        if ($this->_collapsible) {
            $open = $this->_isOpen() ? ' in' : '';
            $body = $this->Html->div('panel-collapse collapse'.$open, $text ? $body : null, [
                'role' => 'tabpanel',
                'aria-labelledby' => $this->_headId,
                'id' => $this->_bodyId
            ]).($text ? '' : $body);
        }
        $body = $this->_cleanCurrent().$body;
        if (!$text) {
            $this->_current = 'body';
        }
        return $body;
    }

    /**
     * Create or open a panel footer.
     *
     * @param string|null $text The panel footer content, or null to only open the footer.
     * @param array       $options Array of options for the footer `<div>`.
     *
     * @return string A formated opening tag for the panel footer or the complete panel
     * footer.
     */
    protected function _createFooter ($text = null, $options = []) {
        $options = $this->addClass($options, 'panel-footer');
        return $this->_cleanCurrent().$this->Html->tag('div', $text, $options) ;
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
     * - Other attributes for the panel header `<div>`.
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
        return $this->_createHeader($info, $options) ;
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
     * @param string|array $text The footer content, or `null`, or an array of options.
     * @param array        $options Array of options for the panel footer `<div>`.
     *
     * @return string A formated opening tag for the panel footer or the complete panel
     * footer.
     */
    public function footer ($text = null, $options = []) {
        if (is_array($text)) {
            $options = $text;
            $text    = null;
        }
        return $this->_createFooter($text, $options) ;
    }

}

?>
