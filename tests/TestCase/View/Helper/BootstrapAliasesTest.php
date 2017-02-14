<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;

class BootstrapAliasesTest extends TestCase {

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {

    }

    public function testAliasExists() {
        $helpers = ['Breadcrumbs', 'Flash', 'Form', 'Html', 'Modal',
            'Navbar', 'Paginator', 'Panel'];
        $view = new \Cake\View\View();
        foreach ($helpers as $name) {
            $class = 'Bootstrap\\View\\Helper\\'.$name.'Helper';
            $alias = 'Bootstrap\\View\\Helper\\Bootstrap'.$name.'Helper';
            $this->assertTrue(class_exists($class), "Class $class does not exists.");
            $this->assertTrue(class_exists($alias), "Alias class $alias does not exists.");
            $this->assertTrue(is_subclass_of(new $alias($view), $class), "Class $alias is not an alias of $class.");
        }
    }    

};