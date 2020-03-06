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

use Bootstrap\View\Helper\PaginatorHelper;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\I18n\I18n;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class PaginatorHelperTest extends TestCase
{
    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \Cake\View\View
     */
    protected $View;

    /**
     * @var \Cake\View\Helper\PaginatorHelper
     */
    protected $Paginator;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('Config.language', 'eng');
        $request = new ServerRequest([
            'url' => '/',
            'params' => [
                'plugin' => null,
                'controller' => '',
                'action' => 'index',
            ],
        ]);
        $request = $request->withAttribute('paging', [
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
            ],
        ]);

        $this->View = new View($request);
        $this->View->loadHelper('Html', [
            'className' => 'Bootstrap.Html',
        ]);
        $this->Paginator = new PaginatorHelper($this->View);

        Router::reload();
        Router::connect('/:controller/:action/*');
        Router::connect('/:plugin/:controller/:action/*');
        Router::setRequest($request);

        $this->locale = I18n::getLocale();
    }

    public function testNumbers()
    {
        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 8,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers();
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=8&amp;page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);
        $result = $this->Paginator->numbers(['first' => 'first', 'last' => 'last']);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], 'first', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=8&amp;page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=15', 'class' => 'page-link']], 'last', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);
        $result = $this->Paginator->numbers(['first' => '2', 'last' => '8']);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '2', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=8&amp;page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=15', 'class' => 'page-link']], '8', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);
        $result = $this->Paginator->numbers(['first' => '8', 'last' => '8']);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=8&amp;page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=15', 'class' => 'page-link']], '8', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 1,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers();
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=1', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=2', 'class' => 'page-link']], '2', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=3', 'class' => 'page-link']], '3', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 14,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers();
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=13', 'class' => 'page-link']], '13', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=14&amp;page=14', 'class' => 'page-link']], '14', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=15', 'class' => 'page-link']], '15', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 2,
                'current' => 3,
                'count' => 27,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 9,
            ],
        ]));
        $result = $this->Paginator->numbers(['first' => 1]);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=2&amp;page=2', 'class' => 'page-link']], '2', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=3', 'class' => 'page-link']], '3', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $result = $this->Paginator->numbers(['last' => 1]);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=2&amp;page=2', 'class' => 'page-link']], '2', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=3', 'class' => 'page-link']], '3', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 15,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers(['first' => 1]);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=13', 'class' => 'page-link']], '13', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=14', 'class' => 'page-link']], '14', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=15&amp;page=15', 'class' => 'page-link']], '15', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 10,
                'current' => 3,
                'count' => 30,
                'prevPage' => false,
                'nextPage' => 2,
                'pageCount' => 15,
            ],
        ]));
        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=10&amp;page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=11', 'class' => 'page-link']], '11', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=12', 'class' => 'page-link']], '12', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=13', 'class' => 'page-link']], '13', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=14', 'class' => 'page-link']], '14', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=15', 'class' => 'page-link']], '15', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 6,
                'current' => 15,
                'count' => 623,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 42,
            ],
        ]));
        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=2', 'class' => 'page-link']], '2', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=3', 'class' => 'page-link']], '3', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=4', 'class' => 'page-link']], '4', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=5', 'class' => 'page-link']], '5', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=6&amp;page=6', 'class' => 'page-link']], '6', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=7', 'class' => 'page-link']], '7', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=8', 'class' => 'page-link']], '8', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=9', 'class' => 'page-link']], '9', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=10', 'class' => 'page-link']], '10', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=42', 'class' => 'page-link']], '42', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);

        $this->View->setRequest($this->View->getRequest()->withAttribute('paging', [
            'Client' => [
                'page' => 37,
                'current' => 15,
                'count' => 623,
                'prevPage' => 1,
                'nextPage' => 1,
                'pageCount' => 42,
            ],
        ]));
        $result = $this->Paginator->numbers(['first' => 1, 'last' => 1]);
        $expected = [
            ['ul' => ['class' => 'pagination']],
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index', 'class' => 'page-link']], '1', '/a', '/li',
            ['li' => ['class' => 'ellipsis disabled']], ['a' => []], '&hellip;', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=33', 'class' => 'page-link']], '33', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=34', 'class' => 'page-link']], '34', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=35', 'class' => 'page-link']], '35', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=36', 'class' => 'page-link']], '36', '/a', '/li',
            ['li' => ['class' => 'page-item active']], ['a' => ['href' => '/index?%3F%5Bpage%5D=37&amp;page=37', 'class' => 'page-link']], '37', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=38', 'class' => 'page-link']], '38', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=39', 'class' => 'page-link']], '39', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=40', 'class' => 'page-link']], '40', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=41', 'class' => 'page-link']], '41', '/a', '/li',
            ['li' => ['class' => 'page-item']], ['a' => ['href' => '/index?page=42', 'class' => 'page-link']], '42', '/a', '/li',
            '/ul',
        ];
        $this->assertHtml($expected, $result);
    }

    public function testPrev()
    {
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item disabled',
            ]],
            ['a' => [
                'class' => 'page-link',
            ]], '&lt;', '/a',
            '/li',
        ], $this->Paginator->prev('<'));
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item disabled',
            ]],
            ['a' => [
                'class' => 'page-link',
            ]],
            ['i' => [
                'class' => 'fa fa-chevron-left',
                'aria-hidden' => 'true',
            ]],
            '/i', '/a', '/li',
        ], $this->Paginator->prev('i:chevron-left'));
    }

    public function testNext()
    {
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item',
            ]],
            ['a' => [
                'href' => '/index?page=2',
                'class' => 'page-link',
            ]], '&gt;', '/a',
            '/li',
        ], $this->Paginator->next('>'));
        $this->assertHtml([
            ['li' => [
                'class' => 'page-item',
            ]],
            ['a' => [
                'href' => '/index?page=2',
                'class' => 'page-link',
            ]],
            ['i' => [
                'class' => 'fa fa-chevron-right',
                'aria-hidden' => 'true',
            ]],
            '/i', '/a', '/li',
        ], $this->Paginator->next('i:chevron-right'));
    }
}
