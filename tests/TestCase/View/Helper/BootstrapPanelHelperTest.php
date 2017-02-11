<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Bootstrap\View\Helper\BootstrapPanelHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapPanelHelperTest extends TestCase {

    /**
     * Instance of BootstrapPanelHelper.
     *
     * @var BootstrapPanelHelper
     */
    public $panel;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $html = new BootstrapHtmlHelper($view);
        $this->panel = new BootstrapPanelHelper($view);
    }

    protected function reset() {
        $this->panel->end();
    }

    public function testCreate() {
        $title = "My Modal";
        $id = "myModalId";
        // Test standard create with title
        $result = $this->panel->create($title);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel panel-default'
            ]],
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            $title,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'panel-body'
            ]]
        ], $result);
        $this->reset();
        // Test standard create with title
        $result = $this->panel->create($title, ['body' => false]);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel panel-default'
            ]],
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            $title,
            '/h4',
            '/div'
        ], $result);
        $this->reset();
        // Test standard create without title
        $result = $this->panel->create();
        $this->assertHtml([
            ['div' => [
                'class' => 'panel panel-default'
            ]]
        ], $result);
        $this->reset();
    }

    public function testHeader() {
        $content = 'Header';
        $htmlContent = '<b>'.$content.'</b>';
        $extraclass = 'my-extra-class';

        // Simple test
        $result = $this->panel->header($content);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            $content,
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should be escaped)
        $result = $this->panel->header($htmlContent);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            htmlspecialchars($htmlContent),
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should NOT be escaped)
        $result = $this->panel->header($htmlContent, ['escape' => false]);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            ['b' => true], $content, '/b',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with icon
        $iconContent = 'i:home Home';
        $result = $this->panel->header($iconContent);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-home',
                'aria-hidden' => 'true'
            ]], '/i', ' Home',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with collapsible (should NOT be escaped)

        // Test with HTML content (should be escaped)
        $tmp = $this->panel->create(null, ['collapsible' => true]);
        $result = $this->panel->header($htmlContent);
        $this->assertHtml([
            ['div' => [
                'class' => 'panel-heading',
                'role'  => 'tab',
                'id'    => 'heading-0'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            ['a' => [
                'role'          => 'button',
                'data-toggle'   => 'collapse',
                'href'          => '#collapse-0',
                'aria-expanded' => 'true',
                'aria-controls' => 'collapse-0'
            ]],
            htmlspecialchars($htmlContent),
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should NOT be escaped)
        $this->panel->create(null, ['collapsible' => true]);
        $result = $this->panel->header($htmlContent, ['escape' => false]);
        $this->assertHtml([
            ['div' => [
                'role'  => 'tab',
                'id'    => 'heading-1',
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            ['a' => [
                'role'          => 'button',
                'data-toggle'   => 'collapse',
                'href'          => '#collapse-1',
                'aria-expanded' => 'true',
                'aria-controls' => 'collapse-1'
            ]],
            ['b' => true], $content, '/b',
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with icon
        $iconContent = 'i:home Home';
        $this->panel->create(null, ['collapsible' => true]);
        $result = $this->panel->header($iconContent);
        $this->assertHtml([
            ['div' => [
                'role'  => 'tab',
                'id'    => 'heading-2',
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            ['a' => [
                'role'          => 'button',
                'data-toggle'   => 'collapse',
                'href'          => '#collapse-2',
                'aria-expanded' => 'true',
                'aria-controls' => 'collapse-2'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-home',
                'aria-hidden' => 'true'
            ]], '/i', ' Home',
            '/a',
            '/h4',
            '/div'
        ], $result, true);
        $this->reset();
    }

    public function testGroup() {

        $panelHeading = 'This is a panel heading';
        $panelContent = 'A bit of HTML code inside!';

        $result = '';
        $result .= $this->panel->startGroup();
        $result .= $this->panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->panel->endGroup();
        $result .= $this->panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->panel->end();

        $expected = [
            ['div' => [
                'class'                => 'panel-group',
                'role'                 => 'tablist',
                'aria-multiselectable' => 'true',
                'id'                   => 'panelGroup-1'
            ]]
        ];

        for ($i = 0; $i < 3; ++$i) {
            $expected = array_merge($expected, [
                ['div' => [
                    'class' => 'panel panel-default'
                ]],
                ['div' => [
                    'class' => 'panel-heading',
                    'role'  => 'tab',
                    'id'    => 'heading-'.$i
                ]],
                ['h4' => [
                    'class' => 'panel-title'
                ]],
                ['a' => [
                    'role'          => 'button',
                    'data-toggle'   => 'collapse',
                    'href'          => '#collapse-'.$i,
                    'aria-expanded' => $i ? 'false' : 'true',
                    'aria-controls' => 'collapse-'.$i,
                    'data-parent'   => '#panelGroup-1'
                ]],
                $panelHeading,
                '/a',
                '/h4',
                '/div',
                ['div' => [
                    'class'           => 'panel-collapse collapse'.($i ? '' : ' in'),
                    'role'            => 'tabpanel',
                    'aria-labelledby' => 'heading-'.$i,
                    'id'              => 'collapse-'.$i
                ]],
                ['div' => [
                    'class' => 'panel-body'
                ]],
                $panelContent,
                '/div',
                '/div',
                '/div'
            ]);
        }

        $expected = array_merge($expected, ['/div']);

        $expected = array_merge($expected, [
            ['div' => [
                'class' => 'panel panel-default'
            ]],
            ['div' => [
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            $panelHeading,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'panel-body'
            ]],
            $panelContent,
            '/div',
            '/div'
        ]);

        $this->assertHtml($expected, $result, true);
    }

}
