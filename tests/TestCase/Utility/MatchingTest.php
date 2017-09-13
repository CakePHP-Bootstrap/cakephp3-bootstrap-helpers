<?php

namespace Bootstrap\Test\TestCase\Utility;

use Bootstrap\Utility\Matching;
use Cake\TestSuite\TestCase;

class MatchingTest extends TestCase {

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->match = new Matching();
    }

    public function testMatchTag() {
        // no match
        $this->assertFalse(
            $this->match->matchTag('a', '<div class="cl"><a href="#">Link</a></div>'));
        $this->assertFalse(
            $this->match->matchTag('a', '<a href="#">Link</a></div>'));
        $this->assertFalse(
            $this->match->matchTag('a', '<div class="cl"><a href="#">Link</a>'));
        $this->assertFalse(
            $this->match->matchTag('a', 'a href="#">Link</a'));
        $this->assertFalse(
            $this->match->matchTag('a', '<a href="#"a>'));
        $this->assertFalse(
            $this->match->matchTag('a', '<a href="#">Link<a>'));

        // match
        $this->assertTrue(
            $this->match->matchTag('a', '<a>Link</a>'));
        $this->assertTrue(
            $this->match->matchTag('a', '  <a class="cl">Link</a>'));
        $this->assertTrue(
            $this->match->matchTag('a', '<a class="cl">Link</a  >  '));
        $this->assertTrue(
            $this->match->matchTag('div', '<div class="cl">Content</div>'));
        $this->assertTrue(
            $this->match->matchTag('div', '<div class="cl">Content</div>'));

        // attrs
        $this->match->matchTag('a', '<a>Link</a>', $content, $attrs);
        $this->assertEquals($content, 'Link');
        $this->assertEquals($attrs, []);

        $this->match->matchTag('div', '<div class="my-class" id="my-id">Here is a link <a href="#">Link 1</a> inside.</div>',
                               $content, $attrs);
        $this->assertEquals($content, 'Here is a link <a href="#">Link 1</a> inside.');
        $this->assertEquals($attrs, [
            'class' => 'my-class',
            'id' => 'my-id'
        ]);
    }

    public function testMatchAttribute() {
        // no match
        $this->assertTrue(
            $this->match->matchAttribute('class', 'cl', '<div class="cl"><a href="#">Link</a></div>'));
        $this->assertTrue(
            $this->match->matchAttribute('id', 'my-id', '<div class="cl" id="my-id"><a href="#">Link</a></div>'));
        $this->assertTrue(
            $this->match->matchAttribute('required', 'true', '<div class="cl" required="true"><a href="#">Link</a></div>'));
    }

};
