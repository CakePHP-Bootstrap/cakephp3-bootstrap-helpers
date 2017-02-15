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

    public function normalize($url) {
        return $this->_normalize($url);
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
        $this->_urlsMatchTrue = [
            // Test root
            ['/', '/'],
            ['/', '/#anchor'],
            // Test connection
            ['/pages/test', '/pages/test#anchor'],
            ['/pages', '/pages?param=value'],
            ['/pages/test', ['controller' => 'Pages', 'action' => 'display']],
            ['/pages/test/id', ['controller' => 'Pages', 'action' => 'display']],
            // Controller routes
            ['/user/login', ['controller' => 'user', 'action' => 'login']],
            ['/user/login/myself?query=no', ['controller' => 'user', 'action' => 'login']],

        ];
        $this->_urlsMatchFalse = [
            ['https://github.com', '/']
        ];
        $this->trait = new PublicUrlComparerTrait();
    }

    public function _testNormalize() {
        $tests = [
            ['/pages/test', '/pages'], // normalize as /pages due to (2)
            ['/users/login', '/users/login'],
            ['/users/login/whatever?query=no', '/users/login'],
            ['/pages/display/test', '/pages'],
            ['/admin/users/login', '/admin/users/login']
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
            ['/pages/test', '/cakephp/pages'], // normalize as /pages due to (2)
            ['/users/login', '/cakephp/users/login'],
            ['/users/login/whatever?query=no', '/cakephp/users/login'],
            ['/pages/display/test', '/cakephp/pages'],
            ['/admin/users/login', '/cakephp/admin/users/login'],
            ['/cakephp/admin/rights', '/cakephp/admin/rights'],
            ['/cakephp/admin/users/edit/1', '/cakephp/admin/users/edit']
        ];
        foreach ($tests as $test) {
            list($lhs, $rhs) = $test;
            $nm = $this->trait->normalize($lhs);
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
        $this->_testCompare($this->_urlsMatchTrue, $this->_urlsMatchFalse);
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
        $matchTrue = array_merge($this->_urlsMatchTrue, [
            [[], ['controller' => 'pages', 'action' => 'view', '1']],
            [[], ['controller' => 'pages', 'action' => 'view']],
            [[], 'http://localhost/cakephp/pages/view/1'],
            [[], 'https://localhost/cakephp/pages/view/1'],
            [[], '/pages/view/1']
        ]);
        $matchFalse = $this->_urlsMatchFalse;
        $this->_testCompare($matchTrue, $matchFalse);
    }

};
