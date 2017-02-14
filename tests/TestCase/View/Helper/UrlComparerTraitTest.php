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
        Router::scope('/', function (RouteBuilder $routes) {
            $routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
            $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
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
