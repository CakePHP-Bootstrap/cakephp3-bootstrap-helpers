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
            $options = $title ;
        }

        $nobody = $this->_extractOption('no-body', $options, false);
        unset ($options['no-body']);
        $type   = $this->_extractOption('type', $options, 'default');
        unset ($options['type']);

        $options = $this->addClass($options, ['panel', 'panel-'.$type]);
        $class   = $options['class'];
        unset ($options['class']);

        $res = $this->Html->div($class, null, $options);
        if (is_string($title) && $title) {
            $res .= $this->_createHeader($title, []) ;
            if (!$nobody) {
                $res .= $this->_startPart('body');
            }
        }
        return $res ;
    }

    /**
     *
     * End a panel. If $title is not null, the ModalHelper::footer functions
     * is called with $title and $options arguments.
     *
     * @param string|null $buttons
     * @param array $options
     *
     **/
    public function end ($title = null, $options = []) {
        $res = '' ;
        if ($this->current != null) {
            $this->current = null ;
            $res .= $this->_endPart();
        }
        if ($title !== null) {
            $res .= $this->footer($title, $options) ;
        }
        $res .= '</div>' ;
        return $res ;
    }

    protected function _cleanCurrent () {
        if ($this->current) {
            $this->current = NULL ;
            return $this->_endPart();
        }
        return '' ;
    }

    protected function _createHeader ($title, $options = [], $titleOptions = []) {
        $options += [
            '_title' => []
        ];
        if (empty($titleOptions))
            $titleOptions = $options['_title'];
        unset ($options['_title']);
        $options = $this->addClass($options, 'panel-heading');
        $class   = $options['class'];
        unset ($options['class']);
        $titleOptions = $this->addClass($titleOptions, 'panel-title');
        return $this->_cleanCurrent().$this->Html->div($class,
                                                       $this->Html->tag('h3', $title,
                                                                        $titleOptions),
                                                       $options
        ) ;
    }

    protected function _createBody ($text, $options = []) {
        $options = $this->addClass($options, 'panel-body');
        $class   = $options['class'];
        unset ($options['class']);
        return $this->_cleanCurrent().$this->Html->div($class, $text, $options) ;
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
        return $res.$this->Html->div('panel-'.$part.' '.$this->_extractOption('class',
                                                                              $options, ''),
                                     null, $options) ;
    }

    protected function _endPart () {
        return '</div>' ;
    }

    /**
     *
     * Create / Start the header. If $info is specified as a string, create and return the
     * whole header, otherwize only open the header.
     *
     * @param array|string $info If string, use as the modal title, otherwize works as $options.
     * @param array $options Options for the header div.
     *
     * Special option (if $info is string):
     *     - close: Add the 'close' button in the header (default true).
     *
     **/
    public function header ($info = null, $options = []) {
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
    public function footer ($text = "", $options = []) {
        return $this->_createFooter($text, $options) ;
    }

}

?>
