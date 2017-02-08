<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapNavbarHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class BootstrapNavbarHelperTest extends TestCase {

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $this->View = new View();
        $this->Navbar = new BootstrapNavbarHelper($this->View);
    }

    /**
     * Tear Down
     *
     * @return void
     */
    public function tearDown() {
        parent::tearDown();
        unset($this->Navbar);
        unset($this->View);
    }

    public function testCreate() {

    }

};
