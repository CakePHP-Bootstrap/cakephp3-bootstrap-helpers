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
 * Card helper library.
 *
 * Automatic generation of Bootstrap HTML cards.
 *
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 */
class CardHelper extends Helper {

    use ClassTrait;
    use EasyIconTrait;
    use StringTemplateTrait;

    /**
     * Other helpers used by CardHelper.
     *
     * @var array
     */
    public $helpers = [
        'Html'
    ];

    /**
     * Default configuration for the helper.
     *
     * - `collapsible` Default behavior for collapsible card.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'cardGroupStart' => '<div role="tablist"{{attrs}}>',
            'cardGroupEnd' => '</div>',
            'cardStart' => '<div class="card card-{{type}}{{attrs.class}}"{{attrs}}>',
            'cardEnd' => '</div>',
            'headerStart' => '<div class="card-header{{attrs.class}}"{{attrs}}>',
            'headerCollapsibleStart' => '<div class="card-header{{attrs.class}}" role="tab"{{attrs}}>',
            'headerCollapsibleLink' =>
'<h5 class="mb-0"><a role="button" data-toggle="collapse" href="#{{target}}" aria-expanded="{{expanded}}" aria-controls="{{target}}"{{attrs}}>{{content}}</a></h5>',
            'headerEnd' => '</div>',
            'title' => '<h4 class="card-title{{attrs.class}}"{{attrs}}>{{content}}</h4>',
            'bodyStart' => '<div class="card-body{{attrs.class}}"{{attrs}}>',
            'bodyEnd' => '</div>',
            'bodyCollapsibleStart' =>
                '<div class="collapse{{attrs.class}}" role="tabpanel" aria-labelledby="{{headId}}"{{attrs}}>{{bodyStart}}',
            'bodyCollapsibleEnd' => '{{bodyEnd}}</div>',
            'footerStart' => '<div class="card-footer{{attrs.class}}"{{attrs}}>',
            'footerEnd' => '</div>'
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate',
        'collapsible' => false
    ];

    /**
     * States of the card helper (contains states of type 'group' and 'card').
     *
     * @var StackedStates
     */
    protected $_states;

    /**
     * Card counter (for collapsible groups).
     *
     * @var int
     */
    protected $_cardCount = 0;

    /**
     * Card groups counter (for card groups).
     *
     * @var int
     */
    protected $_groupCount = 0;

