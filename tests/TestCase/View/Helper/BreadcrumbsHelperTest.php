<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BreadcrumbsHelper;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BreadcrumbsHelperTest extends TestCase {

    /**
     * Instance of the BreadcrumbsHelper.
     *
     * @var BreadcrumbsHelper
     */
    public $breadcrumbs;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void {
        parent::setUp();
        $view = new View();
        $this->breadcrumbs = new BreadcrumbsHelper($view);

        Router::reload();
        Router::scope('/', function ($routes) {
            $routes->connect('/', ['controller' => 'pages', 'action' => 'display', 'home']);
            $routes->connect('/some_alias', ['controller' => 'tests_apps', 'action' => 'some_method']);
            $routes->fallbacks();
        });
    }


    /**
     * Tests the render method
     *
     * @return void
     */
    public function testRender()
    {
        $this->assertSame('', $this->breadcrumbs->render());
        $this->breadcrumbs
             ->add('Home', '/', ['class' => 'first', 'innerAttrs' => ['data-foo' => 'bar']])
             ->add('Some text', ['controller' => 'tests_apps', 'action' => 'some_method'])
             ->add('Final crumb', null, ['class' => 'final',
                                         'innerAttrs' => ['class' => 'final-link']]);
        $result = $this->breadcrumbs->render(
            ['data-stuff' => 'foo and bar']
        );
        $expected = [
            ['ol' => [
                'class' => 'breadcrumb',
                'data-stuff' => 'foo and bar'
            ]],
            ['li' => ['class' => 'first']],
            ['a' => ['href' => '/', 'data-foo' => 'bar']],
            'Home',
            '/a',
            '/li',
            ['li' => []],
            ['a' => ['href' => '/some_alias']],
            'Some text',
            '/a',
            '/li',
            ['li' => ['class' => 'active final']],
            'Final crumb',
            '/li',
            '/ol'
        ];
        $this->assertHtml($expected, $result);
    }

};
