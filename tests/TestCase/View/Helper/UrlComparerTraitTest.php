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

use Bootstrap\TestApp\PublicUrlComparerTrait;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;

class UrlComparerTraitTest extends TestCase
{
    /**
     * Instance of PublicUrlComparerTrait.
     *
     * @var PublicUrlComparerTrait
     */
    public $trait;

    private $_fullBaseUrl;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // set Router fullBaseUrl
        $this->_fullBaseUrl = Router::fullBaseUrl();
        Router::fullBaseUrl('http://localhost');

        Configure::write('debug', true);

        Router::scope('/', function (RouteBuilder $routes) {
            $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']); // (1)
            $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']); // (2)
            $routes->fallbacks(DashedRoute::class);
        });
        Router::prefix('admin', function ($routes) {
            $routes->fallbacks(DashedRoute::class);
        });
        $this->trait = new PublicUrlComparerTrait();
    }

    public function tearDown(): void
    {
        // reset router fullBaseUrl
        Router::fullBaseUrl($this->_fullBaseUrl);
        parent::tearDown();
    }

    public function testNormalizedWithoutPass()
    {
        $tests = [
            ['/pages/test', '/pages/display'], // normalize as /pages due to (2)
            ['/users/login', '/users/login'],
            ['/users/login/whatever?query=no', '/users/login'],
            ['/pages/display/test', '/pages/display'],
            ['/admin/users/login', '/admin/users/login'],
        ];
        foreach ($tests as $test) {
            [$lhs, $rhs] = $test;
            $nm = $this->trait->normalize($lhs, ['pass' => false]);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
        $request = new ServerRequest([
            'url' => '/pages/view/1',
            'base' => '/cakephp',
            'params' => [
                'action' => 'view',
                'plugin' => null,
                'controller' => 'pages',
                'pass' => ['1'],
            ],
        ]);
        Router::setRequest($request);
        $tests = [
            ['/pages', '/pages/display'],
            ['/pages/display/test', '/pages/display'],
            ['/pages/test', '/pages/display'], // normalize as /pages due to (2)
            ['/pages?query=no', '/pages/display'],
            ['/pages#anchor', '/pages/display'],
            ['/pages?query=no#anchor', '/pages/display'],
            ['/users/login', '/users/login'],
            ['/users/login/whatever', '/users/login'],
            ['/users/login?query=no', '/users/login'],
            ['/users/login#anchor', '/users/login'],
            ['/users/login/whatever?query=no#anchor', '/users/login'],
            ['/admin/users/login', '/admin/users/login'],
            ['/admin/users/login/whatever', '/admin/users/login'],
            ['/admin/users/login?query=no', '/admin/users/login'],
            ['/admin/users/login#anchor', '/admin/users/login'],
            ['/admin/users/login/whatever?query=no#anchor', '/admin/users/login'],
            ['/cakephp/admin/users/login', '/admin/users/login'],
            ['/cakephp/admin/users/login/whatever', '/admin/users/login'],
            ['/cakephp/admin/users/login?query=no', '/admin/users/login'],
            ['/cakephp/admin/users/login#anchor', '/admin/users/login'],
            ['/cakephp/admin/users/login/whatever?query=no#anchor', '/admin/users/login'],
            ['http://localhost/cakephp/pages', '/pages/display'],
            ['http://localhost/cakephp/pages/display/test', '/pages/display'],
            ['http://localhost/cakephp/pages/test', '/pages/display'], // normalize as /pages due to (2)
            ['http://localhost/cakephp/pages?query=no', '/pages/display'],
            ['http://localhost/cakephp/pages#anchor', '/pages/display'],
            ['http://localhost/cakephp/pages?query=no#anchor', '/pages/display'],
            ['http://localhost/cakephp/admin/users/login', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login?query=no', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login#anchor', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever?query=no#anchor', '/admin/users/login'],
            ['http://github.com/cakephp/admin/users', null],
            ['http://localhost/notcakephp', null],
            ['http://localhost/somewhere/cakephp', null],
        ];
        foreach ($tests as $test) {
            [$lhs, $rhs] = $test;
            $nm = $this->trait->normalize($lhs, ['pass' => false]);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
    }

    public function testNormalizedWithPass()
    {
        $tests = [
            ['/pages/test', '/pages/display/test'], // normalize as /pages due to (2)
            ['/users/login', '/users/login'],
            ['/users/login/whatever?query=no', '/users/login/whatever'],
            ['/admin/users/login', '/admin/users/login'],
        ];
        foreach ($tests as $test) {
            [$lhs, $rhs] = $test;
            $nm = $this->trait->normalize($lhs);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
        $request = new ServerRequest([
            'url' => '/pages/view/1',
            'base' => '/cakephp',
            'params' => [
                'action' => 'view',
                'plugin' => null,
                'controller' => 'pages',
                'pass' => ['1'],
            ],
        ]);
        Router::setRequest($request);
        $tests = [
            ['/pages', '/pages/display'],
            ['/pages/test', '/pages/display/test'],
            ['/pages?query=no', '/pages/display'],
            ['/pages#anchor', '/pages/display'],
            ['/pages?query=no#anchor', '/pages/display'],
            ['/users/login', '/users/login'],
            ['/users/login/whatever', '/users/login/whatever'],
            ['/users/login?query=no', '/users/login'],
            ['/users/login#anchor', '/users/login'],
            ['/users/login/whatever?query=no#anchor', '/users/login/whatever'],
            ['/admin/users/login', '/admin/users/login'],
            ['/admin/users/login/whatever', '/admin/users/login/whatever'],
            ['/admin/users/login?query=no', '/admin/users/login'],
            ['/admin/users/login#anchor', '/admin/users/login'],
            ['/admin/users/login/whatever?query=no#anchor', '/admin/users/login/whatever'],
            ['/cakephp/admin/users/login', '/admin/users/login'],
            ['/cakephp/admin/users/login/whatever', '/admin/users/login/whatever'],
            ['/cakephp/admin/users/login?query=no', '/admin/users/login'],
            ['/cakephp/admin/users/login#anchor', '/admin/users/login'],
            ['/cakephp/admin/users/login/whatever?query=no#anchor', '/admin/users/login/whatever'],
            ['http://localhost/cakephp/pages', '/pages/display'],
            ['http://localhost/cakephp/pages/test', '/pages/display/test'],
            ['http://localhost/cakephp/pages?query=no', '/pages/display'],
            ['http://localhost/cakephp/pages#anchor', '/pages/display'],
            ['http://localhost/cakephp/pages?query=no#anchor', '/pages/display'],
            ['http://localhost/cakephp/admin/users/login', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever', '/admin/users/login/whatever'],
            ['http://localhost/cakephp/admin/users/login?query=no', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login#anchor', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever?query=no#anchor', '/admin/users/login/whatever'],
            ['http://github.com/cakephp/admin/users', null],
            ['http://localhost/notcakephp', null],
            ['http://localhost/somewhere/cakephp', null],

        ];
        foreach ($tests as $test) {
            [$lhs, $rhs] = $test;
            $nm = $this->trait->normalize($lhs);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
    }

    private function _testCompare($matchTrue, $matchFalse, $parts = [])
    {
        foreach ($matchTrue as $urls) {
            [$lhs, $rhs] = $urls;
            $this->assertTrue($this->trait->compareUrls($lhs, $rhs, $parts), sprintf('%s [] != %s', Router::url($lhs), Router::url($rhs)));
        }
        foreach ($matchFalse as $urls) {
            [$lhs, $rhs] = $urls;
            $this->assertTrue(!$this->trait->compareUrls($lhs, $rhs, $parts), sprintf('%s == %s', Router::url($lhs), Router::url($rhs)));
        }
    }

    public function testCompare()
    {
        $urlsMatchTrue = [
            // Test root
            ['/', '/'],
            ['/', '/#anchor'],
            // Test connection
            ['/pages', '/pages/test'],
            ['/pages/test', '/pages/test#anchor'],
            ['/pages', '/pages?param=value'],
            ['/pages/test', ['controller' => 'Pages', 'action' => 'display', 'test']],
            ['/pages/test/id', ['controller' => 'Pages', 'action' => 'display', 'test', 'id']],
            // Controller routes
            ['/users/login', ['controller' => 'users', 'action' => 'login']],
            ['/users/login/myself?query=no', ['controller' => 'users', 'action' => 'login', 'myself']],
            ['/users', '/users'],
        ];
        $urlsMatchFalse = [
            ['https://github.com', '/'],
            ['/pages/url', '/pages'],
            ['/pages/url', '/pages/something'],
            [['controller' => 'users', 'action' => 'index'], '/users/edit'],
        ];
        $this->_testCompare($urlsMatchTrue, $urlsMatchFalse);
    }

    public function testFullBase()
    {
        $request = new ServerRequest([
            'url' => '/pages/view/1',
            'base' => '/cakephp',
            'params' => [
                'action' => 'view',
                'plugin' => null,
                'controller' => 'pages',
                'pass' => ['1'],
            ],
        ]);
        Router::setRequest($request);
        $urlsMatchTrue = [
            // Test root
            ['/', '/'],
            ['/', '/#anchor'],
            // Test connection
            ['/pages', '/pages/test'],
            ['/pages/test', '/pages/test#anchor'],
            ['/pages', '/pages?param=value'],
            ['/pages/test', ['controller' => 'Pages', 'action' => 'display', 'test']],
            ['/pages/test/id', ['controller' => 'Pages', 'action' => 'display', 'test', 'id']],
            // Controller routes
            ['/user/login', ['controller' => 'user', 'action' => 'login']],
            ['/user/login/myself?query=no', ['controller' => 'user', 'action' => 'login', 'myself']],
            [[], ['controller' => 'pages', 'action' => 'view', '1']],
            [[], 'http://localhost/cakephp/pages/view/1'],
            [[], 'https://localhost/cakephp/pages/view/1'],
            [[], '/pages/view/1'],
            ['/pages/view', []],
            ['/pages/test', '/pages/test'], // normalize as /pages due to (2)
            ['/users/login', '/users/login'],
            ['/users/login/whatever?query=no', '/users/login/whatever'],
            ['/pages/display/test', '/pages/display/test'],
            ['/admin/users/login', '/admin/users/login'],
            ['/cakephp/admin/rights', '/admin/rights'],
            ['/cakephp/admin/users/edit', '/admin/users/edit/1'],
        ];
        $urlsMatchFalse = [
            ['https://github.com', '/'],
            ['/pages/url', '/pages'],
            ['/pages/url', '/pages/something'],
            [[], ['controller' => 'pages', 'action' => 'view']],
            ['/cakephp/admin/users/edit/1', '/admin/users/edit'],
        ];
        $this->_testCompare($urlsMatchTrue, $urlsMatchFalse);

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
        $this->_testCompare([
            ['/pages/faq', []],
            [['controller' => 'Pages', 'action' => 'display', 'faq'], []],
            ['/pages', []],
        ], [
            ['/pages/credits', []],
        ]);
    }

    public function testCompareCustom()
    {
        $tests = [
            [['controller' => 'Apartments', 'action' => 'index'], '/apartments/edit'],
        ];
        $this->_testCompare($tests, [], ['action' => false, 'pass' => false]);
    }
}
