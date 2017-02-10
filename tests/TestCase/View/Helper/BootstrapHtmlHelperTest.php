<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapHtmlHelperTest extends TestCase {

    /**
     * Instance of BootstrapHtmlHelper.
     *
     * @var BootstrapHtmlHelper
     */
    public $html;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $this->html = new BootstrapHtmlHelper($view);
    }

    public function testIcon() {

        // Default icon
        $result = $this->html->icon('home', [
            'id' => 'my-id',
            'class' => 'my-class'
        ]);
        $expected = [
            ['i' => [
                'aria-hidden' => 'true',
                'class' => 'glyphicon glyphicon-home my-class',
                'id' => 'my-id'
            ]],
            '/i'
        ];
        $this->assertHtml($expected, $result);

        // Custom templates
        $oldTemplates = $this->html->templates();
        $this->html->templates([
            'icon' => '<span class="fa fa-{{type}}{{attrs.class}}" data-type="{{type}}"{{attrs}}>{{inner}}</span>'
        ]);
        $result = $this->html->icon('home', [
            'id' => 'my-id',
            'class' => 'my-class'
        ]);
        $expected = [
            ['span' => [
                'class' => 'fa fa-home my-class',
                'data-type' => 'home',
                'id' => 'my-id'
            ]],
            '/span'
        ];
        // With template variables
        $this->assertHtml($expected, $result);
        $result = $this->html->icon('home', [
            'id' => 'my-id',
            'class' => 'my-class',
            'templateVars' => [
                'inner' => 'inner home'
            ]
        ]);
        $expected = [
            ['span' => [
                'class' => 'fa fa-home my-class',
                'data-type' => 'home',
                'id' => 'my-id'
            ]],
            'inner home',
            '/span'
        ];
        $this->assertHtml($expected, $result);
        $this->html->templates($oldTemplates);

    }

    public function testLabel() {
        $content = 'My Label';
        // Standard test
        $this->assertHtml([
            ['span' => [
                'class' => 'label label-default'
            ]],
            'My Label',
            '/span'
        ], $this->html->label($content));
        // Type
        $this->assertHtml([
            ['span' => [
                'class' => 'label label-primary'
            ]],
            'My Label',
            '/span'
        ], $this->html->label($content, 'primary'));
        // Type + Options
        $options = [
            'class' => 'my-label-class',
            'id'    => 'my-label-id'
        ];
        $this->assertHtml([
            ['span' => [
                'class' => 'label label-primary '.$options['class'],
                'id'    => $options['id']
            ]],
            'My Label',
            '/span'
        ], $this->html->label($content, 'primary', $options));
        // Only options
        $options = [
            'class' => 'my-label-class',
            'id'    => 'my-label-id',
            'type'  => 'primary'
        ];
        $this->assertHtml([
            ['span' => [
                'class' => 'label label-primary '.$options['class'],
                'id'    => $options['id']
            ]],
            'My Label',
            '/span'
        ], $this->html->label($content, $options));
    }

    public function testAlert() {

        // Default
        $result = $this->html->alert('Alert');
        $expected = [
            ['div' => [
                'class' => 'alert alert-warning alert-dismissible',
                'role' => 'alert'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'alert',
                'aria-label' => 'Close'
            ]], ['span' => ['aria-hidden' => 'true']], '&times;', '/span', '/button',
            'Alert', '/div'
        ];
        $this->assertHtml($expected, $result);

        // Custom type
        $result = $this->html->alert('Alert', 'primary');
        $expected = [
            ['div' => [
                'class' => 'alert alert-primary alert-dismissible',
                'role' => 'alert'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'alert',
                'aria-label' => 'Close'
            ]], ['span' => ['aria-hidden' => 'true']], '&times;', '/span', '/button',
            'Alert', '/div'
        ];
        $this->assertHtml($expected, $result);

        // Custom attributes
        $result = $this->html->alert('Alert', 'primary', [
            'class' => 'my-class',
            'id' => 'my-id'
        ]);
        $expected = [
            ['div' => [
                'class' => 'alert alert-primary my-class alert-dismissible',
                'role' => 'alert',
                'id' => 'my-id'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'alert',
                'aria-label' => 'Close'
            ]], ['span' => ['aria-hidden' => 'true']], '&times;', '/span', '/button',
            'Alert', '/div'
        ];
        $this->assertHtml($expected, $result);

        // Non dismissible
        $result = $this->html->alert('Alert', 'primary', [
            'class' => 'my-class',
            'id' => 'my-id',
            'close' => false
        ]);
        $expected = [
            ['div' => [
                'class' => 'alert alert-primary my-class',
                'role' => 'alert',
                'id' => 'my-id'
            ]],
            'Alert', '/div'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testTooltip() {
        // Default test
        $result = $this->html->tooltip('Content', 'Tooltip');
        $expected = [
            ['span' => [
                'data-toggle' => 'tooltip',
                'data-placement' => 'right',
                'title' => 'Tooltip'
            ]], 'Content', '/span'
        ];
        $this->assertHtml($expected, $result);
    }

    public function testProgress() {
        // Default test
        $result = $this->html->progress(20);
        $expected = [
            ['div' => ['class' => 'progress']],
            ['div' => [
                'class' => 'progress-bar progress-bar-primary',
                'role' => 'progressbar',
                'aria-valuenow' => 20,
                'aria-valuemin' => 0,
                'aria-valuemax' => 100,
                'style' => 'width: 20%;'
            ]],
            ['span' => ['class' => 'sr-only']], '20%', '/span',
            '/div',
            '/div'
        ];
        $this->assertHtml($expected, $result);

        // Multiple bars
        $result = $this->html->progress([
            ['width' => 20, 'class' => 'my-class'],
            ['width' => 15, 'id' => 'my-id'],
            ['width' => 10, 'active' => true]
        ], ['striped' => true]);
        $expected = [
            ['div' => ['class' => 'progress']],
            ['div' => [
                'class' => 'progress-bar progress-bar-primary my-class progress-bar-striped',
                'role' => 'progressbar',
                'aria-valuenow' => 20,
                'aria-valuemin' => 0,
                'aria-valuemax' => 100,
                'style' => 'width: 20%;'
            ]],
            ['span' => ['class' => 'sr-only']], '20%', '/span',
            '/div',
            ['div' => [
                'class' => 'progress-bar progress-bar-primary progress-bar-striped',
                'role' => 'progressbar',
                'aria-valuenow' => 15,
                'aria-valuemin' => 0,
                'aria-valuemax' => 100,
                'style' => 'width: 15%;',
                'id' => 'my-id'
            ]],
            ['span' => ['class' => 'sr-only']], '15%', '/span',
            '/div',
            ['div' => [
                'class' => 'progress-bar progress-bar-primary progress-bar-striped active',
                'role' => 'progressbar',
                'aria-valuenow' => 10,
                'aria-valuemin' => 0,
                'aria-valuemax' => 100,
                'style' => 'width: 10%;'
            ]],
            ['span' => ['class' => 'sr-only']], '10%', '/span',
            '/div',
            '/div'
        ];
        $this->assertHtml($expected, $result);
    }

}
