<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapFormHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapFormHelperTest extends TestCase {

    /**
     *
     */
    public $View ;
    public $Form ;

    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->Form = new BootstrapFormHelper ($this->View);
    }

    public function testCreate () {

    }

    public function testInputText () {

    }
    
    public function testInputSelect () {

    }

    public function testInputRadio () {

    }

    public function testInputCheckbox () {

    }

}