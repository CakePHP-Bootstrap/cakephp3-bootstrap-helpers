<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\NavbarHelper;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class NavbarHelperTest extends TestCase {

    /**
     * Instance of the NavbarHelper.
     *
     * @var NavbarHelper
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
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html'
        ]);
        $view->loadHelper('Form', [
            'className' => 'Bootstrap.Form'
        ]);
        $this->navbar = new NavbarHelper($view);
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
                'data-target' => '#navbar',
                'aria-expanded' => 'false'
            ],
            ['span' => ['class' => 'sr-only']], __('Toggle navigation'), '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            ['span' => ['class' => 'icon-bar']], '/span',
            '/button',
            '/div',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbar'
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
                'data-target' => '#navbar',
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
                'class' => 'collapse navbar-collapse',
                'id' => 'navbar'
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

        // Custom options
        $result = $this->navbar->text('Some text', ['class' => 'my-class']);
        $expected = [
            ['p' => ['class' => 'navbar-text my-class']],
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

        $result = $this->navbar->text(
            'Some text with a <a href="/" class="my-class">link</a>.');
        $expected = [
            ['p' => ['class' => 'navbar-text']],
            'Some text with a <a href="/" class="my-class navbar-link">link</a>.',
            '/p'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testMenu() {
        // TODO: Add test for this...
        $this->navbar->setConfig('autoActiveLink', false);
        // Basic test:
        $this->navbar->create(null);
        $result = $this->navbar->beginMenu(['class' => 'my-menu']);
        $result .= $this->navbar->link('Link', '/', ['class' => 'active']);
        $result .= $this->navbar->link('Blog', ['controller' => 'pages', 'action' => 'test']);
        $result .= $this->navbar->beginMenu('Dropdown');
        $result .= $this->navbar->header('Header 1');
        $result .= $this->navbar->link('Action');
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->link('Something else here');
        $result .= $this->navbar->divider();
        $result .= $this->navbar->header('Header 2');
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->endMenu();
        $result .= $this->navbar->endMenu();
        $expected = [
            ['ul' => ['class' => 'nav navbar-nav my-menu']],
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/']], 'Link', '/a', '/li',
            ['li' => []],
            ['a' => ['href' => '/pages/test']], 'Blog', '/a', '/li',
            ['li' => ['class' => 'dropdown']],
            ['a' => ['href' => '#', 'class' => 'dropdown-toggle', 'data-toggle' => 'dropdown',
                     'role' => 'button', 'aria-haspopup' => 'true',
                     'aria-expanded' => 'false']],
            'Dropdown',
            ['span' => ['class' => 'caret']], '/span', '/a',
            ['ul' => ['class' => 'dropdown-menu']],
            ['li' => ['class' => 'dropdown-header']], 'Header 1', '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Action', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Another action', '/a', '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Something else here', '/a', '/li',
            ['li' => ['role' => 'separator', 'class' => 'divider']], '/li',
            ['li' => ['class' => 'dropdown-header']], 'Header 2', '/li',
            ['li' => []], ['a' => ['href' => '/']], 'Another action', '/a', '/li',
            '/ul',
            '/li',
            '/ul'
        ];
        $this->assertHtml($expected, $result, true);

        // TODO: Add more tests...
    }

    public function testAutoActiveLink() {
        $this->navbar->create(null);
        $this->navbar->beginMenu('');

        // Active and correct link:
        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Active and incorrect link but more complex:
        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Unactive and correct link:
        $this->navbar->setConfig('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Unactive and incorrect link:
        $this->navbar->setConfig('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Customt tests

        Router::scope('/', function (RouteBuilder $routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        Router::fullBaseUrl('/cakephp/pages/view/1');
        Configure::write('App.fullBaseUrl', 'http://localhost');
        $request = new ServerRequest();
        $request = $request
            ->withAttribute('params', [
                'action' => 'view',
                'plugin' => null,
                'controller' => 'pages',
                'pass' => ['1']
            ])
            ->withAttribute('base', '/cakephp');
        Router::setRequestInfo($request);

        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages', [
            'active' => ['action' => false, 'pass' => false]
        ]);
        $expected = [
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/cakephp/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/cakephp/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // More custom tests...
        Router::scope('/', function (RouteBuilder $routes) {
            $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']); // (1)
            $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']); // (2)
            $routes->fallbacks(DashedRoute::class);
        });
        Router::fullBaseUrl('');
        Configure::write('App.fullBaseUrl', 'http://localhost');
        $request = new ServerRequest('/pages/faq');
        $request = $request
            ->withAttribute('params', [
                'action' => 'display',
                'plugin' => null,
                'controller' => 'pages',
                'pass' => ['faq']
            ])
            ->withAttribute('base', '/cakephp');
        Router::setRequestInfo($request);

        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages', [
            'active' => ['action' => false, 'pass' => false]
        ]);
        $expected = [
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/cakephp/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages/credits');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/cakephp/pages/credits']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages/faq');
        $expected = [
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/cakephp/pages/faq']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);
    }

};
