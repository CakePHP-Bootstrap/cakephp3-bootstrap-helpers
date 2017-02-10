<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\UrlComparerTrait;
use Cake\Routing\Router;
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
        $this->trait = new PublicUrlComparerTrait();
    }

    public function testCompare() {
        $cmpTrue = [
            ['/', '/'],
            ['/', '#anchor'],
            ['/', []],
            ['/pages/test', '/pages/test#anchor'],
            ['/pages', '/pages?param=value']
        ];
        foreach ($cmpTrue as $urls) {
            list($lhs, $rhs) = $urls;
            $this->assertTrue($this->trait->compareUrls($lhs, $rhs), sprintf('%s != %s', Router::url($lhs), Router::url($rhs)));
        }
    }

};