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

use Bootstrap\TestApp\PublicClassTrait;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class ClassTraitTest extends TestCase
{
    /**
     * Instance of PublicClassTrait.
     *
     * @var PublicClassTrait
     */
    public $trait;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->trait = new PublicClassTrait($view);
    }

    public function testAddClass()
    {
        // Test with a string
        $opts = [
            'class' => 'class-1',
        ];
        $opts = $this->trait->addClass($opts, '  class-1    class-2  ');
        $this->assertEquals($opts, [
            'class' => 'class-1 class-2',
        ]);
        // Test with an array
        $opts = $this->trait->addClass($opts, ['class-1', 'class-3']);
        $this->assertEquals($opts, [
            'class' => 'class-1 class-2 class-3',
        ]);
    }
}
