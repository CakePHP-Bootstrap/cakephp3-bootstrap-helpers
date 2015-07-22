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

namespace Bootstrap3\View\Helper;

use Cake\View\Helper ;

class BootstrapNavbarHelper extends Helper {

    public $helpers = [
        'Html',
        'Form' => [
            'className' => 'Bootstrap3.BootstrapForm'
        ]
    ] ;

    public $autoActiveLink = false ;
    public $autoButtonLink = true ;
    
    protected $_fixed = false ;
    protected $_static = false ;
    protected $_responsive = false ;
    protected $_inverse = false ;
	protected $_fluid = false;
        
    protected $_level = 0;

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
     *  - fluid: false, true
     * 
    **/
    public function create ($brand, $options = []) {
        $this->_fixed = $this->_extractOption('fixed', $options, false) ;
        unset($options['fixed']) ;
        $this->_responsive = $this->_extractOption('responsive', $options, false) ;
        unset($options['responsive']) ;
        $this->_static = $this->_extractOption('static', $options, false) ;
        unset($options['static']) ;
        $this->_inverse = $this->_extractOption('inverse', $options, false) ;
        unset($options['inverse']) ;
		$this->_fluid = $this->_extractOption('fluid', $options, false);
		unset($options['fluid']);
        
        /** Generate options for outer div. **/
        $options = $this->addClass($options, 'navbar navbar-default') ;
        if ($this->_fixed !== false) {
            $options = $this->addClass($options, 'navbar-fixed-'.$this->fixed) ;
        }
        else if ($this->_static !== false) {
            $options = $this->addClass($options, 'navbar-static-top') ;
        }
        if ($this->_inverse !== false) {
            $options = $this->addClass($options , 'navbar-inverse') ;
        }
        
        $toggleButton = '' ;
        $rightOpen = '' ;
        if ($this->_responsive) {
            $toggleButton = $this->Html->tag('button', 
                implode('', array(
                    $this->Html->tag('span', __('Toggle navigation'), array('class' => 'sr-only')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar'))
                )),
                array(
                    'type' => 'button',
                    'class' => 'navbar-toggle collapsed',
                    'data-toggle' => 'collapse',
                    'data-target' => '.navbar-collapse'
                )
            ) ;
            $rightOpen = $this->Html->tag('div', null, ['class' => 'navbar-collapse collapse']) ;
        }

        if ($brand) {
            if (is_string($brand)) {
                $brand = $this->Html->link ($brand, '/', ['class' => 'navbar-brand', 'escape' => false]) ;
            }
            else if (is_array($brand) && array_key_exists('url', $brand)) {
                $brandOptions = $this->_extractOption ($brand, 'options', []) ;
                $brandOptions = $this->addClass ($brandOptions, 'navbar-brand') ;
                $brand = $this->Html->link ($brand['name'], $brand['url'], $brandOptions) ;
            }
            $rightOpen = $this->Html->tag('div', $toggleButton.$brand, ['class' => 'navbar-header']).$rightOpen ;
        }
        
        /** Add and return outer div openning. **/
        return $this->Html->tag('div', null, $options).$this->Html->tag('div', null, ['class' => $this->_fluid ? 'container-fluid' : 'container']).$rightOpen ;
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
    public function link ($name, $url = '', $options = [], $linkOptions = []) {
        if ($this->_level == 0 && $this->autoButtonLink) {
            $options = $this->addClass ($options, 'btn btn-default navbar-btn') ;
            return $this->Html->link ($name, $url, $options) ;
        }
        return $this->Html->tag('li', $this->Html->link ($name, $url, $linkOptions), $options) ;
    }

    public function button ($name, array $options = []) {
        $options = $this->addClass ($options, 'navbar-btn') ;
        return $this->Form->button ($name, $options) ;
    }
    
    /**
     * 
     * Add a divider to the navbar or to a menu.
     * 
    **/
    public function divider ($options = []) {
        $options = $this->addClass ($options, 'divider') ;
        $options['role'] = 'separator' ;
        return $this->Html->tag('li', '', $options) ;
    }

    /**
     * 
     * Add a header to the navbar or to a menu.
     * 
    **/
    public function header ($name, $options = []) {
        $options = $this->addClass ($options, 'dropdown-header') ;
        return $this->Html->tag('li', $name, $options) ;
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
    public function text ($text, $options = []) {
        $tag     = $this->_extractOption ($options, 'tag', 'p') ;
        $options = $this->addClass ($options, 'navbar-text') ;
        $text = preg_replace_callback ('/<a([^>]*)?>([^<]*)?<\/a>/i', function ($matches) {
            $attrs = preg_replace_callback ('/class="(.*)?"/', function ($m) {
                $cl = $this->addClass (['class' => $m[1]], 'navbar-link') ;
                return 'class="'.$cl['class'].'"' ;
            }, $matches[1], -1, $count) ;
            if ($count == 0) {
                $attrs .= ' class="navbar-link"' ;
            }
            return '<a'.$attrs.'>'.$matches[2].'</a>' ;
        }, $text);
        return $this->Html->tag($tag, $text, $options) ;
    }
    
    
    /**
     *
     * Add a serach form to the navbar.
     *
     * @param model Model for BootstrapFormHelper::searchForm method
     * @param options Options for BootstrapFormHelper::searchForm method
     *
    **/
    public function searchForm ($model = null, $options = []) {
        $align = $this->_extractOption ($options, 'align', 'left') ;
        unset ($options['align']) ;
        $options = $this->addClass($options, ['navbar-form',  'navbar-'.$align]) ;
        return $this->Form->searchForm($model, $options) ;
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
    public function beginMenu ($name = null, $url = null, $options = [], $linkOptions = [], $listOptions = []) {
        $res = '';
        if ($this->_level == 0) {
            $options = is_array($name) ? $name : [] ;
            $options = $this->addClass ($options, ['nav', 'navbar-nav']);
            $res = $this->Html->tag('ul', null, $options) ;
        }
        else {
            $linkOptions += [
                'data-toggle' => 'dropdown',
                'role' => 'button',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
                'escape' => false
            ] ;
            $link        = $this->Html->link ($name.(array_key_exists ('caret', $linkOptions) ? $linkOptions['caret'] : '<span class="caret"></span>'), $url ? $url : '#', $linkOptions);
            $options     = $this->addClass ($options, 'dropdown') ;
            $listOptions = $this->addClass ($listOptions, 'dropdown-menu') ;
            $res = $this->Html->tag ('li', null, $options).$link.$this->Html->tag ('ul', null, $listOptions);
        }
        $this->_level += 1 ;
        return $res ;
    }
    
    /**
     * 
     * End a menu.
     * 
    **/
    public function endMenu () {
        $this->_level -= 1 ;
        return ($this->_level == 1 ? '</li>' : '').'</ul>' ;
    }

    /**
     * 
     * End a navbar.
     * 
     * @param compile If true, compile the navbar and return
     *    
    **/
    public function end ($compile = false) {
        $res = '</div></div>' ;
        if ($this->_responsive) {
            $res .= '</div>' ;
        }
        return $res ;
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
    private function __compileNavBlock ($nav) {
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
            $button = $this->Html->link($menu['text'].'<span class="caret"></span>', $menu['url'] ? $menu['url'] : '#', array(
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
            $res = $this->__compileNavBlock($m) ;
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
            $res = $this->__compileNavBlock($nav) ;
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
        $this->options = $this->addClass($this->options, 'navbar navbar-default') ;
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
            $button = $this->Html->tag('button', 
                implode('', array(
                    $this->Html->tag('span', __('Toggle navigation'), array('class' => 'sr-only')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar')),
                    $this->Html->tag('span', '', array('class' => 'icon-bar'))
                )),
                array(
                    'type' => 'button',
                    'class' => 'navbar-toggle collapsed',
                    'data-toggle' => 'collapse',
                    'data-target' => '.navbar-collapse'
                )
            ) ;
            if ($this->brand !== null && $this->brand['collapse']) {
                $inner = $brand.$inner ;
            }
            $inner = $this->Html->tag('div', $inner, array('class' => 'navbar-collapse collapse')) ;
            
            $header = '' ;
            
            if ($this->brand !== null && !$this->brand['collapse']) {
                $header = $brand ;
            }
            $header = $this->Html->tag('div', $button.$header, array('class' => 'navbar-header')) ;
            $inner = $header.$inner ;
        }
        else if ($this->brand !== null) {
            $inner = $brand.$inner ;
        }
        
        /** Add container. **/
		$container_class = $this->fluid? 'container-fluid' : 'container';
        $inner = $this->Html->tag('div', $inner, array('class' => $container_class)) ;
        
        /** Add and return outer div. **/
        return $this->Html->tag('div', $inner, $this->options) ;
        
    }
    
    /**
     * 
     * Extract options from $options, returning $default if $key is not found.
     * 
    **/
    protected function _extractOption ($key, $options, $default = null) {
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
    protected function _extractValue ($options) {
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
    protected function _addToCurrent ($type, $value, $options = array()) {
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