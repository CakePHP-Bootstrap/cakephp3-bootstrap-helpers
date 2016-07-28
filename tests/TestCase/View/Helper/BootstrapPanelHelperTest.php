<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapHtmlHelper;
use Bootstrap\View\Helper\BootstrapPanelHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapPanelHelperTest extends TestCase {

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->View->Html = new BootstrapHtmlHelper($this->View);
        $this->Panel = new BootstrapPanelHelper($this->View);
    }

    protected function reset () {
        $this->Panel->end();
    }

    public function testCreate () {
        $title = "My Modal";
        $id = "myModalId";
        // Test standard create with title
        $result = $this->Panel->create($title);
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
        $result = $this->Panel->create($title, ['no-body' => true]);
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
        $result = $this->Panel->create();
        $this->assertHtml([
            ['div' => [
                'class' => 'panel panel-default'
            ]]
        ], $result);
        $this->reset();
    }

    public function testHeader () {
        $content = 'Header';
        $htmlContent = '<b>'.$content.'</b>';
        $extraclass = 'my-extra-class';

        // Simple test
        $result = $this->Panel->header($content);
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
        $result = $this->Panel->header($htmlContent);
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
        $result = $this->Panel->header($htmlContent, ['escape' => false]);
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
        $result = $this->Panel->header($iconContent);
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
        $this->Panel->create(null, ['collapsible' => true]);
        $result = $this->Panel->header($htmlContent);
        $this->assertHtml([
            ['div' => [
                'role'  => 'tab',
                'id'    => 'heading-0',
                'class' => 'panel-heading'
            ]],
            ['h4' => [
                'class' => 'panel-title'
            ]],
            ['a' => [
                'href'          => '#collapse-0',
                'data-toggle'   => 'collapse',
                'aria-expanded' => 'true',
                'aria-controls' => '#collapse-0'
            ]],
            htmlspecialchars($htmlContent),
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should NOT be escaped)
        $this->Panel->create(null, ['collapsible' => true]);
        $result = $this->Panel->header($htmlContent, ['escape' => false]);
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
                'href'          => '#collapse-1',
                'data-toggle'   => 'collapse',
                'aria-expanded' => 'true',
                'aria-controls' => '#collapse-1'
            ]],
            ['b' => true], $content, '/b',
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with icon
        $iconContent = 'i:home Home';
        $this->Panel->create(null, ['collapsible' => true]);
        $result = $this->Panel->header($iconContent);
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
                'href'          => '#collapse-2',
                'data-toggle'   => 'collapse',
                'aria-expanded' => 'true',
                'aria-controls' => '#collapse-2'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-home',
                'aria-hidden' => 'true'
            ]], '/i', ' Home',
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();


    }

    public function testGroup () {

        $panelHeading = 'This is a panel heading';
        $panelContent = 'A bit of HTML code inside!';

        $result = '';
        $result .= $this->Panel->startGroup();
        $result .= $this->Panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->Panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->Panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->Panel->endGroup();
        $result .= $this->Panel->create($panelHeading);
        $result .= $panelContent;
        $result .= $this->Panel->end();

        $expected = [
            ['div' => [
                'id'                   => 'panelGroup-1',
                'role'                 => 'tablist',
                'aria-multiselectable' => true,
                'class'                => 'panel-group'
            ]],
        ];

        for ($i = 0; $i < 3; ++$i) {
            $expected = array_merge($expected, [
                ['div' => [
                    'class' => 'panel panel-default'
                ]],
                ['div' => [
                    'role'  => 'tab',
                    'id'    => 'heading-'.$i,
                    'class' => 'panel-heading'
                ]],
                ['h4' => [
                    'class' => 'panel-title'
                ]],
                ['a' => [
                    'href'          => '#collapse-'.$i,
                    'data-toggle'   => 'collapse',
                    'data-parent'   => '#panelGroup-1',
                    'aria-expanded' => $i == 0 ? 'true' : 'false',
                    'aria-controls' => '#collapse-'.$i
                ]],
                $panelHeading,
                '/a',
                '/h4',
                '/div',
                ['div' => [
                    'id'              => 'collapse-'.$i,
                    'role'            => 'tabpanel',
                    'aria-labelledby' => 'heading-'.$i,
                    'class'           => 'panel-collapse collapse'.($i ? '' : ' in'),

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

        $this->assertHtml($expected, $result);

    }

}