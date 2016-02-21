<?php

/**
* Bootstrap Modal Helper
*
*
* PHP 5
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
*
* @copyright Copyright (c) Mikaël Capelle (http://mikael-capelle.fr)
* @link http://mikael-capelle.fr
* @package app.View.Helper
* @since Apache v2
* @license http://www.apache.org/licenses/LICENSE-2.0
*/

namespace Bootstrap\View\Helper;

use Cake\View\Helper;

class BootstrapPanelHelper extends Helper {

    use BootstrapTrait ;

    public $helpers = ['Html'];

    public $current = NULL ;

    /* Protected attributes used to generate ID for collapsible panels. */
    protected $_panelCount         = 0;
    protected $_bodyId      = null;
    protected $_headId      = null;

    /* Default value for "collapsible" option. */
    protected $_defaultCollapsible = false;

    /* Protected attribute used to generate group ID. */
    protected $_groupCount         = 0;
    protected $_groupId            = false;

    protected $_groupPanelCount = 0;
    protected $_groupPanelOpen  = 0;

    protected $_lastPanelClosed    = true;
    protected $_autoCloseOnCreate  = false;

    protected $_collapsible = false;

    public function startGroup($options = []) {
        $options += [
            'class' => '',
            'role'  => 'tablist',
            'aria-multiselectable' => true,
            'id'   => 'panelGroup-'.(++$this->_groupCount),
            'collapsible' => true,
            'open' => 0
        ];
        $this->_defaultCollapsible = $options['collapsible'];
        $this->_autoCloseOnCreate  = true;
        $this->_lastPanelClosed    = true;
        $this->_groupPanelCount    = -1;
        $this->_groupPanelOpen     = $options['open'];
        $this->_groupId = $options['id'];
        $options = $this->addClass($options, 'panel-group');
        $class   = $options['class'];
        unset($options['class'], $options['open'], $options['collapsible']);
        return $this->Html->div($class, null, $options);
    }