    public function __construct(\Cake\View\View $View, array $config = []) {
        $this->_states = new StackedStates([
            'group' => [
                'groupCardOpen' => false,
                'groupCardCount' => -1,
                'groupId' => false,
                'groupCollapsible' => true
            ],
            'card' => [
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
     * Open a card group.
     *
     * ### Options
     *
     * - `collapsible` Set to `false` if cards should not be collapsible. Default is `true`.
     * - `id` Identifier for the group. Default is automatically generated.
     * - `open` If `collapsible` is `true`, indicate the card that should be open by default.
     * Set to `false` to have no cards open. You can also indicate if a card should be open
     * in the `create()` method. Default is `0`.
     *
     * - Other attributes will be passed to the `Html::div()` method.
     *
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening HTML tag for card groups.
     *
     * @link http://getbootstrap.com/javascript/#collapse-example-accordion
     */
    public function startGroup($options = []) {
        $options += [
            'id' => 'cardGroup-'.(++$this->_groupCount),
            'collapsible' => true,
            'open' => 0,
            'templateVars' => []
        ];
        $this->_states->push('group', [
            'groupCardOpen' => $options['open'],
            'groupCardCount' => -1,
            'groupId' => $options['id'],
            'groupCollapsible' => $options['collapsible']
        ]);
        return $this->formatTemplate('cardGroupStart', [
            'attrs' => $this->templater()->formatAttributes($options, ['open', 'collapsible']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Closes a card group, closes the last card if it has not already been closed.
     *
     * @return string An HTML string containing closing tags.
     */
    public function endGroup() {
        $out = '';
        while ($this->_states->is('card')) { // cards were not closed
            $out .= $this->end();
        }
        $out .= $this->formatTemplate('cardGroupEnd', []);
        $this->_states->pop();
        return $out;
    }

    /**
     * Open a card.
     *
     * If `$title` is a string, the card header is created using `$title` as its
     * content and default options (except for the `title` options that can be specified
     * inside `$options`).
     *
     * ```php
     * echo $this->Card->create('My Card Title', ['title' => ['tag' => 'h2']]);
     * ```
     *
     * If the card header is created, the card body is automatically opened after
     * it, except if the `no-body` options is specified (see below).
     *
     * If `$title` is an array, it is used as `$options`.
     *
     * ```php
     * echo $this->Card->create(['class' => 'my-card-class']);
     * ```
     *
     * If the `create()` method is used inside a card group, the previous card is
     * automatically closed.
     *
     * ### Options
     *
     * - `collapsible` Set to `true` if the card should be collapsible. Default is fetch
     * from configuration/
     * - `body` If `$title` is a string, set to `false` to not open the body after the
     * card header. Default is `true`.
     * - `open` Indicate if the card should be open. If the card is not inside a group, the
     * default is `true`, otherwize the default is `false` and the card is open if its
     * count matches the specified value in `startGroup()` (set to `true` inside a group to
     * force the card to be open).
     * - `card-count` Card counter, can be used to override the default counter when inside
     * a group. This value is used to generate the card, header and body ID attribute.
     * - `title` Array of options for the title. Default is [].
     * - `type` Type of the card (`'default'`, `'primary'`, ...). Default is `'default'`.
     * - Other options will be passed to the `Html::div` method for creating the
     * card `<div>`.
     *
     * @param array|string $title   The card title or an array of options.
     * @param array        $options Array of options. See above.
     *
     * @return string An HTML string containing opening elements for a card.
     */
    public function create($title = null, $options = []) {

        if (is_array($title)) {
            $options = $title;
            $title   = null;
        }

        $out = '';

        // close previous card if in group
        if ($this->_states->is('card') && $this->_states->getValue('inGroup')) {
            $out .= $this->end();
        }

        $options += [
            'body' => true,
            'type' => 'default',
            'collapsible' => $this->_states->is('group') ?
                $this->_states->getValue('groupCollapsible') : $this->getConfig('collapsible'),
            'open' => !$this->_states->is('group'),
            'card-count' => $this->_cardCount,
            'title' => [],
            'templateVars' => []
        ];

        $this->_cardCount = intval($options['card-count']) + 1;

        // check open
        $open = $options['open'];
        if ($this->_states->is('group')) {
            // increment count inside
            $this->_states->setValue('groupCardCount',
                $this->_states->getValue('groupCardCount') + 1);
            $open = $open
                || $this->_states->getValue('groupCardOpen')
                    == $this->_states->getValue('groupCardCount');
        }

        $out .= $this->formatTemplate('cardStart', [
            'type' => $options['type'],
            'attrs' => $this->templater()->formatAttributes(
                $options, ['body', 'type', 'collapsible', 'open', 'card-count', 'title']),
            'templateVars' => $options['templateVars']
        ]);

        $this->_states->push('card', [
            'part' => null,
            'bodyId' => 'collapse-'.$options['card-count'],
            'headId' => 'heading-'.$options['card-count'],
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
            $out .= $this->_cleanCurrent();
            if ($options['body']) {
                $out .= $this->_createBody();
            }
        }

        return $out;
    }

    /**
     * Closes a card, cleans part that have not been closed correctly and optionaly adds a
     * footer to the card.
     *
     * If `$content` is not null, the `footer()` methods will be used to create the card
     * footer using `$content` and `$options`.
     *
     * ```php
     * echo $this->Card->end('Footer Content', ['my-class' => 'my-footer-class']);
     * ```
     *
     * @param string|null $content   Footer content, or `null`.
     * @param array       $options Array of options for the footer.
     *
     * @return string An HTML string containing closing tags.
     */
    public function end($content = null, $options = []) {
        $this->_lastCardClosed = true;
        $res = '';
        $res .= $this->_cleanCurrent();
        if ($content !== null) {
            $res .= $this->footer($content, $options);
        }
        $res .= $this->formatTemplate('cardEnd', []);
        $this->_states->pop();
        return $res;
    }

    /**
     * Cleans the current card part and return necessary HTML closing elements.
     *
     * @return string An HTML string containing closing elements.
     */
    protected function _cleanCurrent() {
        if (!$this->_states->is('card')) {
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
     * Check if the current card should be open or not.
     *
     * @return bool `true` if the current card should be open, `false` otherwize.
     */
    protected function _isOpen() {
        return $this->_states->getValue('open');
    }

    /**
     * Create or open a card header.
     *
     * ### Options
     *
     * - `title` See `header()`.
     * - `templateVars` Provide template variables for the header template.
     * - Other attributes will be assigned to the header element.
     *
     * @param string $text The card header content, or null to only open the header.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the card header or the complete card
     * header.
     */
    protected function _createHeader($title, $options = []) {
        $title = $this->_makeIcon($title, $converted);
        $options += [
            'escape' => !$converted,
            'templateVars' => []
        ];
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
        if ($title) {
            if ($options['escape']) {
                $title = h($title);
            }
            $out .= $title;
        }
        return $out;
    }

    /**
     * Create or open a card body.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the body template.
     * - Other attributes will be assigned to the body element.
     *
     * @param string|null $text The card body content, or null to only open the body.
     * @param array $options Array of options for the body `<div>`.
     *
     * @return string A formated opening tag for the card body or the complete card
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
     * Create or open a card footer.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the footer template.
     * - Other attributes will be assigned to the footer element.
     *
     * @param string $text The card footer content, or null to only open the footer.
     * @param array $options Array of options for the footer `<div>`.
     *
     * @return string A formated opening tag for the card footer or the complete card
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
     * Create or open a card header.
     *
     * If `$text` is a string, create a card header using the specified content
     * and `$options`.
     *
     * ```php
     * echo $this->Card->header('Header Content', ['class' => 'my-class']);
     * ```
     *
     * If `$text` is `null`, create a formated opening tag for a card header using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Card->header(null, ['class' => 'my-class']);
     * ```
     *
     * If `$text` is an array, used it as `$options` and create a formated opening tag for
     * a card header.
     *
     * ```php
     * echo $this->Card->header(['class' => 'my-class']);
     * ```
     *
     * You can use the `title` option to wrap the content:
     *
     * ```php
     * echo $this->Card->header('My Title', ['title' => false]);
     * echo $this->Card->header('My Title', ['title' => true]);
     * echo $this->Card->header('My <Title>', ['title' => ['tag' => 'h2', 'class' => 'my-class', 'escape' => true]]);
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
     * @return string A formated opening tag for the card header or the complete card
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
     * Create a card title.
     *
     * @param string $title Title of the card.
     * @param array $options Attributes for the title div.
     *
     * @return string The card title.
     */
    public function title($title, $options = []) {
        $options += [
            'templateVars' => []
        ];
        if ($this->_states->getValue('part') !== 'body') {
            $this->body();
        }
        return $this->formatTemplate('title', [
            'content' => $title,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Create or open a card body.
     *
     * If `$content` is a string, create a card body using the specified content and
     * `$options`.
     *
     * ```php
     * echo $this->Card->body('Card Content', ['class' => 'my-class']);
     * ```
     *
     * If `$content` is `null`, create a formated opening tag for a card body using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Card->body(null, ['class' => 'my-class']);
     * ```
     *
     * If `$content` is an array, used it as `$options` and create a formated opening tag for
     * a card body.
     *
     * ```php
     * echo $this->Card->body(['class' => 'my-class']);
     * ```
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the body template.
     * - Other attributes will be assigned to the body element.
     *
     * @param array|string $info The body content, or `null`, or an array of options.
     * `$options`.
     * @param array $options Array of options for the card body `<div>`.
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
     * Create or open a card footer.
     *
     * If `$text` is a string, create a card footer using the specified content
     * and `$options`.
     *
     * ```php
     * echo $this->Card->footer('Footer Content', ['class' => 'my-class']);
     * ```
     *
     * If `$text` is `null`, create a formated opening tag for a card footer using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Card->footer(null, ['class' => 'my-class']);
     * ```
     *
     * If `$text` is an array, used it as `$options` and create a formated opening tag for
     * a card footer.
     *
     * ```php
     * echo $this->Card->footer(['class' => 'my-class']);
     * ```
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the footer template.
     * - Other attributes will be assigned to the footer element.
     *
     * @param string|array $text The footer content, or `null`, or an array of options.
     * @param array        $options Array of options for the card footer `<div>`.
     *
     * @return string A formated opening tag for the card footer or the complete card
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
