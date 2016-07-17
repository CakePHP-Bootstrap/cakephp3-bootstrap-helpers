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

    public $helpers = [
        'Html' => [
            'className' => 'Bootstrap.BootstrapHtml'
        ]
    ];

    public $_defaultConfig = [
        'collapsible' => false
    ];

    public $current = NULL ;

    /* Protected attributes used to generate ID for collapsible panels. */
    protected $_panelCount         = 0;
    protected $_bodyId      = null;
    protected $_headId      = null;

    /* Protected attribute used to generate group ID. */
    protected $_groupCount         = 0;
    protected $_groupId            = false;

    protected $_groupPanelCount = 0;
    protected $_groupPanelOpen  = false;

    /* Attribute set to true when in group. */
    protected $_groupInGroup = false;

    protected $_lastPanelClosed    = true;
    protected $_autoCloseOnCreate  = false;

    protected $_collapsible = false;

    public function startGroup($options = []) {
        $options += [
            'class'                => '',
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
     *
     * Create a Twitter Bootstrap like panel.
     *
     * @param array|string $title If array, works as $options, otherwize used as
     *                            the panel title.
     * @param array $options Options for the main div of the panel.
     *
     * Extra options (useless if $title not specified) :
     *     - no-body: Do not open the body after the create (default false)
     **/
    public function create($title = null, $options = []) {

        if (is_array($title)) {
            $options = $title;
            $title   = null;
        }

        $options += [
            'no-body'     => false,
            'type'        => 'default',
            'collapsible' => $this->config('collapsible'),
            'open'        => !$this->_groupInGroup
        ];

        $nobody = $options['no-body'];
        $type   = $options['type'];
        $open   = $options['open'];
        $this->_collapsible = $options['collapsible'];
        unset ($options['no-body'], $options['collapsible'],
               $options['type'], $options['open']);

        $options = $this->addClass($options, ['panel', 'panel-'.$type]);

        if ($this->_collapsible) {
            $this->_headId = 'heading-'.($this->_panelCount);
            $this->_bodyId = 'collapse-'.($this->_panelCount);
            $this->_panelCount++;
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
     *
     * End a panel. If $title is not null, the PanelHelper::footer functions
     * is called with $title and $options arguments.
     *
     * @param string|null $buttons
     * @param array $options
     *
     **/
    public function end ($title = null, $options = []) {
        $this->_lastPanelClosed = true;
        $res = '' ;
        $res .= $this->_cleanCurrent();
        if ($title !== null) {
            $res .= $this->footer($title, $options) ;
        }
        $res .= '</div>' ;
        return $res ;
    }

    protected function _cleanCurrent () {
        $res = '';
        if ($this->current) {
            $res .= '</div>';
            if ($this->_collapsible && $this->current == 'body') {
                $res .= '</div>';
            }
            $this->current = null ;
        }
        return $res;
    }

    /**
     *
     * Return true if the current panel should be open (only for collapsible).
     *
     * @return true if the current panel should be open, false otherwize.
     *
     **/
    protected function _isOpen () {
        return (is_int($this->_groupPanelOpen)
                && $this->_groupPanelOpen === $this->_groupPanelCount)
            || $this->_groupPanelOpen === $this->_bodyId;
    }

    protected function _createHeader ($title, $options = [], $titleOptions = []) {
        if (empty($titleOptions)) {
            $titleOptions = $options['title'] ;
        }
        unset ($options['title']);
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
            $this->current = 'body';
        }
        return $body;
    }

    protected function _createFooter ($text = null, $options = []) {
        $options = $this->addClass($options, 'panel-footer');
        return $this->_cleanCurrent().$this->Html->tag('div', $text, $options) ;
    }

    /**
     *
     * Create / Start the header. If $info is specified as a string, create and return the
     * whole header, otherwize only open the header.
     *
     * @param array|string $info If string, use as the panel title, otherwize works as
     *                           $options.
     * @param array $options Options for the header div.
     *
     * Special option (if $info is string):
     *     - close: Add the 'close' button in the header (default true).
     *
     **/
    public function header ($info = null, $options = []) {
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
     *
     * Create / Start the body. If $info is not null, it is used as the body content, otherwize
     * start the body div.
     *
     * @param array|string $info If string, use as the body content, otherwize works
     *                           as $options.
     * @param array $options Options for the footer div.
     *
     *
     **/
    public function body ($info = null, $options = []) {
        if (is_array($info)) {
            $options = $info;
            $info    = null;
        }
        return $this->_createBody($info, $options);
    }

    protected function _isAssociativeArray ($array) {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     *
     * Create / Start the footer. If $buttons is specified as an associative arrays or as null,
     * start the footer, otherwize create the footer with the specified text.
     *
     * @param string $text Use as the footer content.
     * @param array $options Options for the footer div.
     *
     **/
    public function footer ($text = null, $options = []) {
        if (is_array($text)) {
            $options = $text;
            $text    = null;
        }
        return $this->_createFooter($text, $options) ;
    }

}

?>