    public function endGroup() {
        $this->_defaultCollapsible = false;
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
     * @param array|string $title If array, works as $options, otherwize used as the panel title.
     * @param array $options Options for the main div of the panel.
     *
     * Extra options (useless if $title not specified) :
     *     - no-body: Do not open the body after the create (default false)
     **/
    public function create($title = null, $options = []) {

        if (is_array($title)) {
            $options = $title;
        }

        $options += [
            'no-body'     => false,
            'type'        => 'default',
            'collapsible' => $this->_defaultCollapsible
        ];

        $nobody = $options['no-body'];
        $type   = $options['type'];
        $this->_collapsible = $options['collapsible'];
        unset ($options['no-body'], $options['collapsible'], $options['type']);

        $options = $this->addClass($options, ['panel', 'panel-'.$type]);

        if ($this->_collapsible) {
            $this->_headId = 'heading-'.($this->_panelCount);
            $this->_bodyId = 'collapse-'.($this->_panelCount);
            $this->_panelCount++;
        }

        $class   = $options['class'];
        unset ($options['class']);

        $out = '';

        if ($this->_autoCloseOnCreate && !$this->_lastPanelClosed) {
            $out .= $this->end();
        }
        $this->_lastPanelClosed = false;

        /* Increment panel counter for the current group. */
        $this->_groupPanelCount++;

        $out .= $this->Html->div($class, null, $options);
        if (is_string($title) && $title) {
            $out .= $this->_createHeader($title, [
                'title' => isset($options['title']) ? $options['title'] : true
            ]) ;
            if (!$nobody) {
                $out .= $this->_startPart('body');
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
            $res = $this->_endPart();
            $this->current = NULL ;
        }
        return $res;
    }

    protected function _createHeader ($title, $options = [], $titleOptions = []) {
        if (empty($titleOptions)) {
            $titleOptions = $options['title'] ;
        }
        unset ($options['title']);
        $options = $this->addClass($options, 'panel-heading');
        $class   = $options['class'];
        unset ($options['class']);
        if ($this->_collapsible) {
            $options += [
                'role' => 'tab',
                'id'   => $this->_headId
            ];
            $this->_headId = $options['id'];
            $title = $this->Html->link($title, '#'.$this->_bodyId, [
                'data-toggle'   => 'collapse',
                'data-parent'   => $this->_groupId ? '#'.$this->_groupId : false,
                'aria-expanded' => true,
                'aria-controls' => '#'.$this->_bodyId
            ]);
        }
        if ($titleOptions !== false) {
            if (!is_array($titleOptions)) {
                $titleOptions = [];
            }
            $titleOptions += ['tag' => 'h4'];
            $titleOptions = $this->addClass($titleOptions, 'panel-title');
            $tag = $titleOptions['tag'];
            unset($titleOptions['tag']);
            $title = $titleOptions ? $this->Html->tag($tag, $title, $titleOptions) : $title;
        }
        return $this->_cleanCurrent().$this->Html->div($class, $title, $options);
    }

    protected function _createBody ($text, $options = []) {
        $options = $this->addClass($options, 'panel-body');
        $class   = $options['class'];
        unset ($options['class']);
        $body = $this->Html->div($class, $text, $options);
        if ($this->_collapsible) {
            $open = ((is_int($this->_groupPanelOpen)
                     && $this->_groupPanelOpen == $this->_groupPanelCount)
                     || $this->_groupPanelOpen == $this->_bodyId) ? ' in' : '';
            $body = $this->Html->div('panel-collapse collapse'.$open, $body, [
                'role' => 'tabpanel',
                'aria-labelledby' => $this->_headId,
                'id' => $this->_bodyId
            ]);
        }
        return $this->_cleanCurrent().$body ;
    }

    protected function _createFooter ($text = null, $options = []) {
        $options = $this->addClass($options, 'panel-footer');
        $class   = $options['class'];
        unset ($options['class']);
        return $this->_cleanCurrent().$this->Html->div($class, $text, $options) ;
    }

    protected function _startPart ($part, $options = []) {
        $res = '' ;
        if ($this->current != null) {
            $res = $this->_endPart () ;
        }
        $this->current = $part ;
        if ($this->_collapsible && $this->current == 'body') {
            $open = ((is_int($this->_groupPanelOpen)
                      && $this->_groupPanelOpen === $this->_groupPanelCount)
                     || $this->_groupPanelOpen === $this->_bodyId) ? ' in' : '';
            $res .= $this->Html->div('panel-collapse collapse'.$open, null, [
                'role' => 'tabpanel',
                'aria-labelledby' => $this->_headId,
                'id' => $this->_bodyId
            ]);
        }
        return $res.$this->Html->div('panel-'.$part.' '.$this->_extractOption('class',
                                                                              $options, ''),
                                     null, $options) ;
    }

    protected function _endPart () {
        if ($this->_collapsible && $this->current == 'body') {
            return '</div></div>';
        }
        return '</div>' ;
    }

    /**
     *
     * Create / Start the header. If $info is specified as a string, create and return the
     * whole header, otherwize only open the header.
     *
     * @param array|string $info If string, use as the panel title, otherwize works as $options.
     * @param array $options Options for the header div.
     *
     * Special option (if $info is string):
     *     - close: Add the 'close' button in the header (default true).
     *
     **/
    public function header ($info = null, $options = []) {
        $options += [
            'title' => true
        ];
        if (is_string($info)) {
            return $this->_createHeader($info, $options) ;
        }
        return $this->_startPart('header', is_array($info) ? $info : $options) ;
    }

    /**
     *
     * Create / Start the body. If $info is not null, it is used as the body content, otherwize
     * start the body div.
     *
     * @param array|string $info If string, use as the body content, otherwize works as $options.
     * @param array $options Options for the footer div.
     *
     *
     **/
    public function body ($info = null, $options = []) {
        if (is_string($info)) {
            if ($this->current != null) {
                $this->_endPart() ;
            }
            return $this->_createBody($info, $options) ;
        }
        return $this->_startPart('body', is_array($info) ? $info : $options) ;
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
        if (is_string($text)) {
            return $this->_createFooter($text, $options) ;
        }
        return $this->_startPart('footer', is_array($text) ? $text : $options);
    }

}

?>
