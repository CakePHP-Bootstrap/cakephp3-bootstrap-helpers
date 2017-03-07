<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\NavbarHelper;
use Cake\Core\Configure;
use Cake\Network\Request;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class DumbTest extends TestCase {

    /**
     * Instance of the NavbarHelper.
     *
     * @var NavbarHelper
     */
    public $navbar;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp() {
        Configure::write('debug', true);
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html'
        ]);
        $view->loadHelper('Form', [
            'className' => 'Bootstrap.Form'
        ]);
        $this->navbar = new NavbarHelper($view);
    }

    public function test() {
        $this->Form = $this->navbar->Form;
        echo $this->Form->create(null, ['class' => 'navbar-form navbar-left']);
        echo $this->Form->input('data', [
            'type' => 'text',
            'label' => false,
            'placeholder' => 'Recherches',
            'class' => 'input-sm',
            'append' => $this->Form->button('i:search')
        ]);
        echo $this->Form->end();
    }

};
