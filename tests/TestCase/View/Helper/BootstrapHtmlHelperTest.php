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

}