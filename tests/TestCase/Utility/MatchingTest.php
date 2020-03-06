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
namespace Bootstrap\Test\TestCase\Utility;

use Bootstrap\Utility\Matching;
use Cake\TestSuite\TestCase;

class MatchingTest extends TestCase
{
    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testMatchTag()
    {
        // no match
        $this->assertFalse(
            Matching::matchTag('a', '<div class="cl"><a href="#">Link</a></div>')
        );
        $this->assertFalse(
            Matching::matchTag('a', '<a href="#">Link</a></div>')
        );
        $this->assertFalse(
            Matching::matchTag('a', '<div class="cl"><a href="#">Link</a>')
        );
        $this->assertFalse(
            Matching::matchTag('a', 'a href="#">Link</a')
        );
        $this->assertFalse(
            Matching::matchTag('a', '<a href="#"a>')
        );
        $this->assertFalse(
            Matching::matchTag('a', '<a href="#">Link<a>')
        );

        // match
        $this->assertTrue(
            Matching::matchTag('a', '<a>Link</a>')
        );
        $this->assertTrue(
            Matching::matchTag('a', '  <a class="cl">Link</a>')
        );
        $this->assertTrue(
            Matching::matchTag('a', '<a class="cl">Link</a  >  ')
        );
        $this->assertTrue(
            Matching::matchTag('div', '<div class="cl">Content</div>')
        );
        $this->assertTrue(
            Matching::matchTag('div', '<div class="cl">Content</div>')
        );

        // attrs
        Matching::matchTag('a', '<a>Link</a>', $content, $attrs);
        $this->assertEquals($content, 'Link');
        $this->assertEquals($attrs, []);

        Matching::matchTag(
            'div',
            '<div class="my-class" id="my-id">Here is a link <a href="#">Link 1</a> inside.</div>',
            $content,
            $attrs
        );
        $this->assertEquals($content, 'Here is a link <a href="#">Link 1</a> inside.');
        $this->assertEquals($attrs, [
            'class' => 'my-class',
            'id' => 'my-id',
        ]);
    }

    public function testMatchAttribute()
    {
        // no match
        $this->assertTrue(
            Matching::matchAttribute('class', 'cl', '<div class="cl"><a href="#">Link</a></div>')
        );
        $this->assertTrue(
            Matching::matchAttribute('id', 'my-id', '<div class="cl" id="my-id"><a href="#">Link</a></div>')
        );
        $this->assertTrue(
            Matching::matchAttribute('required', 'true', '<div class="cl" required="true"><a href="#">Link</a></div>')
        );
    }
}
