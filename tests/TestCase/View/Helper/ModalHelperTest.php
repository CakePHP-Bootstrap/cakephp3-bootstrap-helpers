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

use Bootstrap\View\Helper\ModalHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class ModalHelperTest extends TestCase
{
    /**
     * Instance of ModalHelper.
     *
     * @var ModalHelper
     */
    public $modal;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html',
        ]);
        $this->modal = new ModalHelper($view);
    }

    public function testCreate()
    {
        $title = "My Modal";
        $id = "myModalId";
        // Test standard create without ID
        $result = $this->modal->create($title);
        $expected = [
            ['div' => [
                'tabindex' => '-1',
                'role' => 'dialog',
                'class' => 'modal fade',
                'aria-hidden' => 'true',
            ]],
            ['div' => [
                'class' => 'modal-dialog',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
            ]],
            $title,
            '/h5',
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-label' => __('Close'),
            ]],
            ['span' => ['aria-hidden' => 'true']], '&times;', '/span',
            '/button',
            '/div',
            ['div' => [
                'class' => 'modal-body',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test standard create with ID
        $result = $this->modal->create($title, ['id' => $id]);
        $expected = [
            ['div' => [
                'id' => $id,
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'aria-labelledby' => $id . 'Label',
                'class' => 'modal fade',
            ]],
            ['div' => [
                'class' => 'modal-dialog',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
                'id' => $id . 'Label',
            ]],
            $title,
            '/h5',
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-label' => __('Close'),
            ]],
            ['span' => ['aria-hidden' => 'true']], '&times;', '/span',
            '/button',
            '/div',
            ['div' => [
                'class' => 'modal-body',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Create without body
        $result =
        $this->modal->create($title, ['id' => $id, 'body' => false]);
        $expected = [
            ['div' => [
                'id' => $id,
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'aria-labelledby' => $id . 'Label',
                'class' => 'modal fade',
            ]],
            ['div' => [
                'class' => 'modal-dialog',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
                'id' => $id . 'Label',
            ]],
            $title,
            '/h5',
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-label' => __('Close'),
            ]],
            ['span' => ['aria-hidden' => 'true']], '&times;', '/span',
            '/button',
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Create without close
        $result = $this->modal->create($title, ['id' => $id, 'close' => false]);
        $expected = [
            ['div' => [
                'id' => $id,
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'aria-labelledby' => $id . 'Label',
                'class' => 'modal fade',
            ]],
            ['div' => [
                'class' => 'modal-dialog',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
                'id' => $id . 'Label',
            ]],
            $title,
            '/h5',
            '/div',
            ['div' => [
                'class' => 'modal-body',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Create without title / no id
        $result = $this->modal->create();
        $expected = [
            ['div' => [
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'class' => 'modal fade',
            ]],
            ['div' => [
                'class' => 'modal-dialog',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test standard create with size
        $result = $this->modal->create($title, ['size' => 'lg']);
        $expected = [
            ['div' => [
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'class' => 'modal fade',
            ]],
            ['div' => [
                'class' => 'modal-dialog modal-lg',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
            ]],
            $title,
            '/h5',
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-label' => __('Close'),
            ]],
            ['span' => ['aria-hidden' => 'true']], '&times;', '/span',
            '/button',
            '/div',
            ['div' => [
                'class' => 'modal-body',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test standard create with custom size
        $result = $this->modal->create($title, ['size' => 'modal-big']);
        $expected = [
            ['div' => [
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'class' => 'modal fade',
            ]],
            ['div' => [
                'class' => 'modal-dialog modal-big',
                'role' => 'document',
            ]],
            ['div' => [
                'class' => 'modal-content',
            ]],
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
            ]],
            $title,
            '/h5',
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-label' => __('Close'),
            ]],
            ['span' => ['aria-hidden' => 'true']], '&times;', '/span',
            '/button',
            '/div',
            ['div' => [
                'class' => 'modal-body',
            ]],
        ];
        $this->assertHtml($expected, $result);
    }

    public function testHeader()
    {
        $content = 'Header';
        $extraclass = 'my-extra-class';
        // Test with HTML
        $result = $this->modal->header($content);
        $expected = [
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
            ]],
            $content,
            '/h5',
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-label' => __('Close'),
            ]],
            ['span' => ['aria-hidden' => 'true']], '&times;', '/span',
            '/button',
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test no close HTML
        $result = $this->modal->header($content, ['close' => false]);
        $expected = [
            ['div' => [
                'class' => 'modal-header',
            ]],
            ['h5' => [
                'class' => 'modal-title',
            ]],
            $content,
            '/h5',
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test option
        $result = $this->modal->header($content, ['close' => false, 'class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-header ' . $extraclass,
            ]],
            ['h5' => [
                'class' => 'modal-title',
            ]],
            $content,
            '/h5',
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test null first
        $result = $this->modal->header(null);
        $expected = [
            ['div' => [
                'class' => 'modal-header',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test option first
        $this->modal->create();
        $result = $this->modal->header(['class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-header ' . $extraclass,
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test aut close
        $this->modal->create($content);
        $result = $this->modal->header(['class' => $extraclass]);
        $expected = [
            '/div',
            ['div' => [
                'class' => 'modal-header ' . $extraclass,
            ]],
        ];
        $this->assertHtml($expected, $result);
    }

    public function testBody()
    {
        $content = 'Body';
        $extraclass = 'my-extra-class';
        // Test with HTML
        $result = $this->modal->body($content);
        $expected = [
            ['div' => [
                'class' => 'modal-body',
            ]],
            $content,
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test option
        $result = $this->modal->body($content, ['close' => false, 'class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-body ' . $extraclass,
            ]],
            $content,
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test null first
        $result = $this->modal->body(null);
        $expected = [
            ['div' => [
                'class' => 'modal-body',
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test option first
        $this->modal->create();
        $result = $this->modal->body(['class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-body ' . $extraclass,
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test aut close
        $this->modal->create();
        $this->modal->header(); // Unclosed part
        $result = $this->modal->body(['class' => $extraclass]);
        $expected = [
            '/div',
            ['div' => [
                'class' => 'modal-body ' . $extraclass,
            ]],
        ];
        $this->assertHtml($expected, $result);
    }

    public function testFooter()
    {
        $content = 'Footer';
        $extraclass = 'my-extra-class';
        // Test with HTML
        $result = $this->modal->footer($content);
        $expected = [
            ['div' => [
                'class' => 'modal-footer',
            ]],
            $content,
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test with Array
        $result = $this->modal->footer([$content, $content], ['class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-footer ' . $extraclass,
            ]],
            $content,
            $content,
            '/div',
        ];
        $this->assertHtml($expected, $result);
        // Test with null as first arg
        $result = $this->modal->footer(null, ['class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-footer ' . $extraclass,
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test with Options as first arg
        $this->modal->create();
        $result = $this->modal->footer(['class' => $extraclass]);
        $expected = [
            ['div' => [
                'class' => 'modal-footer ' . $extraclass,
            ]],
        ];
        $this->assertHtml($expected, $result);
        // Test with automatic close
        $this->modal->create($content);
        $result = $this->modal->footer();
        $expected = [
            '/div',
            ['div' => [
                'class' => 'modal-footer',
            ]],
        ];
        $this->assertHtml($expected, $result);
    }

    public function testEnd()
    {
        $result = $this->modal->end();
        // Standard close
        $expected = [
            '/div', '/div', '/div',
        ];
        $this->assertHtml($expected, $result);
        // Close open part
        $this->modal->create('Title'); // Create modal with open title
        $result = $this->modal->end();
        $expected = [
            '/div', '/div', '/div', '/div',
        ];
        $this->assertHtml($expected, $result);
    }
}
