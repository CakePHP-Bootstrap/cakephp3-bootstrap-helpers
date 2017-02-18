<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\UrlComparerTrait;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\TestSuite\TestCase;

class PublicUrlComparerTrait {

    use UrlComparerTrait;

    public function normalize($url, $pass = false) {
        return $this->_normalize($url, $pass);
    }

};

class UrlComparerTraitTest extends TestCase {

    /**
     * Instance of PublicUrlComparerTrait.
     *
     * @var PublicUrlComparerTrait
     */
    public $trait;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
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

    public function testNormalizedWithoutPass() {
        $tests = [
            ['/pages/test', '/pages'], // normalize as /pages due to (2)
            ['/users/login', '/users/login'],
            ['/users/login/whatever?query=no', '/users/login'],
            ['/pages/display/test', '/pages'],
            ['/admin/users/login', '/admin/users/login'],
        ];
        foreach ($tests as $test) {
            list($lhs, $rhs) = $test;
            $nm = $this->trait->normalize($lhs);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
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
        $tests = [
            ['/pages', '/pages'],
            ['/pages/display/test', '/pages'],
            ['/pages/test', '/pages'], // normalize as /pages due to (2)
            ['/pages?query=no', '/pages'],
            ['/pages#anchor', '/pages'],
            ['/pages?query=no#anchor', '/pages'],
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
            ['http://localhost/cakephp/pages', '/pages'],
            ['http://localhost/cakephp/pages/display/test', '/pages'],
            ['http://localhost/cakephp/pages/test', '/pages'], // normalize as /pages due to (2)
            ['http://localhost/cakephp/pages?query=no', '/pages'],
            ['http://localhost/cakephp/pages#anchor', '/pages'],
            ['http://localhost/cakephp/pages?query=no#anchor', '/pages'],
            ['http://localhost/cakephp/admin/users/login', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login?query=no', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login#anchor', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever?query=no#anchor', '/admin/users/login'],
            ['http://github.com/cakephp/admin/users', null],
            ['http://localhost/notcakephp', null],
            ['http://localhost/somewhere/cakephp', null]

        ];
        foreach ($tests as $test) {
            list($lhs, $rhs) = $test;
            $nm = $this->trait->normalize($lhs);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
    }

    public function testNormalizedWithPass() {
        $tests = [
            ['/pages/test', '/pages/test'], // normalize as /pages due to (2)
            ['/users/login', '/users/login'],
            ['/users/login/whatever?query=no', '/users/login/whatever'],
            ['/pages/display/test', '/pages/display/test'],
            ['/admin/users/login', '/admin/users/login'],
        ];
        foreach ($tests as $test) {
            list($lhs, $rhs) = $test;
            $nm = $this->trait->normalize($lhs, true);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
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
        $tests = [
            ['/pages', '/pages'],
            ['/pages/display/test', '/pages/display/test'],
            ['/pages/test', '/pages/test'], // normalize as /pages due to (2)
            ['/pages?query=no', '/pages'],
            ['/pages#anchor', '/pages'],
            ['/pages?query=no#anchor', '/pages'],
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
            ['http://localhost/cakephp/pages', '/pages'],
            ['http://localhost/cakephp/pages/display/test', '/pages/display/test'],
            ['http://localhost/cakephp/pages/test', '/pages/test'],
            ['http://localhost/cakephp/pages?query=no', '/pages'],
            ['http://localhost/cakephp/pages#anchor', '/pages'],
            ['http://localhost/cakephp/pages?query=no#anchor', '/pages'],
            ['http://localhost/cakephp/admin/users/login', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever', '/admin/users/login/whatever'],
            ['http://localhost/cakephp/admin/users/login?query=no', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login#anchor', '/admin/users/login'],
            ['http://localhost/cakephp/admin/users/login/whatever?query=no#anchor', '/admin/users/login/whatever'],
            ['http://github.com/cakephp/admin/users', null],
            ['http://localhost/notcakephp', null],
            ['http://localhost/somewhere/cakephp', null]

        ];
        foreach ($tests as $test) {
            list($lhs, $rhs) = $test;
            $nm = $this->trait->normalize($lhs, true);
            $this->assertTrue($nm == $rhs, sprintf("%s is not normalized as %s but %s.", $lhs, $rhs, $nm));
        }
    }

    public function _testCompare($matchTrue, $matchFalse) {
        foreach ($matchTrue as $urls) {
            list($lhs, $rhs) = $urls;
            $this->assertTrue($this->trait->compareUrls($lhs, $rhs), sprintf('%s != %s', Router::url($lhs), Router::url($rhs)));
        }
        foreach ($matchFalse as $urls) {
            list($lhs, $rhs) = $urls;
            $this->assertTrue(!$this->trait->compareUrls($lhs, $rhs), sprintf('%s == %s', Router::url($lhs), Router::url($rhs)));
        }
    }

    public function testCompare() {
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
        ];
        $urlsMatchFalse = [
            ['https://github.com', '/'],
            ['/pages/url', '/pages'],
            ['/pages/url', '/pages/something']
        ];
        $this->_testCompare($urlsMatchTrue, $urlsMatchFalse);
    }

    public function testFullBase() {
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
            ['/cakephp/admin/users/edit', '/admin/users/edit/1']
        ];
        $urlsMatchFalse = [
            ['https://github.com', '/'],
            ['/pages/url', '/pages'],
            ['/pages/url', '/pages/something'],
            [[], ['controller' => 'pages', 'action' => 'view']],
            ['/cakephp/admin/users/edit/1', '/admin/users/edit']
        ];
        $this->_testCompare($urlsMatchTrue, $urlsMatchFalse);
    }

};
