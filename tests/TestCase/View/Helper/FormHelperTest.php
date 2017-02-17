<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\FormHelper;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class FormHelperTest extends TestCase {

    /**
     * Instance of FormHelper.
     *
     * @var FormHelper
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
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html'
        ]);
        $this->form = new FormHelper($view);
        $this->dateRegex = [
            'daysRegex' => 'preg:/(?:<option value="0?([\d]+)">\\1<\/option>[\r\n]*)*/',
            'monthsRegex' => 'preg:/(?:<option value="[\d]+">[\w]+<\/option>[\r\n]*)*/',
            'yearsRegex' => 'preg:/(?:<option value="([\d]+)">\\1<\/option>[\r\n]*)*/',
            'hoursRegex' => 'preg:/(?:<option value="0?([\d]+)">\\1<\/option>[\r\n]*)*/',
            'minutesRegex' => 'preg:/(?:<option value="([\d]+)">0?\\1<\/option>[\r\n]*)*/',
            'meridianRegex' => 'preg:/(?:<option value="(am|pm)">\\1<\/option>[\r\n]*)*/',
        ];
        Configure::write('debug', true);
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

    protected function _testInput($expected, $fieldName, $options = [], $debug = false) {
        $formOptions = [];
        if(isset($options['_formOptions'])) {
            $formOptions = $options['_formOptions'];
            unset($options['_formOptions']);
        }
        $this->form->create(null, $formOptions);
        $result = $this->form->input($fieldName, $options);
        $assert = $this->assertHtml($expected, $result, $debug);
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
                'class' => 'control-label',
                'for' => $fieldName
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
                'class' => 'control-label col-md-2',
                'for' => $fieldName
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
                'class' => 'dropdown-menu'
            ]],
            ['li' => []], ['a' => ['href'  => '#']], 'Link 1', '/a', '/li',
            ['li' => []], ['a' => ['href'  => '#']], 'Link 2', '/a', '/li',
            ['li' => [
                'role' => 'separator',
                'class' => 'divider'
            ]], '/li',
            ['li' => []], ['a' => ['href'  => '#']], 'Link 3', '/a', '/li',
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

    public function testDateTime() {
        extract($this->dateRegex);

        $result = $this->form->dateTime('Contact.date', ['default' => true]);
        $now = strtotime('now');
        $expected = [
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][year]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][month]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][day]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][hour]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $hoursRegex,
            ['option' => ['value' => date('H', $now), 'selected' => 'selected']],
            date('G', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][minute]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $minutesRegex,
            ['option' => ['value' => date('i', $now), 'selected' => 'selected']],
            date('i', $now),
            '/option',
            '*/select',
            '/div'
        ];
        $this->assertHtml($expected, $result);

        // Empty=>false implies Default=>true, as selecting the "first" dropdown value is useless
        $result = $this->form->dateTime('Contact.date', ['empty' => false]);
        $now = strtotime('now');
        $expected = [
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][year]', 'class' => 'form-control']],
            $yearsRegex,
            ['option' => ['value' => date('Y', $now), 'selected' => 'selected']],
            date('Y', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][month]', 'class' => 'form-control']],
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][day]', 'class' => 'form-control']],
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][hour]', 'class' => 'form-control']],
            $hoursRegex,
            ['option' => ['value' => date('H', $now), 'selected' => 'selected']],
            date('G', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-2']],
            ['select' => ['name' => 'Contact[date][minute]', 'class' => 'form-control']],
            $minutesRegex,
            ['option' => ['value' => date('i', $now), 'selected' => 'selected']],
            date('i', $now),
            '/option',
            '*/select',
            '/div'
        ];
        $this->assertHtml($expected, $result);

        // year => false implies 4 column, thus column size => 3
        $result = $this->form->dateTime('Contact.date', ['default' => true, 'year' => false]);
        $now = strtotime('now');
        $expected = [
            ['div' => ['class' => 'col-md-3']],
            ['select' => ['name' => 'Contact[date][month]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $monthsRegex,
            ['option' => ['value' => date('m', $now), 'selected' => 'selected']],
            date('F', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-3']],
            ['select' => ['name' => 'Contact[date][day]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $daysRegex,
            ['option' => ['value' => date('d', $now), 'selected' => 'selected']],
            date('j', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-3']],
            ['select' => ['name' => 'Contact[date][hour]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $hoursRegex,
            ['option' => ['value' => date('H', $now), 'selected' => 'selected']],
            date('G', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-3']],
            ['select' => ['name' => 'Contact[date][minute]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $minutesRegex,
            ['option' => ['value' => date('i', $now), 'selected' => 'selected']],
            date('i', $now),
            '/option',
            '*/select',
            '/div'
        ];
        $this->assertHtml($expected, $result);

        // year => false, month => false, day => false implies 2 column, thus column size => 6
        $result = $this->form->dateTime('Contact.date', ['default' => true, 'year' => false,
                                                         'month' => false, 'day' => false]);
        $now = strtotime('now');
        $expected = [
            ['div' => ['class' => 'col-md-6']],
            ['select' => ['name' => 'Contact[date][hour]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $hoursRegex,
            ['option' => ['value' => date('H', $now), 'selected' => 'selected']],
            date('G', $now),
            '/option',
            '*/select',
            '/div',
            ['div' => ['class' => 'col-md-6']],
            ['select' => ['name' => 'Contact[date][minute]', 'class' => 'form-control']],
            ['option' => ['value' => '']],
            '/option',
            $minutesRegex,
            ['option' => ['value' => date('i', $now), 'selected' => 'selected']],
            date('i', $now),
            '/option',
            '*/select',
            '/div'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testSubmit() {
        $this->form->horizontal = false;
        $result = $this->form->submit('Submit');
        $expected = [
            ['div' => ['class' => 'form-group']],
            ['input' => [
                'type' => 'submit',
                'class' => 'btn btn-default',
                'value' => 'Submit'
            ]],
            '/div'
        ];
        $this->assertHtml($expected, $result);

        // horizontal forms
        $this->form->horizontal = true;
        $result = $this->form->submit('Submit');
        $expected = [
            ['div' => ['class' => 'form-group']],
            ['div' => ['class' => 'col-md-offset-2 col-md-10']],
            ['input' => [
                'type' => 'submit',
                'class' => 'btn btn-default',
                'value' => 'Submit'
            ]],
            '/div',
            '/div'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testCustomFileInput() {
        $this->form->setConfig('useCustomFileInput', true);
        $result = $this->form->file('Contact.picture');
        $expected = [
            ['input' => [
                'type' => 'file',
                'name' => 'Contact[picture]',
                'id' => 'Contact[picture]',
                'style' => 'display: none;',
                'onchange' => "document.getElementById('Contact[picture]-input').value = (this.files.length <= 1) ? this.files[0].name : this.files.length + ' ' + 'files selected';"
            ]],
            ['div' => ['class' => 'input-group']],
            ['div' => ['class' => 'input-group-btn']],
            ['button' => [
                'class' => 'btn btn-default',
                'type' => 'button',
                'onclick' => "document.getElementById('Contact[picture]').click();"
            ]],
            __('Choose File'),
            '/button',
            '/div',
            ['input' => [
                'type' => 'text',
                'name' => 'Contact[picture]-text',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'id' => 'Contact[picture]-input',
                'onclick' => "document.getElementById('Contact[picture]').click();"
            ]],
            '/div',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->form->file('Contact.picture', ['multiple' => true]);
        $expected = [
            ['input' => [
                'type' => 'file',
                'multiple' => 'multiple',
                'name' => 'Contact[picture]',
                'id' => 'Contact[picture]',
                'style' => 'display: none;',
                'onchange' => "document.getElementById('Contact[picture]-input').value = (this.files.length <= 1) ? this.files[0].name : this.files.length + ' ' + 'files selected';"
            ]],
            ['div' => ['class' => 'input-group']],
            ['div' => ['class' => 'input-group-btn']],
            ['button' => [
                'class' => 'btn btn-default',
                'type' => 'button',
                'onclick' => "document.getElementById('Contact[picture]').click();"
            ]],
            __('Choose Files'),
            '/button',
            '/div',
            ['input' => [
                'type' => 'text',
                'name' => 'Contact[picture]-text',
                'class' => 'form-control',
                'readonly' => 'readonly',
                'id' => 'Contact[picture]-input',
                'onclick' => "document.getElementById('Contact[picture]').click();"
            ]],
            '/div',
        ];
        $this->assertHtml($expected, $result);
    }

}
