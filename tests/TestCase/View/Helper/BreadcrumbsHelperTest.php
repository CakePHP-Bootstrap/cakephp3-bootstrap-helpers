<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BreadcrumbsHelper;
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
    public function setUp() {
        parent::setUp();
        $view = new View();
        $this->breadcrumbs = new BreadcrumbsHelper($view);
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
