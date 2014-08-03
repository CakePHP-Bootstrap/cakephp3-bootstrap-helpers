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

namespace Bootstrap3\View\Helper;

use Cake\View\Helper;

class BootstrapModalHelper extends Helper {

    public $helpers = ['Html'];

    public $current = NULL ;
    
    protected function _extractOption ($key, $options, $default = null) {
        if (isset($options[$key])) {
            return $options[$key] ;
        }
        return $default ;
    }
    
    /**
     * 
     * Create a Twitter Bootstrap like modal. 
     *
     * @param array|string $title If array, works as $options, otherwize used as the modal title.
     * @param array $options Options for the main div of the modal. 
     *
     * Extra options (useless if $title not specified) :
     *     - close: Add close buttons to header (default true)
     *     - no-body: Do not open the body after the create (default false)
    **/
    public function create($title = null, $options = array()) {

        if (is_array($title)) {
            $options = $title ;
        }
        $close = $this->_extractOption('close', $options, true);
        unset ($options['close']) ;
        $nobody = $this->_extractOption('no-body', $options, false);
        unset ($options['no-body']) ;
        $options['tabindex'] = $this->_extractOption('tabindex', $options, -1);
        $options['role'] = $this->_extractOption('role', $options, 'dialog');
        $options['aria-hidden'] = $this->_extractOption('aria-hidden', $options, 'true');
        if (isset($options['id'])) {
            $this->currentId = $options['id'] ;
            $options['aria-labbeledby'] = $this->currentId.'Label' ;
        }
		$res = $this->Html->div('modal fade '.$this->_extractOption('class', $options, ''), NULL, $options).$this->Html->div('modal-dialog').$this->Html->div('modal-content');
        if (is_string($title) && $title) {
            $res .= $this->_createHeader($title, array('close' => $close)) ;
            if (!$nobody) {
                $res .= $this->_startPart('body');
            }
        }
        return $res ;
	}
    	
    /**
     * 
     * End a modal. If $buttons is not null, the ModalHelper::footer functions is called with $buttons and $options arguments.
     *
     * @param array|null $buttons
     * @param array $options
     * 
    **/
    public function end ($buttons = NULL, $options = array()) {
        $res = '' ;
        if ($this->current != NULL) {
            $this->current = NULL ;
            $res .= $this->_endPart();
        }
        if ($buttons !== NULL) {
            $res .= $this->footer($buttons, $options) ;
        }
        $res .= '</div></div></div>' ;
		return $res ;
    }

    protected function _cleanCurrent () {
        if ($this->current) {
            $this->current = NULL ;
            return $this->_endPart();
        }
        return '' ;
    }

    protected function _createHeader ($title, $options = array()) {
        $close = $this->_extractOption('close', $options, true);
        unset($options['close']) ;
        if ($close) {
            $button = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' ;
        }
        else {
            $button = '' ;
        }
        return $this->_cleanCurrent().$this->Html->div('modal-header '.$this->_extractOption('class', $options, ''), 
            $button.$this->Html->tag('h4', $title, array('class' => 'modal-title', 'id' => $this->currentId ? $this->currentId.'Label' : false)), 
            $options
        ) ; 
    }

    protected function _createBody ($text, $options = array()) {
        return $this->_cleanCurrent().$this->Html->div('modal-body '.$this->_extractOption('class', $options, ''), $text, $options) ; 
    }

    protected function _createFooter ($buttons = NULL, $options = array()) {
        if ($buttons == NULL) {
            $close = $this->_extractOption('close', $options, true);
            unset($options['close']) ;
            if ($close) {
                $buttons = '<button type="button" class="btn btn-default" data-dismiss="modal">'.__('Close').'</button>' ;
            }
            else {
                $buttons = '' ;
            }
        }
        return $this->_cleanCurrent().$this->Html->div('modal-footer '.$this->_extractOption('class', $options, ''), $buttons, $options) ; 
    }
    
    protected function _startPart ($part, $options = array()) {
        $res = '' ;
        if ($this->current != NULL) {
            $res = $this->_endPart () ;
        }
        $this->current = $part ;
        return $res.$this->Html->div('modal-'.$part.' '.$this->_extractOption('class', $options, ''), NULL, $options) ;
    }

    protected function _endPart () {
        return '</div>' ;
    }

    /**
     *
     * Create / Start the header. If $info is specified as a string, create and return the whole header, otherwize only open the header.
     * 
     * @param array|string $info If string, use as the modal title, otherwize works as $options.
     * @param array $options Options for the header div.
     * 
     * Special option (if $info is string):
     *     - close: Add the 'close' button in the header (default true).
     *
    **/
    public function header ($info = NULL, $options = array()) {
        if (is_string($info)) {
            return $this->_createHeader($info, $options) ;
        }
        return $this->_startPart('header', is_array($info) ? $info : $options) ;
    }

    /**
     *
     * Create / Start the body. If $info is not null, it is used as the body content, otherwize start the body div.
     * 
     * @param array|string $info If string, use as the body content, otherwize works as $options.
     * @param array $options Options for the footer div.
     * 
     *
    **/
    public function body ($info = NULL, $options = array()) {
        if (is_string($info)) {
            if ($this->current != NULL) {
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
     * Create / Start the footer. If $buttons is specified as an associative arrays or as null, start the footer, otherwize create the footer with the specified buttons.
     * 
     * @param array|string $buttons If string, use as the footer content, if list, concatenate values in the list as content (use for buttons purpose), otherwize works as $options.
     * @param array $options Options for the footer div.
     * 
     * Special option (if $buttons is NOT NULL but empty):
     *     - close: Add the 'close' button to the footer (default true).
     *
    **/
    public function footer ($buttons = [], $options = []) {
        if ($buttons === NULL || (!empty($buttons) && $this->_isAssociativeArray($buttons))) {
            return $this->_startPart('footer', $buttons === NULL ? $options : $buttons) ;
        }
        if (empty($buttons)) {
            return $this->_createFooter(NULL, $options) ;
        }
        return $this->_createFooter(is_string($buttons) ? $buttons : implode('', $buttons), $options) ;
    }

}

?>
