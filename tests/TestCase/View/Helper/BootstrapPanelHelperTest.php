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
                'class' => 'glyphicon glyphicon-home'
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
                'aria-expanded' => true,
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
                'aria-expanded' => true,
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
                'aria-expanded' => true,
                'aria-controls' => '#collapse-2'
            ]],
            ['i' => [
                'class' => 'glyphicon glyphicon-home'
            ]], '/i', ' Home',
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();


    }

                          /*
                            public function testBody () {
        $content = 'Body';
        $extraclass = 'my-extra-class';
        // Test with HTML
        $result = $this->Modal->body($content);
        $this->assertHtml([
            ['div' => [
                'class' => 'modal-body'
            ]],
            $content,
            '/div'
        ], $result);
        // Test option
        $result = $this->Modal->body($content, ['close' => false, 'class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-body'
            ]],
            $content,
            '/div'
        ], $result);
        // Test null first
        $result = $this->Modal->body(null);
        $this->assertHtml([
            ['div' => [
                'class' => 'modal-body'
            ]]
        ], $result);
        // Test option first
        $this->Modal->create();
        $result = $this->Modal->body(['class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-body'
            ]]
        ], $result);
        // Test aut close
        $this->Modal->create();
        $this->Modal->header(); // Unclosed part
        $result = $this->Modal->body(['class' => $extraclass]);
        $this->assertHtml([
            '/div',
            ['div' => [
                'class' => $extraclass.' modal-body'
            ]]
        ], $result);
    }

    public function testFooter () {
        $content = 'Footer';
        $extraclass = 'my-extra-class';
        // Test with HTML
        $result = $this->Modal->footer($content);
        $this->assertHtml([
            ['div' => [
                'class' => 'modal-footer'
            ]],
            $content,
            '/div'
        ], $result);
        // Test with Array
        $result = $this->Modal->footer([$content, $content], ['class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-footer'
            ]],
            $content,
            $content,
            '/div'
        ], $result);
        // Test with null as first arg
        $result = $this->Modal->footer(null, ['class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-footer'
            ]]
        ], $result);
        // Test with Options as first arg
        $this->Modal->create();
        $result = $this->Modal->footer(['class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-footer'
            ]]
        ], $result);
        // Test with automatic close
        $this->Modal->create($content);
        $result = $this->Modal->footer();
        $this->assertHtml([
            '/div',
            ['div' => [
                'class' => 'modal-footer'
            ]]
        ], $result);
    }

    public function testEnd() {
        $result = $this->Modal->end();
        // Standard close
        $this->assertHtml([
            '/div', '/div', '/div'
        ], $result);
        // Close open part
        $this->Modal->create('Title'); // Create modal with open title
        $result = $this->Modal->end();
        $this->assertHtml([
            '/div', '/div', '/div', '/div'
        ], $result);
    }
    */


}