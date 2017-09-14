<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\NavbarHelper;
use Cake\Core\Configure;
use Cake\Network\Request;
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
                'class' => 'navbar navbar-light bg-light navbar-expand-lg'
            ]],
            'button' => [
                'type' => 'button',
                'class' => 'navbar-toggler',
                'data-toggle' => 'collapse',
                'data-target' => '#navbar',
                'aria-controls' => 'navbar',
                'aria-label' => __('Toggle navigation'),
                'aria-expanded' => 'false'
            ],
            ['span' => ['class' => 'navbar-toggler-icon']], '/span',
            '/button',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbar'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test non responsive:
        $result = $this->navbar->create(null, ['collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test brand and non responsive:
        $result = $this->navbar->create('Brandname', ['collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand'
            ]],
            ['a' => [
                'class' => 'navbar-brand',
                'href' => '/',
            ]], 'Brandname', '/a',
        ];
        $this->assertHtml($expected, $result);

        // Test brand and responsive:
        $result = $this->navbar->create('Brandname');
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand-lg'
            ]],
            ['a' => [
                'class' => 'navbar-brand',
                'href' => '/',
            ]], 'Brandname', '/a',
            'button' => [
                'type' => 'button',
                'class' => 'navbar-toggler',
                'data-toggle' => 'collapse',
                'data-target' => '#navbar',
                'aria-controls' => 'navbar',
                'aria-label' => __('Toggle navigation'),
                'aria-expanded' => 'false'
            ],
            ['span' => ['class' => 'navbar-toggler-icon']], '/span',
            '/button',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbar'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test container
        $result = $this->navbar->create(null, ['container' => true, 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand'
            ]],
            ['div' => [
                'class' => 'container'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test scheme
        $result = $this->navbar->create(null, ['scheme' => 'dark', 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-dark bg-dark navbar-expand'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test sticky
        $result = $this->navbar->create(null, ['sticky' => true, 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light sticky-top navbar-expand'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fixed top
        $result = $this->navbar->create(null, ['fixed' => 'top', 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light fixed-top navbar-expand'
            ]]
        ];
        $this->assertHtml($expected, $result);

        // Test fixed bottom
        $result = $this->navbar->create(null, ['fixed' => 'bottom', 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light fixed-bottom navbar-expand'
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
        $this->navbar->config('autoActiveLink', false);
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
        $this->navbar->config('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => ['class' => 'active']],
            ['a' => ['href' => '/']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Active and incorrect link but more complex:
        $this->navbar->config('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/pages']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Unactive and correct link:
        $this->navbar->config('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => []],
            ['a' => ['href' => '/']], 'Link', '/a',
            '/li'
        ];
        $this->assertHtml($expected, $result);

        // Unactive and incorrect link:
        $this->navbar->config('autoActiveLink', false);
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
        Router::fullBaseUrl('');
        Configure::write('App.fullBaseUrl', 'http://localhost');
        $request = new Request();
        $request->addParams([
            'action' => 'view',
            'plugin' => null,
            'controller' => 'pages',
            'pass' => ['1']
        ]);
        $request->base = '/cakephp';
        $request->here = '/cakephp/pages/view/1';
        Router::setRequestInfo($request);

        $this->navbar->config('autoActiveLink', true);
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
        $request = new Request();
        $request->addParams([
            'action' => 'display',
            'plugin' => null,
            'controller' => 'pages',
            'pass' => ['faq']
        ]);
        $request->base = '/cakephp';
        $request->here = '/cakephp/pages/faq';
        Router::setRequestInfo($request);

        $this->navbar->config('autoActiveLink', true);
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
