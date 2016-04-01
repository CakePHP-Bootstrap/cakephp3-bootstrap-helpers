<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapTrait;
use Bootstrap\View\Helper\BootstrapFormHelper;
use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Bootstrap\View\Helper\BootstrapPaginatorHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class PublicBootstrapTrait {
    
    use BootstrapTrait ;
    
    public function __construct ($View) {
        $this->_View = $View;
    }
    
    public function publicEasyIcon ($callback, $title, $options) {
        return $this->_easyIcon($callback, $title, $options);
    }
    
};

class BootstrapTraitTemplateTest extends TestCase {
    
    /**
     * Setup
     *
     * @return void
     */
    public function setUp () {
        parent::setUp();
        $this->View = new View();
        $this->_Trait = new PublicBootstrapTrait($this->View);
        $this->View->Html = new BootstrapHtmlHelper ($this->View);
        $this->Form = new BootstrapFormHelper ($this->View);
        $this->Paginator = new BootstrapPaginatorHelper ($this->View);
    }


    /**
     * Tear Down
     *
     * @return void
     */
    public function tearDown() {
        parent::tearDown();
        unset($this->View->Html);
        unset($this->View);
        unset($this->Form);
        unset($this->Paginator);
    }
        
    public function testEasyIcon() {
        
        $that = $this;
        $callback = function ($text, $options) use ($that) {
            $that->assertEquals(isset($options['escape']) ? $options['escape'] : true, $options['expected']['escape']);
            $that->assertHtml($options['expected']['result'], $text);
        };
                
        $this->_Trait->publicEasyIcon($callback, 'i:plus', [
            'expected' => [
                'escape' => false,
                'result' => [['i' => [
                    'class' => 'glyphicon glyphicon-plus'
                ]], '/i']
            ]
        ]);
        
        $this->_Trait->publicEasyIcon($callback, 'Click Me!', [
            'expected' => [
                'escape' => true,
                'result' => 'Click Me!'
            ]
        ]);
        
        $this->_Trait->publicEasyIcon($callback, 'i:plus Add', [
            'expected' => [
                'escape' => false,
                'result' => [['i' => [
                    'class' => 'glyphicon glyphicon-plus'
                ]], '/i', ' Add']
            ]
        ]);
        
        $this->_Trait->publicEasyIcon($callback, 'Add i:plus', [
            'expected' => [
                'escape' => false,
                'result' => ['Add ', ['i' => [
                    'class' => 'glyphicon glyphicon-plus'
                ]], '/i']
            ]
        ]);
        
        $this->_Trait->easyIcon = false;
        $this->_Trait->publicEasyIcon($callback, 'i:plus', [
            'expected' => [
                'escape' => true,
                'result' => 'i:plus'
            ]
        ]);
        
    }
    
    public function testHelperMethods() {
        
        // BootstrapPaginatorHelper - TODO
        // BootstrapPaginatorHelper::prev($title, array $options = []);
        // BootstrapPaginatorHelper::next($title, array $options = []);
        // BootstrapPaginatorHelper::numbers(array $options = []); // For `prev` and `next` options.

        // BootstrapFormatHelper
        $result = $this->Form->button ('i:plus') ;
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]], ['i' => [
                'class' => 'glyphicon glyphicon-plus'
            ]], '/i', '/button'
        ], $result) ;
        $result = $this->Form->input ('fieldname', [
            'prepend' => 'i:home',
            'append'  => 'i:plus',
            'label'   => false
        ]) ;
        $this->assertHtml([
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            ['i' => ['class' => 'glyphicon glyphicon-home']], '/i',
            '/span',
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => 'fieldname',
                'id' => 'fieldname'
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            ['i' => ['class' => 'glyphicon glyphicon-plus']], '/i',
            '/span',
            '/div',
            '/div'
        ], $result) ;
        //BootstrapFormHelper::prepend($input, $prepend); // For $prepend.
        //BootstrapFormHelper::append($input, $append); // For $append.
    }

};