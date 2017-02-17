<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\EasyIconTrait;
use Bootstrap\View\Helper\FormHelper;
use Bootstrap\View\Helper\HtmlHelper;
use Bootstrap\View\Helper\PaginatorHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class PublicEasyIconTrait {

    use EasyIconTrait;

    public function __construct($view) {
        $this->Html = new HtmlHelper($view);
    }

    public function publicEasyIcon($callback, $title, $options) {
        return $this->_easyIcon($callback, $title, $options);
    }

};

class EasyIconTraitTest extends TestCase {

    /**
     * Instance of PublicEasyIconTrait.
     *
     * @var PublicEasyIconTrait
     */
    public $trait;

    /**
     * Instance of FormHelper.
     *
     * @var FormHelper
     */
    public $form;

    /**
     * Instance of PaginatorHelper.
     *
     * @var PaginatorHelper
     */
    public $paginator;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.BootstrapHtml'
        ]);
        $this->trait = new PublicEasyIconTrait($view);
        $this->form = new FormHelper($view);
        $this->paginator = new PaginatorHelper($view);
    }

    public function testEasyIcon() {

        $that = $this;
        $callback = function($text, $options) use($that) {
            $that->assertEquals(isset($options['escape']) ? $options['escape'] : true,
                                $options['expected']['escape']);
            $that->assertHtml($options['expected']['result'], $text);
        };

        $this->trait->publicEasyIcon($callback, 'i:plus', [
            'expected' => [
                'escape' => false,
                'result' => [['i' => [
                    'class' => 'glyphicon glyphicon-plus',
                    'aria-hidden' => 'true'
                ]], '/i']
            ]
        ]);

        $this->trait->publicEasyIcon($callback, 'Click Me!', [
            'expected' => [
                'escape' => true,
                'result' => 'Click Me!'
            ]
        ]);

        $this->trait->publicEasyIcon($callback, 'i:plus Add', [
            'expected' => [
                'escape' => false,
                'result' => [['i' => [
                    'class' => 'glyphicon glyphicon-plus',
                    'aria-hidden' => 'true'
                ]], '/i', ' Add']
            ]
        ]);

        $this->trait->publicEasyIcon($callback, 'Add i:plus', [
            'expected' => [
                'escape' => false,
                'result' => ['Add ', ['i' => [
                    'class' => 'glyphicon glyphicon-plus',
                    'aria-hidden' => 'true'
                ]], '/i']
            ]
        ]);

        $this->trait->easyIcon = false;
        $this->trait->publicEasyIcon($callback, 'i:plus', [
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
        $result = $this->form->button('i:plus');
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-default',
                'type'  => 'submit'
            ]], ['i' => [
                'class' => 'glyphicon glyphicon-plus',
                'aria-hidden' => 'true'
            ]], '/i', '/button'
        ], $result);
        $result = $this->form->input('fieldname', [
            'prepend' => 'i:home',
            'append'  => 'i:plus',
            'label'   => false
        ]);
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
            ['i' => [
                'class' => 'glyphicon glyphicon-home',
                'aria-hidden' => 'true'
            ]], '/i',
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
            ['i' => [
                'class' => 'glyphicon glyphicon-plus',
                'aria-hidden' => 'true'
            ]], '/i',
            '/span',
            '/div',
            '/div'
        ], $result);
        //BootstrapFormHelper::prepend($input, $prepend); // For $prepend.
        //BootstrapFormHelper::append($input, $append); // For $append.
    }

};