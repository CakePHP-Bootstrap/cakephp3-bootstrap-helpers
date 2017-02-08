<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapNavbarHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapNavbarHelperTest extends TestCase {

    /**
     * Instance of the BootstrapNavbarHelper.
     *
     * @var BootstrapNavbarHelper
     */
    public $navbar;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $this->navbar = new BootstrapNavbarHelper($view);
    }

    public function testCreate() {
        // Test default:
        $result = $this->navbar->create(null);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default'
            ]],
            ['div' => [
                'class' => 'container'
            ]],
            ['div' => [
                'class' => 'navbar-header'
            ]],
            'button' => [
                'type' => 'button',
                'class' => 'navbar-toggle collapsed',
                'data-toggle' => 'collapse',
                'data-target' => '.navbar-collapse',
                'aria-expanded' => 'false'
            ],
            ['span' => ['class' => 'sr-only']], __('Toggle navigation'), '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            '/button',
            '/div',
            ['div' => [
                'class' => 'collapse navbar-collapse'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test non responsive:
        $result = $this->navbar->create(null, ['responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default'
            ]],
            ['div' => [
                'class' => 'container'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test brand and non responsive:
        $result = $this->navbar->create('Brandname', ['responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default'
            ]],
            ['div' => [
                'class' => 'container'
            ]],
            ['div' => [
                'class' => 'navbar-header'
            ]],
            ['a' => [
                'class' => 'navbar-brand',
                'href' => '/',
            ]], 'Brandname', '/a',
            '/div',
        ];
        $this->assertHtml($expected, $result);

        // Test brand and responsive:
        $result = $this->navbar->create('Brandname');
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default'
            ]],
            ['div' => [
                'class' => 'container'
            ]],
            ['div' => [
                'class' => 'navbar-header'
            ]],
            'button' => [
                'type' => 'button',
                'class' => 'navbar-toggle collapsed',
                'data-toggle' => 'collapse',
                'data-target' => '.navbar-collapse',
                'aria-expanded' => 'false'
            ],
            ['span' => ['class' => 'sr-only']], __('Toggle navigation'), '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            '/button',
            ['a' => [
                'class' => 'navbar-brand',
                'href' => '/',
            ]], 'Brandname', '/a',
            '/div',
            ['div' => [
                'class' => 'collapse navbar-collapse'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fluid
        $result = $this->navbar->create(null, ['fluid' => true, 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default'
            ]],
            ['div' => [
                'class' => 'container-fluid'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test inverted
        $result = $this->navbar->create(null, ['inverse' => true, 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-inverse'
            ]],
            ['div' => [
                'class' => 'container'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test static
        $result = $this->navbar->create(null, ['static' => true, 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default navbar-static-top'
            ]],
            ['div' => [
                'class' => 'container'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fixed top
        $result = $this->navbar->create(null, ['fixed' => 'top', 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default navbar-fixed-top'
            ]],
            ['div' => [
                'class' => 'container'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fixed bottom
        $result = $this->navbar->create(null, ['fixed' => 'bottom', 'responsive' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-default navbar-fixed-bottom'
            ]],
            ['div' => [
                'class' => 'container'
            ]]
        ];
        $this->assertHtml($expected, $result);
    }

    public function testEnd() {
        // Test standard end (responsive)
        $this->navbar->create(null);
        $result = $this->navbar->end();
        $expected = ['/div', '/div', '/nav'];
        $this->assertHtml($expected, $result);

        // Test non-responsive end
        $this->navbar->create(null, ['responsive' => false]);
        $result = $this->navbar->end();
        $expected = ['/div', '/nav'];
        $this->assertHtml($expected, $result);
    }

    public function testButton() {
        $result = $this->navbar->button('Click Me!');
        $expected = [
            ['button' => ['class' => 'navbar-btn btn btn-default', 'type' => 'button']],
            'Click Me!', '/button'];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->button('Click Me!', ['class' => 'my-class', 'href' => '/']);
        $expected = [
            ['button' => ['class' => 'my-class navbar-btn btn btn-default',
                          'href' => '/', 'type' => 'button']],
            'Click Me!', '/button'];
        $this->assertHtml($expected, $result);
    }

    public function testText() {
        // Normal test
        $result = $this->navbar->text('Some text');
        $expected = [
            ['p' => ['class' => 'navbar-text']],
            'Some text',
            '/p'
        ];
        $this->assertHtml($expected, $result);

        // Custom tag test
        $result = $this->navbar->text('Some text', ['tag' => 'span']);
        $expected = [
            ['span' => ['class' => 'navbar-text']],
            'Some text',
            '/span'
        ];
        $this->assertHtml($expected, $result);

        // Custom options
        $result = $this->navbar->text('Some text', ['class' => 'my-class']);
        $expected = [
            ['p' => ['class' => 'my-class navbar-text']],
            'Some text',
            '/p'
        ];
        $this->assertHtml($expected, $result);

        // Link automatic wrapping
        $result = $this->navbar->text('Some text with a <a href="/">link</a>.');
        $expected = [
            ['p' => ['class' => 'navbar-text']],
            'Some text with a <a href="/" class="navbar-link">link</a>.',
            '/p'
        ];
        $this->assertHtml($expected, $result);
        /*
           $result = $this->navbar->text(
           'Some text with a <a href="/" class="my-class">link</a>.');
           $expected = [
           ['p' => ['class' => 'navbar-text']],
           'Some text with a <a href="/" class="my-class navbar-link">link</a>.',
           '/p'
           ];
           $this->assertHtml($expected, $result); */
    }

    public function testMenu() {
        // TODO: Add test for this...
        $this->navbar->autoActiveLink = false;
        // Basic test:
        $this->navbar->create(null);
        $result = $this->navbar->beginMenu(['class' => 'my-menu']);
        $result .= $this->navbar->link('Link', '/', ['class' => 'active']);
        $result .= $this->navbar->link('Blog', ['controller' => 'pages', 'action' => 'test']);
        $result .= $this->navbar->beginMenu('Dropdown');
        $result .= $this->navbar->link('Action');
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->link('Something else here');
        $result .= $this->navbar->divider();
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->endMenu(); 
        $result .= $this->navbar->endMenu();
        $expected = [
            ['ul' => ['class' => 'my-menu nav navbar-nav']],
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/']], 'Link', '/a', '/li',
            ['li' => []],
            ['a' => ['href' => '/pages/test']], 'Blog', '/a', '/li',
            ['li' => ['class' => 'dropdown']],
            ['a' => ['href' => '#', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown',
                     'role' => 'button', 'aria-haspopup' => 'true',
                     'aria-expanded' => 'false']],
            'Dropdown<span class="caret"></span>', '/a',
            ['ul' => ['class' => 'dropdown-menu']],
            ['li' => []], ['a' => ['href' => '/']], 'Action', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Another action', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Something else here', '/a', '/li',
            ['li' => ['role' => 'separator', 'class' => 'divider']], '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Another action', '/a', '/li',
            '/ul',
            '/li',
            '/ul'
        ];
        $this->assertHtml($expected, $result);

        // TODO: Add more tests...
    }

};
