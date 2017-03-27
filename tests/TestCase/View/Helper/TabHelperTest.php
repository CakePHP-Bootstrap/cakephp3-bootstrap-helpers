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
        $result = $this->tab->create(['Home', 'Profile', 'Messages', 'Settings']);

        $result .= $this->tab->end();
        $expected = [
            ["div" => []],
            "Nav tabs",
            ["ul" => [
                "class" => "nav nav-tabs",
                "role" => "tablist"
            ]],
            ["li" => [
                "role" => "presentation",
                "class" => "active"
            ]],
            ["a" => [
                "href" => "#home",
                "aria-controls" => "home",
                "role" => "tab",
                "data-toggle" => "tab"
            ]],
            "Home",
            "/a",
            "/li",
            ["li" => [
                "role" => "presentation"
            ]],
            ["a" => [
                "href" => "#profile",
                "aria-controls" => "profile",
                "role" => "tab",
                "data-toggle" => "tab"
            ]],
            "Profile",
            "/a",
            "/li",
            ["li" => [
                "role" => "presentation"
            ]],
            ["a" => [
                "href" => "#messages",
                "aria-controls" => "messages",
                "role" => "tab",
                "data-toggle" => "tab"
            ]],
            "Messages",
            "/a",
            "/li",
            ["li" => [
                "role" => "presentation"
            ]],
            ["a" => [
                "href" => "#settings",
                "aria-controls" => "settings",
                "role" => "tab",
                "data-toggle" => "tab"
            ]],
            "Settings",
            "/a",
            "/li",
            "/ul",
            "Tab panes",
            ["div" => [
                "class" => "tab-content"
            ]],
            ["div" => [
                "role" => "tabpanel",
                "class" => "tab-pane active",
                "id" => "home"
            ]],
            "...",
            "/div",
            ["div" => [
                "role" => "tabpanel",
                "class" => "tab-pane",
                "id" => "profile"
            ]],
            "...",
            "/div",
            ["div" => [
                "role" => "tabpanel",
                "class" => "tab-pane",
                "id" => "messages"
            ]],
            "...",
            "/div",
            ["div" => [
                "role" => "tabpanel",
                "class" => "tab-pane",
                "id" => "settings"
            ]],
            "...",
            "/div",
            "/div",
            "/div"
        ];
        // $this->assertHtml($expected, $result, true);

    }

}
