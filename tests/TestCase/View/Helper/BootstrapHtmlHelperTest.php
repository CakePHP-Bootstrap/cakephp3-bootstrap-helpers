<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapHtmlHelperTest extends TestCase {

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->Html = new BootstrapHtmlHelper ($this->View);
    }

    /**
     * Tear Down
     *
     * @return void
     */
    public function tearDown() {
        parent::tearDown();
        unset($this->Html);
        unset($this->View);
    }

    public function testInitialize () {
        $oldHtml = $this->Html ;
        // Test useGlyphicon
        $type = 'home';
        $options = [
            'id' => 'my-home',
            'class' => 'my-home-class'
        ] ;
        $this->Html = new BootstrapHtmlHelper ($this->View, [
            'useGlyphicon' => true
        ]) ;
        $this->assertEquals ($this->Html->icon ($type, $options), $this->Html->glIcon($type, $options)) ;
        unset ($this->Html) ;
        $this->Html = $oldHtml ;
    }
    
    public function testIcon () {
        $type = 'home';
        $options = [
            'id' => 'my-home',
            'class' => 'my-home-class'
        ] ;
        // Default icon (FontAwesome)
        $this->assertHtml ([
            ['i' => [
                'class' => 'fa fa-'.$type
            ]],
            '/i'
        ], $this->Html->icon($type));
        $this->assertHtml ([
            ['i' => [
                'class' => $options['class'].' fa fa-'.$type,
                'id' => $options['id']
            ]],
            '/i'
        ], $this->Html->icon($type, $options));
        // Glyphicon icon
        $this->assertHtml ([
            ['i' => [
                'class' => 'glyphicon glyphicon-'.$type
            ]],
            '/i'
        ], $this->Html->glIcon($type));
        $this->assertHtml ([
            ['i' => [
                'class' => $options['class'].' glyphicon glyphicon-'.$type,
                'id' => $options['id']
            ]],
            '/i'
        ], $this->Html->glIcon($type, $options));        
    }
    
    public function testLabel () {
        $content = 'My Label' ;
        // Standard test
        $this->assertHtml ([
            ['span' => [
                'class' => 'label label-default'
            ]],
            'My Label',
            '/span'
        ], $this->Html->label($content)) ;
        // Type
        $this->assertHtml ([
            ['span' => [
                'class' => 'label label-primary'
            ]],
            'My Label',
            '/span'
        ], $this->Html->label($content, 'primary')) ;
        // Type + Options
        $options = [
            'class' => 'my-label-class',
            'id'    => 'my-label-id'
        ] ;
        $this->assertHtml ([
            ['span' => [
                'class' => $options['class'].' label label-primary',
                'id'    => $options['id']
            ]],
            'My Label',
            '/span'
        ], $this->Html->label($content, 'primary', $options)) ;
        // Only options
        $options = [
            'class' => 'my-label-class',
            'id'    => 'my-label-id',
            'type'  => 'primary'
        ] ;
        $this->assertHtml ([
            ['span' => [
                'class' => $options['class'].' label label-primary',
                'id'    => $options['id']
            ]],
            'My Label',
            '/span'
        ], $this->Html->label($content, $options)) ;
    }
    
    public function testDropdown () {
        $title = 'Action' ;
        $menu   = [
            $this->Html->link('Link 1', '#'),
            $this->Html->link('Link 2', '#'),
            'divider',
            $this->Html->link('Link 3', '#')
        ] ;
        $expected = [
            ['div' => [
                'class' => 'dropdown'
            ]],
            ['button' => [
                'data-toggle'   => 'dropdown',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
                'id'            => 'dropdownMenu1',
                'class'         => 'dropdown-toggle btn btn-secondary'
            ]],
            'Action',
            '/button',
            ['div' => [
                'class' => 'dropdown-menu',
                'aria-labelledby' => 'dropdownMenu1'
            ]],
            ['a' => [
                'href'  => '#',
                'class' => 'dropdown-item'
            ]], 'Link 1', '/a',
            ['a' => [
                'href'  => '#',
                'class' => 'dropdown-item'
            ]], 'Link 2', '/a',
            ['div' => [
                'class' => 'dropdown-divider'
            ]], '/div',
            ['a' => [
                'href'  => '#',
                'class' => 'dropdown-item'
            ]], 'Link 3', '/a',
            '/div',
            '/div'
        ] ;
        // Standard test
        $this->assertHtml ($expected, $this->Html->dropdown($title, $menu)) ;
        $menu   = [
            ['Link 1', '#'],
            ['Link 2', '#', ['class' => 'my-item-class', 'id' => 'my-item-id']],
            'divider',
            ['Link 3', '#']
        ] ;
        $options = [
            'class' => 'my-dropdown',
            '_button' => [
                'tag' => 'a',
                'id'  => 'my-dropdown-id'
            ],
            '_menu' => [
                'class' => 'my-dropdown-menu',
                '_item' => [
                    'class' => 'my-dropdown-item'
                ]
            ]
        ] ;
        $expected = [
            ['div' => [
                'class' => $options['class'].' dropdown'
            ]],
            [$options['_button']['tag'] => [
                'data-toggle'   => 'dropdown',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
                'id'            => $options['_button']['id'],
                'class'         => 'dropdown-toggle btn btn-secondary'
            ]],
            'Action',
            '/'.$options['_button']['tag'],
            ['div' => [
                'class' => $options['_menu']['class'].' dropdown-menu',
                'aria-labelledby' => $options['_button']['id']
            ]],
            ['a' => [
                'href'  => '#',
                'class' => $options['_menu']['_item']['class'].' dropdown-item'
            ]], 'Link 1', '/a',
            ['a' => [
                'href'  => '#',
                'class' => $menu[1][2]['class'].' dropdown-item',
                'id'    => $menu[1][2]['id']
            ]], 'Link 2', '/a',
            ['div' => [
                'class' => $options['_menu']['_item']['class'].' dropdown-divider'
            ]], '/div',
            ['a' => [
                'href'  => '#',
                'class' => $options['_menu']['_item']['class'].' dropdown-item'
            ]], 'Link 3', '/a',
            '/div',
            '/div'
        ] ;
        $this->assertHtml ($expected, $this->Html->dropdown($title, $menu, $options)) ;
        
    }

}