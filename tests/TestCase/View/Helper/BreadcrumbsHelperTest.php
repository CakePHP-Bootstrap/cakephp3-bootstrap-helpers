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

use Bootstrap\View\Helper\BreadcrumbsHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BreadcrumbsHelperTest extends TestCase
{
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
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->breadcrumbs = new BreadcrumbsHelper($view);
        $this->loadRoutes();
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
             ->add('Final crumb', null, [
                'class' => 'final',
                'innerAttrs' => [
                    'class' => 'final-link',
                ],
            ]);
        $result = $this->breadcrumbs->render(
            ['data-stuff' => 'foo and bar']
        );
        $expected = [
            ['ol' => [
                'class' => 'breadcrumb',
                'data-stuff' => 'foo and bar',
            ]],
            ['li' => ['class' => 'breadcrumb-item first']],
            ['a' => ['href' => '/', 'data-foo' => 'bar']],
            'Home',
            '/a',
            '/li',
            ['li' => ['class' => 'breadcrumb-item']],
            ['a' => ['href' => '/some_alias']],
            'Some text',
            '/a',
            '/li',
            ['li' => ['class' => 'breadcrumb-item active final', 'aria-current' => 'page']],
            'Final crumb',
            '/li',
            '/ol',
        ];
        $this->assertHtml($expected, $result);
    }
}
