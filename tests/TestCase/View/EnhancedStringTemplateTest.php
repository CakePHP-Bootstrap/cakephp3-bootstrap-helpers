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
namespace Bootstrap\Test\TestCase\View;

use Bootstrap\View\EnhancedStringTemplate;
use Cake\TestSuite\TestCase;

class EnhancedStringTemplateTest extends TestCase
{
    /**
     * Instance of EnhancedStringTemplate.
     *
     * @var EnhancedStringTemplate
     */
    public $templater;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->templater = new EnhancedStringTemplate();
    }

    public function test()
    {
        $this->templater->add([
            'test_default' => '<p{{attrs}}>{{content}}</p>',
            'test_attrs_class' => '<p class="test-class{{attrs.class}}"{{attrs}}>{{content}}</p>',
        ]);
        // Standard test
        $result = $this->templater->format('test_default', [
            'attrs' => ' id="test-id" class="test-class"',
            'content' => 'Hello World!',
        ]);
        $this->assertHtml([
            ['p' => [
                'id' => 'test-id',
                'class' => 'test-class',
            ]],
            'Hello World!',
            '/p',
        ], $result);
        // Test with class test
        $result = $this->templater->format('test_attrs_class', [
            'attrs' => ' id="test-id" class="test-class-2"',
            'content' => 'Hello World!',
        ]);
        $this->assertHtml([
            ['p' => [
                'id' => 'test-id',
                'class' => 'test-class test-class-2',
            ]],
            'Hello World!',
            '/p',
        ], $result);
        // Test with class test
        $result = $this->templater->format('test_attrs_class', [
            'attrs' => 'class="test-class-2" id="test-id"',
            'content' => 'Hello World!',
        ]);
        $this->assertHtml([
            ['p' => [
                'id' => 'test-id',
                'class' => 'test-class test-class-2',
            ]],
            'Hello World!',
            '/p',
        ], $result);
    }
}
