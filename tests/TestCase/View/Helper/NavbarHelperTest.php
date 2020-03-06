<?php
declare(strict_types=1);

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 * @link        https://holt59.github.io/cakephp3-bootstrap-helpers/
 */
namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\NavbarHelper;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class NavbarHelperTest extends TestCase
{
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
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html',
        ]);
        $view->loadHelper('Form', [
            'className' => 'Bootstrap.Form',
        ]);
        $this->navbar = new NavbarHelper($view);
    }

    public function testCreate()
    {
        // Test default:
        $result = $this->navbar->create(null);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand-lg',
            ]],
            'button' => [
                'type' => 'button',
                'class' => 'navbar-toggler',
                'data-toggle' => 'collapse',
                'data-target' => '#navbar',
                'aria-controls' => 'navbar',
                'aria-label' => __('Toggle navigation'),
                'aria-expanded' => 'false',
            ],
            ['span' => ['class' => 'navbar-toggler-icon']], '/span',
            '/button',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbar',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test non responsive:
        $result = $this->navbar->create(null, ['collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test brand and non responsive:
        $result = $this->navbar->create('Brandname', ['collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand',
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
                'class' => 'navbar navbar-light bg-light navbar-expand-lg',
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
                'aria-expanded' => 'false',
            ],
            ['span' => ['class' => 'navbar-toggler-icon']], '/span',
            '/button',
            ['div' => [
                'class' => 'collapse navbar-collapse',
                'id' => 'navbar',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test container
        $result = $this->navbar->create(null, ['container' => true, 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light navbar-expand',
            ]],
            ['div' => [
                'class' => 'container',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test theme
        $result = $this->navbar->create(null, ['theme' => 'dark', 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-dark bg-dark navbar-expand',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test sticky
        $result = $this->navbar->create(null, ['sticky' => true, 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light sticky-top navbar-expand',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test fixed top
        $result = $this->navbar->create(null, ['fixed' => 'top', 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light fixed-top navbar-expand',
            ]],
        ];
        $this->assertHtml($expected, $result);

        // Test fixed bottom
        $result = $this->navbar->create(null, ['fixed' => 'bottom', 'collapse' => false]);
        $expected = [
            ['nav' => [
                'class' => 'navbar navbar-light bg-light fixed-bottom navbar-expand',
            ]],
        ];
        $this->assertHtml($expected, $result);
    }

    public function testEnd()
    {
        // Test standard end (responsive)
        $this->navbar->create(null);
        $result = $this->navbar->end();
        $expected = ['/div', '/nav'];
        $this->assertHtml($expected, $result);

        // Test non-responsive end
        $this->navbar->create(null, ['collapse' => false]);
        $result = $this->navbar->end();
        $expected = ['/nav'];
        $this->assertHtml($expected, $result);

        // Test container end (responsive)
        $this->navbar->create(null, ['container' => true]);
        $result = $this->navbar->end();
        $expected = ['/div', '/div', '/nav'];
        $this->assertHtml($expected, $result);

        // Test non-responsive end
        $this->navbar->create(null, ['container' => true, 'collapse' => false]);
        $result = $this->navbar->end();
        $expected = ['/div', '/nav'];
        $this->assertHtml($expected, $result);
    }

    public function testText()
    {
        // Normal test
        $result = $this->navbar->text('Some text');
        $expected = [
            ['span' => ['class' => 'navbar-text']],
            'Some text',
            '/span',
        ];
        $this->assertHtml($expected, $result);

        // Custom options
        $result = $this->navbar->text('Some text', ['class' => 'my-class']);
        $expected = [
            ['span' => ['class' => 'navbar-text my-class']],
            'Some text',
            '/span',
        ];
        $this->assertHtml($expected, $result);

        // Link automatic wrapping
        $result = $this->navbar->text('Some text with a <a href="/">link</a>.');
        $expected = [
            ['span' => ['class' => 'navbar-text']],
            'Some text with a <a href="/">link</a>.',
            '/span',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->text(
            'Some text with a <a href="/" class="my-class">link</a>.'
        );
        $expected = [
            ['span' => ['class' => 'navbar-text']],
            'Some text with a <a href="/" class="my-class">link</a>.',
            '/span',
        ];
        $this->assertHtml($expected, $result);
    }

    public function testMenu()
    {
        $this->loadRoutes();

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
        $result .= $this->navbar->link('Another action', '', ['class' => 'my-class-1']);
        $result .= $this->navbar->link('Something else here', '', ['class' => 'my-class-2']);
        $result .= $this->navbar->divider();
        $result .= $this->navbar->header('Header 2');
        $result .= $this->navbar->link('Another action');
        $result .= $this->navbar->endMenu();
        $result .= $this->navbar->endMenu();
        $expected = [
            ['ul' => ['class' => 'navbar-nav mr-auto my-menu']],
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/', 'class' => 'nav-link']], 'Link', '/a', '/li',
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/pages/test', 'class' => 'nav-link']], 'Blog', '/a', '/li',
            ['li' => ['class' => 'nav-item dropdown']],
            ['a' => ['href' => '#', 'class' => 'nav-link dropdown-toggle', 'data-toggle' => 'dropdown',
                     'role' => 'button', 'aria-haspopup' => 'true',
                     'aria-expanded' => 'false']],
            'Dropdown',
            '/a',
            ['div' => ['class' => 'dropdown-menu']],
            ['h6' => ['class' => 'dropdown-header']], 'Header 1', '/h6',
            ['a' => ['href' => '/', 'class' => 'dropdown-item']], 'Action', '/a',
            ['a' => ['href' => '/', 'class' => 'dropdown-item my-class-1']], 'Another action', '/a',
            ['a' => ['href' => '/', 'class' => 'dropdown-item my-class-2']], 'Something else here', '/a',
            ['div' => ['role' => 'separator', 'class' => 'dropdown-divider']], '/div',
            ['h6' => ['class' => 'dropdown-header']], 'Header 2', '/h6',
            ['a' => ['href' => '/', 'class' => 'dropdown-item']], 'Another action', '/a',
            '/div',
            '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        // TODO: Add more tests...
    }

    public function testAutoActiveLink()
    {
        $this->loadRoutes();
        $this->navbar->create(null);
        $this->navbar->beginMenu('');

        // Active and correct link:
        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        // Active and incorrect link but more complex:
        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        // Unactive and correct link:
        $this->navbar->setConfig('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        // Unactive and incorrect link:
        $this->navbar->setConfig('autoActiveLink', false);
        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
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
                'pass' => ['1'],
            ])
            ->withAttribute('base', '/cakephp');
        Router::setRequest($request);

        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages', [
            'active' => ['action' => false, 'pass' => false],
        ]);
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/cakephp/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/cakephp/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
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
        $request = new ServerRequest(['url' => '/pages/faq']);
        $request = $request
            ->withAttribute('params', [
                'action' => 'display',
                'plugin' => null,
                'controller' => 'pages',
                'pass' => ['faq'],
            ])
            ->withAttribute('base', '/cakephp');
        Router::setRequest($request);

        $this->navbar->setConfig('autoActiveLink', true);
        $result = $this->navbar->link('Link', '/pages', [
            'active' => ['action' => false, 'pass' => false],
        ]);
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/cakephp/pages', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages/credits');
        $expected = [
            ['li' => ['class' => 'nav-item']],
            ['a' => ['href' => '/cakephp/pages/credits', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->navbar->link('Link', '/pages/faq');
        $expected = [
            ['li' => ['class' => 'nav-item active']],
            ['a' => ['href' => '/cakephp/pages/faq', 'class' => 'nav-link']], 'Link', '/a',
            '/li',
        ];
        $this->assertHtml($expected, $result);
    }
}
