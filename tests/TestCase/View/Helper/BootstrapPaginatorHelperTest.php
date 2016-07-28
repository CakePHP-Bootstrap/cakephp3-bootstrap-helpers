<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Bootstrap\View\Helper\BootstrapPaginatorHelper;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapPaginatorHelperTest extends TestCase {

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->View = new View();
        $this->View->Html = new BootstrapHtmlHelper($this->View);
        $this->Paginator = new BootstrapPaginatorHelper($this->View);
        $this->Paginator->request = new Request();
        $this->Paginator->request->addParams([
            'paging' => [
                'Article' => [
                    'page' => 1,
                    'current' => 9,
                    'count' => 62,
                    'prevPage' => false,
                    'nextPage' => true,
                    'pageCount' => 7,
                    'sort' => null,
                    'direction' => null,
                    'limit' => null,
                ]
            ]
        ]);
        Configure::write('Routing.prefixes', []);
        Router::reload();
        Router::connect('/:controller/:action/*');
        Router::connect('/:plugin/:controller/:action/*');
    }

    public function testPrev () {
        $this->assertHtml([
            ['li' => [
                'class' => 'disabled'
            ]],
            ['a' => true], '&lt;', '/a',
            '/li'
        ], $this->Paginator->prev('<'));
        $this->assertHtml([
            ['li' => [
                'class' => 'disabled'
            ]],
            ['a' => true],
            ['i' => [
                'class' => 'glyphicon glyphicon-chevron-left',
                'aria-hidden' => 'true'
            ]],
            '/i', '/a', '/li'
        ], $this->Paginator->prev('i:chevron-left'));
    }

    public function testNext () {
        $this->assertHtml([
            ['li' => true],
            ['a' => [
                'href' => '/index?page=2'
            ]], '&gt;', '/a',
            '/li'
        ], $this->Paginator->next('>'));
        $this->assertHtml([
            ['li' => true],
            ['a' => [
                'href' => '/index?page=2'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-chevron-right',
                'aria-hidden' => 'true'
            ]],
            '/i', '/a', '/li'
        ], $this->Paginator->next('i:chevron-right'));
    }

};