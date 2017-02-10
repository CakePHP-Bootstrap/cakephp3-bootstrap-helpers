<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapFormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapFormHelperTest extends TestCase {

    /**
     * Instance of BootstrapFormHelper.
     *
     * @var BootstrapFormHelper
     */
    public $form;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $this->form = new BootstrapFormHelper($view);
    }

    public function testCreate() {
        // Standard form
        $this->assertHtml([
            ['form' => [
                'method',
                'accept-charset',
                'role' => 'form',
                'action'
            ]]
        ], $this->form->create());
        // Horizontal form
        $result = $this->form->create(null, ['horizontal' => true]);
        $this->assertEquals($this->form->horizontal, true);
        // Automatically return to non horizonal form
        $result = $this->form->create();
        $this->assertEquals($this->form->horizontal, false);
        // Inline form
        $result = $this->form->create(null, ['inline' => true]);
        $this->assertEquals($this->form->inline, true);
        $this->assertHtml([
            ['form' => [
                'method',
                'accept-charset',
                'role' => 'form',
                'action',
                'class' => 'form-inline'
            ]]
        ], $result);
        // Automatically return to non horizonal form
        $result = $this->form->create();
        $this->assertEquals($this->form->inline, false);
    }

    public function testButton() {
        // default button
        $button = $this->form->button('Test');
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-default',
                'type' => 'submit'
            ]], 'Test', '/button'
        ], $button);
        // button with bootstrap-type and bootstrap-size
        $button = $this->form->button('Test', [
            'bootstrap-type' => 'success',
            'bootstrap-size' => 'sm'
        ]);
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-success btn-sm',
                'type' => 'submit'
            ]], 'Test', '/button'
        ], $button);
        // button with class
        $button = $this->form->button('Test', [
            'class' => 'btn btn-primary'
        ]);
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-primary',
                'type' => 'submit'
            ]], 'Test', '/button'
        ], $button);
    }

    protected function _testInput($expected, $fieldName, $options = []) {
        $formOptions = [];
        if(isset($options['_formOptions'])) {
            $formOptions = $options['_formOptions'];
            unset($options['_formOptions']);
        }
        $this->form->create(null, $formOptions);
        return $this->assertHtml($expected, $this->form->input($fieldName, $options));
    }

    public function testInput() {
        $fieldName = 'field';
        // Standard form
        $this->_testInput([
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['label' => [
                'class' => 'control-label',
                'for'   => $fieldName
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => $fieldName,
                'id'    => $fieldName
            ]],
            '/div'
        ], $fieldName);
        // Horizontal form
        $this->_testInput([
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['label' => [
                'class' => 'control-label col-md-2',
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
            '/div'
        ], $fieldName, [
            '_formOptions' => ['horizontal' => true]
        ]);
    }

    public function testInputText() {
        $fieldName = 'field';
        $this->_testInput([
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['label' => [
                'class' => 'control-label',
                'for'   => $fieldName
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => $fieldName,
                'id'    => $fieldName
            ]],
            '/div'
        ], $fieldName, ['type' => 'text']);
    }

    public function testInputSelect() {

    }

    public function testInputRadio() {
        $fieldName = 'color';
        $options   = [
            'type' => 'radio',
            'options' => [
                'red' => 'Red',
                'blue' => 'Blue',
                'green' => 'Green'
            ]
        ];
        // Default
        $expected = [
            ['div' => [
                'class' => 'form-group'
            ]],
            ['label' => [
                'class' => 'control-label'
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ];
        foreach($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['div' => [
                    'class' => 'radio'
                ]],
                ['label' => [
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                $value,
                '/label',
                '/div'
            ]);
        }
        $expected = array_merge($expected, ['/div']);
        $this->_testInput($expected, $fieldName, $options);
        // Inline
        $options += [
            'inline' => true
        ];
        $expected = [
            ['div' => [
                'class' => 'form-group'
            ]],
            ['label' => [
                'class' => 'control-label'
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ];
        foreach($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['label' => [
                    'class' => 'radio-inline',
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                $value,
                '/label'
            ]);
        }
        $expected = array_merge($expected, ['/div']);
        $this->_testInput($expected, $fieldName, $options);
        // Horizontal
        $options += [
            '_formOptions' => ['horizontal' => true]
        ];
        $options['inline'] = false;
        $expected = [
            ['div' => [
                'class' => 'form-group'
            ]],
            ['label' => [
                'class' => 'control-label col-md-2'
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => 'col-md-10'
            ]],
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ];
        foreach($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['div' => [
                    'class' => 'radio'
                ]],
                ['label' => [
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                $value,
                '/label',
                '/div'
            ]);
        }
        $expected = array_merge($expected, ['/div', '/div']);
        $this->_testInput($expected, $fieldName, $options);
        // Horizontal + Inline
        $options['inline'] = true;
        $expected = [
            ['div' => [
                'class' => 'form-group'
            ]],
            ['label' => [
                'class' => 'control-label col-md-2'
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['div' => [
                'class' => 'col-md-10'
            ]],
            ['input' => [
                'type' => 'hidden',
                'name' => $fieldName,
                'value' => '',
                'class' => 'form-control'
            ]]
        ];
        foreach($options['options'] as $key => $value) {
            $expected = array_merge($expected, [
                ['label' => [
                    'class' => 'radio-inline',
                    'for'   => $fieldName.'-'.$key
                ]],
                ['input' => [
                    'type'  => 'radio',
                    'name'  => $fieldName,
                    'value' => $key,
                    'id'    => $fieldName.'-'.$key
                ]],
                $value,
                '/label'
            ]);
        }
        $expected = array_merge($expected, ['/div', '/div']);
        $this->_testInput($expected, $fieldName, $options);
    }

    public function testInputCheckbox() {

    }

    public function testInputGroup() {
        $fieldName = 'field';
        $options   = [
            'type' => 'text',
            'label' => false
        ];
        // Test with prepend addon
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            '@',
            '/span',
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            '/div',
            '/div'
        ];
        $this->_testInput($expected, $fieldName, $options + ['prepend' => '@']);
        // Test with append
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            '.00',
            '/span',
            '/div',
            '/div'
        ];
        $this->_testInput($expected, $fieldName, $options + ['append' => '.00']);
        // Test with append + prepend
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            '$',
            '/span',
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            ['span' => [
                'class' => 'input-group-addon'
            ]],
            '.00',
            '/span',
            '/div',
            '/div'
        ];
        $this->_testInput($expected, $fieldName,
                           $options + ['prepend' => '$', 'append' => '.00']);
        // Test with prepend button
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['span' => [
                'class' => 'input-group-btn'
            ]],
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]],
            'Go!',
            '/button',
            '/span',
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            '/div',
            '/div'
        ];

        $this->_testInput($expected, $fieldName,
                           $options + ['prepend' => $this->form->button('Go!')]);

        // Test with append button
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            ['span' => [
                'class' => 'input-group-btn'
            ]],
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]],
            'Go!',
            '/button',
            '/span',
            '/div',
            '/div'
        ];
        $this->_testInput($expected, $fieldName,
                           $options + ['append' => $this->form->button('Go!')]);
        // Test with append 2 button
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            ['span' => [
                'class' => 'input-group-btn'
            ]],
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]],
            'Go!',
            '/button',
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]],
            'GoGo!',
            '/button',
            '/span',
            '/div',
            '/div'
        ];
        $this->_testInput($expected, $fieldName, $options + [
            'append' => [$this->form->button('Go!'), $this->form->button('GoGo!')]
        ]);
        // Test with append dropdown
        $expected = [
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['div' => [
                'class' => 'input-group'
            ]],
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => $fieldName,
                'id' => $fieldName
            ]],
            ['span' => [
                'class' => 'input-group-btn'
            ]],
            ['div' => [
                'class' => 'btn-group'
            ]],
            ['button' => [
                'data-toggle' => 'dropdown',
                'class' => 'dropdown-toggle btn btn-default'
            ]],
            'Action',
            ['span' => ['class' => 'caret']], '/span',
            '/button',
            ['ul' => [
                'class' => 'dropdown-menu',
                'role'  => 'menu'
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
            '/ul',
            '/div',
            '/span',
            '/div',
            '/div'
        ];
        $this->_testInput($expected, $fieldName, $options + [
            'append' => $this->form->dropdownButton('Action', [
                $this->form->Html->link('Link 1', '#'),
                $this->form->Html->link('Link 2', '#'),
                'divider',
                $this->form->Html->link('Link 3', '#')
            ])
        ]);
    }

    public function testInputTemplateVars() {
        $fieldName = 'field';
        // Add a template with the help placeholder.
        $help = 'Some help text.';
        $this->form->templates([
            'inputContainer' => '<div class="form-group {{type}}{{required}}">{{content}}<span>{{help}}</span></div>'
        ]);
        // Standard form
        $this->_testInput([
            ['div' => [
                'class' => 'form-group text'
            ]],
            ['label' => [
                'class' => 'control-label',
                'for'   => $fieldName
            ]],
            \Cake\Utility\Inflector::humanize($fieldName),
            '/label',
            ['input' => [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => $fieldName,
                'id'    => $fieldName
            ]],
            ['span' => true],
            $help,
            '/span',
            '/div'
        ], $fieldName, ['templateVars' => ['help' => $help]]);
    }

}