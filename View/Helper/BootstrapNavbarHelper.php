<?php

/**
* Bootstrap Navbar Helper
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

App::import('Helper', 'Html') ;
App::import('Routing', 'Router') ;

class BootstrapNavbarHelper extends AppHelper {

    public $helpers = array('Html') ;
    
    private $options = array() ;
    private $fixed = false ;
    private $static = false ;
    private $responsive = false ;
    private $inverse = false ;
    
    /** Specify if we are currently in a submenu or not. If in submenu,
    this must be an array like: array('name' => '', 'url' => '', 'menu' => array()). **/
    private $currentMenu = null ;
    /** Same but for hover menu. **/
    private $currentSubMenu = null ;
    
    private $brand = null ;
    private $navs = array () ;
    
    /**
     * 
     * Create a new navbar.
     * 
     * @param options Options passed to tag method for outer navbar div
     * 
     * Extra options:
     *  - fixed: false, 'top', 'bottom'
     *  - static: false, true (useless if fixed != false)
     *  - responsive: false, true
     *  - inverse: false, true
     * 
    **/
    public function create ($options = array()) {
        $this->fixed = $this->_extractOption('fixed', $options, false) ;
        unset($options['fixed']) ;
        $this->responsive = $this->_extractOption('responsive', $options, false) ;
        unset($options['responsive']) ;
        $this->static = $this->_extractOption('static', $options, false) ;
        unset($options['static']) ;
        $this->inverse = $this->_extractOption('inverse', $options, false) ;
        unset($options['inverse']) ;
        $this->options = $options ;
    }
    
    /**
     * 
     * Create the brand link of the navbar.
     * 
     * @param name The brand link text
     * @param url The brand link URL (default '/')
     * @param collapse true if you want the brand to be collapsed 
     *      with responsive design (default false)
     * @param options Options passed to link method
     *     
    **/
    public function brand ($name, $url = '/', $collapse = false, $options = array()) {
        $this->brand = array(
            'text' => $name,
            'url' => $url,
            'collapse' => $collapse,
            'options' => $options
        ) ;
    }
    
    /**
     * 
     * Functions below accept following options:
     * 
     *   - disabled (default false)
     *   - active (default auto)
     *   - pull (default auto)
     * 
    **/
    
    /**
     * 
     * Add a link to the navbar or to a menu.
     * 
     * @param name The link text
     * @param url The link URL
     * @param options Options passed to link method (+ extra options, see above)
     *     
    **/
    public function link ($name, $url, $options = array()) {
        $value = array(
            'text' => $name,
            'url' => $url
        ) ;
        $this->_addToCurrent('link', $value, $options) ;
    }
    
    /**
     * 
     * Add a divider to the navbar or to a menu.
     * 
    **/
    public function divider () {
        $this->_addToCurrent('divider', array(), array()) ;
    }

    /**
     * 
     * Add a text to the navbar or to a menu.
     * 
     * @param text The text message
     * @param options Options passed to the tag method (+ extra options, see above)
     * 
     * Extra options:
     *  - wrap The HTML tag to use (default p)
     * 
    **/
    public function text ($text, $options = array()) {
        $tag = $this->_extractOption('wrap', $options, 'p') ;
        unset($options['wrap']) ;
        $value = array(
            'wrap' => $tag,
            'text' => $text,
        ) ;
        $this->_addToCurrent('text', $value, $options) ;
    }
    
    /**
     *
     * Add a HTML block to the navbar.
     *
     * @param block The HTML block to add
     *
     * Extra options:
     *  - list true/false (default true), specify if block should be wrap in a "li" tag, only
     *      work on main nav (in submenu, block are always wrapped in a li tag)
     *
    **/
    public function block ($block, $options = array()) {
        $list = $this->_extractOption('list', $options, true) ;
        unset ($options['list']) ;
        $value = array(
            'text' => $block,
            'list' => $list
        ) ;
        $this->_addToCurrent('block', $value, $options) ;
    }
    
    /**
     *
     * Add a serach form to the navbar.
     *
     * @param block The HTML block to add
     * @param options
     *
     * Extra options:
     *  - form Extra options for BootstrapFormHelper::searchForm options
     *  - pull left/right (default left)
     *  - model Model for BootstrapFormHelper::searchForm method
     *
    **/
    public function searchForm ($options = array()) {
        App::import('Helper', 'BootstrapForm') ;
        $bootFormHelper = new BootstrapFormHelper($this->_View);
        $formOptions = $this->_extractOption('form', $options, array()) ;
        unset($formOptions['form']) ;
        $pull = $this->_extractOption('pull', $options, 'left') ;
        unset($options['pull']) ;
        $model = $this->_extractOption('model', $options, null) ;
        unset($options['model']) ;
        $formOptions = $this->addClass($formOptions, 'navbar-form pull-'.$pull) ;
        $this->block($bootFormHelper->searchForm($model, $formOptions), array('list' => false)) ;
    }
    
    /**
     * 
     * Start a new menu, 2 levels: If not in submenu, create a dropdown menu,
     * oterwize create hover menu.
     * 
     * @param name The name of the menu
     * @param url A URL for the menu (default null)
     * @param options Options passed to the tag method (+ extra options, see above)
     *   
    **/
    public function beginMenu ($name, $url = null, $options = array()) {
        $default = array(
            'type' => 'menu',
            'text' => $name,
            'url' => $url,
            'menu' => array()
        ) ;
        $value = array_merge($this->_extractValue($options), $default) ;
        if ($this->currentMenu === null) {
            $this->currentMenu = $value ;
        }
        else if ($this->currentSubMenu === null) {
            $value['type'] = 'smenu' ;
            $this->currentSubMenu = $value ;
        }
    }
    
    /**
     * 
     * End a menu.
     * 
    **/
    public function endMenu () {
        if ($this->currentSubMenu !== null) {
            $this->currentMenu['menu'][] = $this->currentSubMenu ;
            $this->currentSubMenu = null ;
        }
        else if ($this->currentMenu !== null) {
            $this->navs[] = $this->currentMenu ;
            $this->currentMenu = null ;
        }
    }

    /**
     * 
     * End a navbar.
     * 
     * @param compile If true, compile the navbar and return
     *    
    **/
    public function end ($compile = false) {
    
        if ($compile) {
            return $this->compile () ;
        }
    }
    
    /**
     * 
     * Compile a navigation block.
     * 
     * @param nav Array (type, active, pull, disabled, options, ...)
     * 
     * @return array(
     *      inner => Inner HTML for li tag
     *      class => Extra class for li tag
     *      active => Active element
     *      disabled => disabled element
     * )
     * 
    **/
    private function compileNavBlock ($nav) {
        $inner = '' ;
        $class = '' ;
        switch ($nav['type']) {
        case 'text':
            $nav['options'] = $this->addClass($nav['options'], 'navbar-text') ;
            $inner = $this->Html->tag($nav['wrap'], $nav['text'], $nav['options']) ;
        break ;
        case 'link':
            $active = $nav['active'] === 'auto' ? 
                Router::url() === Router::normalize($nav['url']) : $nav['active'] ;
            $disabled = $nav['disabled'] ;
            $inner = $this->Html->link($nav['text'], $nav['url'], $nav['options']) ;
        break ;
        case 'menu':
        case 'smenu':
            $res = $this->compileMenu($nav) ;
            $inner = $res['inner'] ;
            $active = $nav['active'] === 'auto' ? $res['active'] : $nav['active'] ;
            $disabled = $nav['disabled'] ;
            $class = $res['class'];
        break ;
        case 'block':
            $inner = $nav['text'] ;
            break ;
        case 'divider':
            $class = 'divider' ;
        break ;
        }
        return array(
            'inner' => $inner,
            'class' => $class,
            'active' => isset($active) && $active,
            'disabled' => isset($disabled) && $disabled
        ) ;
    }
    
    /**
     * 
     * Compile a menu.
     * 
     * @param menu array(type, pull, url, text, menu)
     * 
     * @return array(
     *      inner => Inner HTML for li tag
     *      class => Extra class for li tag
     *      active => Active element
     *      disabled => disabled element
     * )
     * 
    **/
    private function compileMenu ($menu) {
        if ($menu['type'] === 'menu') {
            $button = $this->Html->link($menu['text'].'<b class="caret"></b>', $menu['url'] ? $menu['url'] : '#', array(
                'class' => 'dropdown-toggle',
                'data-toggle' => 'dropdown',
                'escape' => false
            )) ;
        }
        else {
            $button = $this->Html->link($menu['text'], $menu['url'] ? $menu['url'] : '#', array(
                'tabindex' => -1
            )) ;
        }
        $active = false ;
        $link = array() ;
        foreach ($menu['menu'] as $m) {
            $res = $this->compileNavBlock($m) ;
            if ($res['active']) {
                $active = true ;
                $res = $this->addClass($res, 'active') ;
            }
            $link[] = $this->Html->tag('li', $res['inner'], $res['class'] ? array('class' => $res['class']) : array()) ;
        }
        $list = $this->Html->tag('ul', implode('', $link), array(
            'class' => 'dropdown-menu'
        )) ;
        $class = ($menu['type'] === 'menu') ? 'dropdown' : 'dropdown-submenu' ;
        if ($menu['pull'] !== 'auto') {
            $class .= ' pull-'.$menu['pull'] ;
        }
        return array(
            'active' => $active, 
            'inner' => $button.$list,
            'class' => $class,
            'disabled' => $menu['disabled']
        ) ;
    }
    
    /**
     * 
     * Compile and returns the current navbar.
     * 
     * @return The navbar (HTML string)
     *     
    **/
    public function compile () {
        $htmls = array() ;
        $ul = false ;
        foreach ($this->navs as $nav) {
            /* Extra check for block... */
            if ($nav['type'] === 'block' && $nav['list'] === false) {
                if ($ul) {
                    $htmls[] = '</ul>' ;
                    $ul = false ;
                }
                $htmls[] = $nav['text'] ;
                continue ;
            }
            if ($ul && $nav['pull'] != 'auto' && $nav['pull'] != $ul) {
                $htmls[] = '</ul>' ;
                $ul = false ;
            }
            if (!$ul && $nav['pull'] === 'auto') {
                $ul = 'left' ;
                $htmls[] = '<ul class="nav navbar-nav">' ;
            }
            if (!$ul && $nav['pull'] !== 'auto') {
                $ul = $nav['pull'] ;
                $htmls[] = '<ul class="nav navbar-nav pull-'.$nav['pull'].'">' ;
            }
            $res = $this->compileNavBlock($nav) ;
            $options = array('class' => $res['class']) ;
            if ($res['active']) {
                $options = $this->addClass($options, 'active') ;
            }
            if ($res['disabled']) {
                $options = $this->addClass($options, 'disabled') ;
            }
            $htmls[] = $this->Html->tag('li', $res['inner'], $options) ;
        }
        if ($ul) {
            $ul = false ;
            $htmls[] = '</ul>' ;
        }
        
        /** Generate options for outer div. **/
        $this->options = $this->addClass($this->options, 'navbar') ;
        if ($this->fixed !== false) {
            $this->options = $this->addClass($this->options, 'navbar-fixed-'.$this->fixed) ;
        }
        else if ($this->static !== false) {
            $this->options = $this->addClass($this->options, 'navbar-static-top') ;
        }
        if ($this->inverse !== false) {
            $this->options = $this->addClass($this->options , 'navbar-inverse') ;
        }
        
        $inner = '' ;
        
        $brand = $this->brand !== null ? 
            $this->Html->link($this->brand['text'], $this->brand['url'], array('class' => 'navbar-brand')) : null ;
        $inner = implode('', $htmls) ;
        
        if ($this->responsive) {
            $button = $this->Html->tag('a', 
                implode('', array(
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar'))
                )),
                array(
                    'class' => 'btn btn-navbar',
                    'data-toggle' => 'collapse',
                    'data-target' => '.nav-collapse'
                )
            ) ;
            if ($this->brand !== null && $this->brand['collapse']) {
                $inner = $brand.$inner ;
            }
            $inner = $this->Html->tag('div', $inner, array('class' => 'nav-collapse collapse')) ;
            if ($this->brand !== null && !$this->brand['collapse']) {
                $inner = $brand.$inner ;
            }
            $inner = $button.$inner ;
        }
        else if ($this->brand !== null) {
            $inner = $brand.$inner ;
        }
        
        /** Add container. **/
        $inner = $this->Html->tag('div', $inner, array('class' => 'container')) ;
        
        /** Add inner. **/
        $inner = $this->Html->tag('div', $inner, array('class' => 'navbar-inner')) ;
                
        /** Add and return outer div. **/
        return $this->Html->tag('div', $inner, $this->options) ;
        
    }
    
    /**
     * 
     * Extract options from $options, returning $default if $key is not found.
     * 
    **/
    private function _extractOption ($key, $options, $default = null) {
        if (isset($options[$key])) {
            return $options[$key] ;
        }
        return $default ;
    }
    
    /**
     * 
     * Extract navbar values from $options.
     * 
    **/
    private function _extractValue ($options) {
        $value = array () ;
        $value['pull'] = $this->_extractOption('pull', $options, 'auto') ;
        unset ($options['pull']) ;
        $value['disabled'] = $this->_extractOption('disabled', $options, false) ;
        unset ($options['disabled']) ;
        $value['active'] = $this->_extractOption('disabled', $options, 'auto') ;
        unset ($options['active']) ;
        $value['options'] = $options ;
        return $value ;
    }
    
    /**
     * 
     * Add navbar block to current nav (navs, dropdownMenu, hoverMenu).
     * 
    **/
    private function _addToCurrent ($type, $value, $options = array()) {
        $value = array_merge($this->_extractValue($options), $value) ;
        $value['type'] = $type ;
        if ($this->currentSubMenu !== null) {
            $this->currentSubMenu['menu'][] = $value ;
        }
        else if ($this->currentMenu !== null) {
            $this->currentMenu['menu'][] = $value ;
        }
        else {
            $this->navs[] = $value ;
        }
    }
    
        
}

?>
