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
        // Default icon (Glyphicon)
        $this->assertHtml ([
            ['i' => [
                'class' => 'glyphicon glyphicon-'.$type
            ]],
            '/i'
        ], $this->Html->icon($type));
        $this->assertHtml ([
            ['i' => [
                'class' => $options['class'].' glyphicon glyphicon-'.$type,
                'id' => $options['id']
            ]],
            '/i'
        ], $this->Html->icon($type, $options));
        // FontAwesome icon
        $this->assertHtml ([
            ['i' => [
                'class' => 'fa fa-'.$type
            ]],
            '/i'
        ], $this->Html->faIcon($type));
        $this->assertHtml ([
            ['i' => [
                'class' => $options['class'].' fa fa-'.$type,
                'id' => $options['id']
            ]],
            '/i'
        ], $this->Html->faIcon($type, $options));        
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
        /**
          <ul class="dropdown-menu">
            <li><a href="#">Link 1</a></li>
            <li><a href="#">Link 2</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="#">Link 3</a></li>
          </ul>
        **/
        $title = 'Action' ;
        $menu   = [
            $this->Html->link('Link 1', '#'),
            $this->Html->link('Link 2', '#'),
            'divider',
            $this->Html->link('Link 3', '#')
        ] ;
        $expected = [
            ['ul' => [
                'role'  => 'menu',
                'class' => 'dropdown-menu'
            ]],
            ['li' => [
                'role' => 'presentation'
            ]], ['a' => ['href'  => '#']], 'Link 1', '/a', '/li',
            ['li' => [
                'role' => 'presentation'
            ]], ['a' => ['href'  => '#']], 'Link 2', '/a', '/li',
            ['li' => [
                'role' => 'presentation',
                'class' => 'divider'
            ]], '/li',
            ['li' => [
                'role' => 'presentation'
            ]], ['a' => ['href'  => '#']], 'Link 3', '/a', '/li',
            '/ul'
        ] ;
        
    }

}