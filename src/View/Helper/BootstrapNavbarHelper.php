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

namespace Bootstrap\View\Helper;

use Cake\View\Helper ;
use Cake\Routing\Router;

class BootstrapNavbarHelper extends Helper {

    use BootstrapTrait ;

    public $helpers = [
        'Html',
        'Form' => [
            'className' => 'Bootstrap.BootstrapForm'
        ]
    ] ;

    /**
     * Automatic detection of active link (class="active").
     *
     * @var bool
     */
    public $autoActiveLink = true ;
    
    /**
     * Automatic button link when not in a menu.
     *
     * @var bool
     */
    public $autoButtonLink = true ;
    
    protected $_fixed = false ;
    protected $_static = false ;
    protected $_responsive = false ;
    protected $_inverse = false ;
    protected $_fluid = false;
        
    /**
     * Menu level (0 = out of menu, 1 = main horizontal menu, 2 = dropdown menu).
     *
     * @var int
     */
    protected $_level = 0;

    /**
     * Adds the given class to the element options
     *
     * @param array $options Array options/attributes to add a class to
     * @param string|array $class The class name being added.
     * @param string $key the key to use for class.
     * @return array Array of options with $key set.
    **/
    public function addClass(array $options = [], $class = null, $key = 'class') {
        if (is_array($class)) {
            $class = implode(' ', array_unique(array_map('trim', $class))) ;
        }
        if (isset($options[$key])) {
            $optClass = $options[$key];
            if (is_array($optClass)) {
                $optClass = trim(implode(' ', array_unique(array_map('trim', $optClass))));
            }
        }
        if (isset($optClass) && $optClass) {
            $options[$key] = $optClass.' '.$class ;
        }
        else {
            $options[$key] = $class ;
        }
        return $options ;
    }

    /**
     * 
     * Create a new navbar.
     * 
     * @param $brand 
     * @param options Options passed to tag method for outer navbar div
     * 
     * Extra options:
     *  - fixed: false, 'top', 'bottom'
     *  - static: false, true (useless if fixed != false)
     *  - responsive: false, true (if true, a toggle button will be added)
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
            $options = $this->addClass($options, 'navbar-fixed-'.$this->_fixed) ;
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
     * Add a link to the navbar or to a menu.
     * 
     * @param name        The link text
     * @param url         The link URL
     * @param options     Options passed to the tag method (for the li tag)
     * @param linkOptions Options passed to the link method
     *     
    **/
    public function link ($name, $url = '', array $options = [], array $linkOptions = []) {
        if ($this->_level == 0 && $this->autoButtonLink) {
            $options = $this->addClass ($options, 'btn btn-default navbar-btn') ;
            return $this->Html->link ($name, $url, $options) ;
        }
        if (Router::url() == Router::url ($url) && $this->autoActiveLink) {
            $options = $this->addClass ($options, 'active');
        }
        return $this->Html->tag('li', $this->Html->link ($name, $url, $linkOptions), $options) ;
    }

    /**
     * 
     * Add a button to the navbar.
     * 
     * @param name    Text of the button.
     * @param options Options sent to the BootstrapFormHelper::button method.
     * 
    **/
    public function button ($name, array $options = []) {
        $options = $this->addClass ($options, 'navbar-btn') ;
        return $this->Form->button ($name, $options) ;
    }
    
    /**
     * 
     * Add a divider to the navbar or to a menu.
     * 
     * @param options Options sent to the tag method.
     * 
    **/
    public function divider (array $options = []) {
        $options = $this->addClass ($options, 'divider') ;
        $options['role'] = 'separator' ;
        return $this->Html->tag('li', '', $options) ;
    }

    /**
     * 
     * Add a header to the navbar or to a menu, should not be used outside a submenu.
     * 
     * @param name    Title of the header.
     * @param options Options sent to the tag method.
     * 
    **/
    public function header ($name, array $options = []) {
        $options = $this->addClass ($options, 'dropdown-header') ;
        return $this->Html->tag('li', $name, $options) ;
    }

    /**
     * 
     * Add a text to the navbar.
     * 
     * @param text The text message.
     * @param options Options passed to the tag method (+ extra options, see above).
     * 
     * Extra options:
     *  - tag The HTML tag to use (default 'p')
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
     * @param model   Model for BootstrapFormHelper::searchForm method.
     * @param options Options for BootstrapFormHelper::searchForm method.
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
        return '</ul>'.($this->_level == 1 ? '</li>' : '') ;
    }

    /**
     * 
     * End a navbar.
     * 
    **/
    public function end () {
        $res = '</div></div>' ;
        if ($this->_responsive) {
            $res .= '</div>' ;
        }
        return $res ;
    }
        
}

?>
