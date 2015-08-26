<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapFormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapFormHelperTest extends TestCase {
    
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

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->Form = new BootstrapFormHelper ($this->View);
    }

    /**
     * Tear Down
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->Form);
        unset($this->View);
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
    
    protected function _testInput ($expected, $fieldName, $options = []) {
        $formOptions = [] ;
        if (isset($options['_formOptions'])) {
            $formOptions = $options['_formOptions'] ;
            unset ($options['_formOptions']) ;
        }
        $this->Form->create (null, $formOptions) ;
        return $this->assertHtml ($expected, $this->Form->input ($fieldName, $options)) ;
    }
    
    public function testInput () {
        $fieldName = 'field' ;
        // Standard form
        $this->_testInput ([
            ['fieldset' => [
                'class' => 'form-group'
            ]],
            ['label' => [
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
        ], $fieldName) ;
        // Horizontal form
        $this->_testInput ([
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
        ], $fieldName, [
            '_formOptions' => ['horizontal' => true]
        ]) ;
    }

    public function testInputText () {
        $fieldName = 'field' ;
        $this->_testInput ([
            ['fieldset' => [
                'class' => 'form-group'
            ]],
            ['label' => [
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
        ], $fieldName, ['type' => 'text']) ;
    }
    
    public function testInputSelect () {

    }

    public function testInputRadio () {
        $fieldName = 'color' ;
        $options   = [
            'type' => 'radio',
            'options' => [
                'red' => 'Red',
                'blue' => 'Blue',
                'green' => 'Green'
            ]
        ] ;
        // Default
        $expected = [
            ['label' => true],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => ['radio', 'c-inputs-stacked']
            ]],
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ] ;
        foreach ($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['label' => [
                    'class' => ['c-input', 'c-radio'],
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                ['span' => [
                    'class' => 'c-indicator'
                ]],
                '/span',
                $value,
                '/label'
            ]) ;
        }
        $expected = array_merge ($expected, ['/div']) ;
        $this->_testInput ($expected, $fieldName, $options) ;
        // Inline
        $options += [
            'inline' => true
        ] ;
        $expected = [
            ['label' => true],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => ['radio']
            ]],
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ] ;
        foreach ($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['label' => [
                    'class' => ['c-input', 'c-radio'],
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                ['span' => [
                    'class' => 'c-indicator'
                ]],
                '/span',
                $value,
                '/label'
            ]) ;
        }
        $expected = array_merge ($expected, ['/div']) ;
        $this->_testInput ($expected, $fieldName, $options) ;
        // Horizontal + Inline
        $options += [
            '_formOptions' => ['horizontal' => true]
        ] ;
        $options['inline'] = false ;
        $expected = [
            ['fieldset' => [
                'class' => ['form-group', 'row']
            ]],
            ['label' => [
                'class' => ['form-control-label', 'col-md-2']
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => 'col-md-10'
            ]],
            ['div' => [
                'class' => ['radio', 'c-inputs-stacked']
            ]],
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ] ;
        foreach ($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['label' => [
                    'class' => ['c-input', 'c-radio'],
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                ['span' => [
                    'class' => 'c-indicator'
                ]],
                '/span',
                $value,
                '/label'
            ]) ;
        }
        $expected = array_merge ($expected, ['/div', '/div', '/fieldset']) ;
        $this->_testInput ($expected, $fieldName, $options) ;
        // Horizontal + Inline
        $options['inline'] = true ;
        $expected = [
            ['fieldset' => [
                'class' => ['form-group', 'row']
            ]],
            ['label' => [
                'class' => ['form-control-label', 'col-md-2']
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => 'col-md-10'
            ]],
            ['div' => [
                'class' => ['radio']
            ]],
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ] ;
        foreach ($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['label' => [
                    'class' => ['c-input', 'c-radio'],
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                ['span' => [
                    'class' => 'c-indicator'
                ]],
                '/span',
                $value,
                '/label'
            ]) ;
        }
        $expected = array_merge ($expected, ['/div', '/div', '/fieldset']) ;
        $this->_testInput ($expected, $fieldName, $options) ;
    }

    public function testInputCheckbox () {
        
    }

}