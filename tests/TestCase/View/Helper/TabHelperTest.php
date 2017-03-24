<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\TabHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class TabHelperTest extends TestCase {

    /**
     * Instance of TabHelper.
     *
     * @var TabHelper
     */
    public $tab;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html'
        ]);
        $this->tab = new TabHelper($view);
    }

    public function testCreate() {
    }

}
