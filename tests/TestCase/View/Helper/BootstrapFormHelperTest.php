<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapFormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapFormHelperTest extends TestCase {

    /**
     *
     */
    public $View ;
    public $Form ;
    
    public function assertHtml($expected, $string, $fullDebug = false) {
        array_walk ($expected, function (&$value) {
            if (!is_array($value))
                return ;
            $tag = array_keys ($value)[0] ;
            if (!isset($value[$tag]['class']))
                return ;
            if (is_string($value[$tag]['class']))
                $value[$tag]['class'] = explode(' ', $value[$tag]['class']) ;
            $value[$tag]['class'] = 'preg:/'.implode(' ', array_map('trim', $value[$tag]['class'])).'\s*/' ;
        });
        return parent::assertHtml ($expected, $string, $fullDebug) ;
    } 

    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->Form = new BootstrapFormHelper ($this->View);
    }

    public function testCreate () {
        // Standard form
        $result  = $this->Form->create () ;
        $this->assertHtml([
            ['form' => [
                'method',
                'accept-charset',
                'role' => 'form',
                'action'
            ]]
        ], $result) ; 
        // Horizontal form
        $result  = $this->Form->create (null, ['horizontal' => true]) ;
        $this->assertHtml([
            ['form' => [
                'method',
                'accept-charset',
                'role' => 'form',
                'action',
                'class' => 'form-horizontal'
            ]]
        ], $result) ; 
    }
    
    public function testInput () {
        $fieldName = 'field' ;
        // Standard form
        $this->Form->create () ;
        $result = $this->Form->input ($fieldName) ;
        $this->assertHtml ([
            ['fieldset' => [
                'class' => 'form-group'
            ]],
            ['label' => [
                'class' => '',
                'for' => $fieldName
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => $fieldName,
                'id'    => $fieldName
            ]],
            '/fieldset'
        ], $result) ;
        // Horizontal form
        $this->Form->create (null, ['horizontal' => true]) ;
        $result = $this->Form->input ($fieldName) ;
        $this->assertHtml ([
            ['fieldset' => [
                'class' => ['form-group', 'row']
            ]],
            ['label' => [
                'class' => ['form-control-label', 'col-md-2'],
                'for' => $fieldName
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => 'col-md-10'
            ]],
            ['input' => [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => $fieldName,
                'id'    => $fieldName
            ]],
            '/div',
            '/fieldset'
        ], $result) ;
    }

    public function testInputText () {
        $fieldName = 'field' ;
        $this->Form->create () ;
        $result = $this->Form->input ($fieldName, ['type' => 'text']) ;
        $this->assertHtml ([
            ['fieldset' => [
                'class' => 'form-group'
            ]],
            ['label' => [
                'class' => '',
                'for' => $fieldName
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => $fieldName,
                'id'    => $fieldName
            ]],
            '/fieldset'
        ], $result) ;
    }
    
    public function testInputSelect () {

    }

    public function testInputRadio () {

    }

    public function testInputCheckbox () {

    }

}