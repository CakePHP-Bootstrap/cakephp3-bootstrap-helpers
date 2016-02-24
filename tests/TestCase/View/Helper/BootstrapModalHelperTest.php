<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapModalHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapModalHelperTest extends TestCase {

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->Modal = new BootstrapModalHelper ($this->View);
    }

    public function testCreate () {
        $title = "My Modal";
        $id = "myModalId";
        // Test standard create without ID
        $result = $this->Modal->create($title);
        $this->assertHtml([
            ['div' => [
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'class' => 'modal fade'
            ]],
            ['div' => [
                'class' => 'modal-dialog'
            ]],
            ['div' => [
                'class' => 'modal-content'
            ]],
            ['div' => [
                'class' => 'modal-header'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-hidden' => 'true'
            ]],
            '&times;',
            '/button',
            ['h4' => [
                'class' => 'modal-title'
            ]],
            $title,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'modal-body'
            ]]
        ], $result);
        // Test standard create with ID
        $result = $this->Modal->create($title, ['id' => $id]);
        $this->assertHtml([
            ['div' => [
                'id' => $id,
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'aria-labelledby' => $id.'Label',
                'class' => 'modal fade'
            ]],
            ['div' => [
                'class' => 'modal-dialog'
            ]],
            ['div' => [
                'class' => 'modal-content'
            ]],
            ['div' => [
                'class' => 'modal-header'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-hidden' => 'true'
            ]],
            '&times;',
            '/button',
            ['h4' => [
                'class' => 'modal-title',
                'id' => $id.'Label'
            ]],
            $title,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'modal-body'
            ]]
        ], $result);
        // Create without body
        $result = $this->Modal->create($title, ['id' => $id, 'body' => false]);
        $this->assertHtml([
            ['div' => [
                'id' => $id,
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'aria-labelledby' => $id.'Label',
                'class' => 'modal fade'
            ]],
            ['div' => [
                'class' => 'modal-dialog'
            ]],
            ['div' => [
                'class' => 'modal-content'
            ]],
            ['div' => [
                'class' => 'modal-header'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-hidden' => 'true'
            ]],
            '&times;',
            '/button',
            ['h4' => [
                'class' => 'modal-title',
                'id' => $id.'Label'
            ]],
            $title,
            '/h4',
            '/div'
        ], $result);
        // Create without close
        $result = $this->Modal->create($title, ['id' => $id, 'close' => false]);
        $this->assertHtml([
            ['div' => [
                'id' => $id,
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'aria-labelledby' => $id.'Label',
                'class' => 'modal fade'
            ]],
            ['div' => [
                'class' => 'modal-dialog'
            ]],
            ['div' => [
                'class' => 'modal-content'
            ]],
            ['div' => [
                'class' => 'modal-header'
            ]],
            ['h4' => [
                'class' => 'modal-title',
                'id' => $id.'Label'
            ]],
            $title,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'modal-body'
            ]]
        ], $result);
        // Create without title / no id
        $result = $this->Modal->create();
        $this->assertHtml([
            ['div' => [
                'tabindex' => '-1',
                'role' => 'dialog',
                'aria-hidden' => 'true',
                'class' => 'modal fade'
            ]],
            ['div' => [
                'class' => 'modal-dialog'
            ]],
            ['div' => [
                'class' => 'modal-content'
            ]]
        ], $result);
    }

    public function testHeader () {
        $content = 'Header';
        $extraclass = 'my-extra-class';
        // Test with HTML
        $result = $this->Modal->header($content);
        $this->assertHtml([
            ['div' => [
                'class' => 'modal-header'
            ]],
            ['button' => [
                'type' => 'button',
                'class' => 'close',
                'data-dismiss' => 'modal',
                'aria-hidden' => 'true'
            ]],
            '&times;',
            '/button',
            ['h4' => [
                'class' => 'modal-title'
            ]],
            $content,
            '/h4',
            '/div'
        ], $result);
        // Test no close HTML
        $result = $this->Modal->header($content, ['close' => false]);
        $this->assertHtml([
            ['div' => [
                'class' => 'modal-header'
            ]],
            ['h4' => [
                'class' => 'modal-title'
            ]],
            $content,
            '/h4',
            '/div'
        ], $result);
        // Test option
        $result = $this->Modal->header($content, ['close' => false, 'class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-header'
            ]],
            ['h4' => [
                'class' => 'modal-title'
            ]],
            $content,
            '/h4',
            '/div'
        ], $result);
        // Test null first
        $result = $this->Modal->header(null);
        $this->assertHtml([
            ['div' => [
                'class' => 'modal-header'
            ]]
        ], $result);
        // Test option first
        $this->Modal->create();
        $result = $this->Modal->header(['class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => $extraclass.' modal-header'
            ]]
        ], $result);
        // Test aut close
        $this->Modal->create($content);
        $result = $this->Modal->header(['class' => $extraclass]);
        $this->assertHtml([
            '/div',
            ['div' => [
                'class' => $extraclass.' modal-header'
            ]]
        ], $result);
    }
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

}