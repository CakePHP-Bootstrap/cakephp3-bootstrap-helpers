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

use Bootstrap\Utility\StackedStates;
use Cake\TestSuite\TestCase;

class StackedStatesTest extends TestCase
{
     /**
      * Instance of StackedStates.
      *
      * @var StackedStates
      */
     public $states;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->states = new StackedStates();
    }

    public function testPushAndPop()
    {
        // push 1
        $this->states->push('type1', [
            'key1' => 1,
            'key2' => 2,
        ]);
        $this->assertEquals($this->states->type(), 'type1');
        $this->assertTrue($this->states->is('type1'));
        $this->assertEquals($this->states->current(), [
            'key1' => 1,
            'key2' => 2,
        ]);

        // push 2
        $this->states->push('type2', [
            'key1' => 3,
            'key2' => 7,
            'key3' => 19,
        ]);
        $this->assertEquals($this->states->type(), 'type2');
        $this->assertTrue($this->states->is('type2'));
        $this->assertEquals($this->states->current(), [
            'key1' => 3,
            'key2' => 7,
            'key3' => 19,
        ]);

        // push 3
        $this->states->push('type1', [
            'key1' => 42,
            'key2' => 43,
        ]);
        $this->assertEquals($this->states->type(), 'type1');
        $this->assertTrue($this->states->is('type1'));
        $this->assertEquals($this->states->current(), [
            'key1' => 42,
            'key2' => 43,
        ]);

        // pop 1
        [$type, $state] = $this->states->pop();
        $this->assertEquals($type, 'type1');
        $this->assertEquals($state, [
            'key1' => 42,
            'key2' => 43,
        ]);
        $this->assertEquals($this->states->type(), 'type2');
        $this->assertTrue($this->states->is('type2'));
        $this->assertEquals($this->states->current(), [
            'key1' => 3,
            'key2' => 7,
            'key3' => 19,
        ]);

        // push 4
        $this->states->push('type3', [
            'key1' => 27,
            'key2' => 29,
        ]);
        $this->assertEquals($this->states->type(), 'type3');
        $this->assertTrue($this->states->is('type3'));
        $this->assertEquals($this->states->current(), [
            'key1' => 27,
            'key2' => 29,
        ]);

        // pop
        $this->states->pop();
        $this->assertEquals($this->states->type(), 'type2');
        $this->assertTrue($this->states->is('type2'));
        $this->assertEquals($this->states->current(), [
            'key1' => 3,
            'key2' => 7,
            'key3' => 19,
        ]);

        // pop
        $this->states->pop();
        $this->assertEquals($this->states->type(), 'type1');
        $this->assertTrue($this->states->is('type1'));
        $this->assertEquals($this->states->current(), [
            'key1' => 1,
            'key2' => 2,
        ]);

        // pop
        [$type, $state] = $this->states->pop();
        $this->assertEquals($type, 'type1');
        $this->assertEquals($state, [
            'key1' => 1,
            'key2' => 2,
        ]);

        $this->assertTrue($this->states->isEmpty());
    }

    public function testDefaults()
    {
        $states = new StackedStates([
            't1' => [
                'key1' => 2,
                'key2' => 4,
            ],
            't2' => [
                'key1' => 3,
                'key2' => 5,
                'key3' => 18,
            ],
        ]);

        $states->push('t1');
        $this->assertEquals($states->current(), [
            'key1' => 2,
            'key2' => 4,
        ]);
        $states->pop();

        $states->push('t2');
        $this->assertEquals($states->current(), [
            'key1' => 3,
            'key2' => 5,
            'key3' => 18,
        ]);
        $states->pop();

        $states->push('t1', ['key1' => 5]);
        $this->assertEquals($states->current(), [
            'key1' => 5,
            'key2' => 4,
        ]);
        $states->pop();

        $states->push('t1', ['key1' => 5, 'key2' => 13]);
        $this->assertEquals($states->current(), [
            'key1' => 5,
            'key2' => 13,
        ]);
        $states->pop();

        $states->push('t1', ['key1' => 5, 'key2' => 13, 'key3' => 17]);
        $this->assertEquals($states->current(), [
            'key1' => 5,
            'key2' => 13,
            'key3' => 17,
        ]);
        $states->pop();

        $states->push('t2', ['key1' => 5, 'key2' => 13, 'key3' => 17]);
        $this->assertEquals($states->current(), [
            'key1' => 5,
            'key2' => 13,
            'key3' => 17,
        ]);
        $states->pop();
    }

    public function testGetValue()
    {
        $this->states->push('type2', [
            'key1' => 3,
            'key2' => 7,
            'key3' => 19,
        ]);

        $this->assertEquals($this->states->getValue('key1'), 3);
        $this->assertEquals($this->states->getValue('key2'), 7);
        $this->assertEquals($this->states->getValue('key3'), 19);
    }

    public function testSetValue()
    {
        $this->states->push('type2');

        $this->states->setValue('key1', 18);
        $this->assertEquals($this->states->getValue('key1'), 18);

        $this->states->setValue('key2', 7);
        $this->assertEquals($this->states->getValue('key2'), 7);

        $this->states->setValue('key3', 19);
        $this->assertEquals($this->states->getValue('key3'), 19);

        $this->states->setValue('key1', 13);
        $this->assertEquals($this->states->getValue('key1'), 13);
    }
}
