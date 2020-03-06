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

use Bootstrap\TestApp\PublicEasyIconTrait;
use Bootstrap\View\Helper\FormHelper;
use Bootstrap\View\Helper\PaginatorHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class EasyIconTraitTest extends TestCase
{
    /**
     * Instance of PublicEasyIconTrait.
     *
     * @var PublicEasyIconTrait
     */
    public $trait;

    /**
     * Instance of HtmlHelper.
     *
     * @var HtmlHelper
     */
    public $html;

    /**
     * Instance of FormHelper.
     *
     * @var FormHelper
     */
    public $form;

    /**
     * Instance of PaginatorHelper.
     *
     * @var PaginatorHelper
     */
    public $paginator;

    /**
     * Setup
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $view->loadHelper('Html', [
            'className' => 'Bootstrap.Html',
        ]);
        $this->html = $view->Html;
        $this->trait = new PublicEasyIconTrait($view);
        $this->form = new FormHelper($view);
        $this->paginator = new PaginatorHelper($view);
    }

    public function testEasyIcon()
    {
        $converted = false;

        $this->assertHtml(
            [['i' => [
                'class' => 'fa fa-plus',
                'aria-hidden' => 'true',
            ]],
            '/i'],
            $this->trait->publicMakeIcon('i:plus', $converted)
        );
        $this->assertTrue($converted);

        $this->assertHtml(['Click Me!'], $this->trait->publicMakeIcon('Click Me!', $converted));
        $this->assertFalse($converted);

        $this->assertHtml([['i' => [
                'class' => 'fa fa-plus',
                'aria-hidden' => 'true',
            ]], '/i', ' Add'], $this->trait->publicMakeIcon('i:plus Add', $converted));
        $this->assertTrue($converted);

        $this->assertHtml(['Add ', ['i' => [
            'class' => 'fa fa-plus',
            'aria-hidden' => 'true',
        ]], '/i'], $this->trait->publicMakeIcon('Add i:plus', $converted));
        $this->assertTrue($converted);

        $this->trait->easyIcon = false;
        $this->assertHtml(['Add i:plus'], $this->trait->publicMakeIcon('Add i:plus', $converted));
        $this->assertFalse($converted);
    }

    public function testHtmlHelperMethods()
    {
        // BootstrapHtmlHelper
        $result = $this->html->link('i:dashboard Dashboard', '/dashboard');
        $this->assertHtml([
            ['a' => [
                'href' => '/dashboard',
            ]],
            ['i' => [
                'class' => 'fa fa-dashboard',
                'aria-hidden' => 'true',
            ]], '/i', 'Dashboard', '/a',
        ], $result);
    }

    public function testPaginatorHelperMethods()
    {
        // BootstrapPaginatorHelper - TODO
        // BootstrapPaginatorHelper::prev($title, array $options = []);
        // BootstrapPaginatorHelper::next($title, array $options = []);
        // BootstrapPaginatorHelper::numbers(array $options = []); // For `prev` and `next` options.
        $this->markTestIncomplete();
    }

    public function testFormHelperMethod()
    {
        // BootstrapFormHelper
        $result = $this->form->button('i:plus');
        $this->assertHtml([
            ['button' => [
                'class' => 'btn btn-primary',
                'type' => 'submit',
            ]], ['i' => [
                'class' => 'fa fa-plus',
                'aria-hidden' => 'true',
            ]], '/i', '/button',
        ], $result);
        $result = $this->form->control('fieldname', [
            'prepend' => 'i:home',
            'append' => 'i:plus',
            'label' => false,
        ]);
        $this->assertHtml([
            ['div' => [
                'class' => 'form-group text',
            ]],
            ['div' => [
                'class' => 'input-group',
            ]],
            ['div' => [
                'class' => 'input-group-prepend',
            ]],
            ['span' => [
                'class' => 'input-group-text',
            ]],
            ['i' => [
                'class' => 'fa fa-home',
                'aria-hidden' => 'true',
            ]], '/i',
            '/span',
            '/div',
            ['input' => [
                'type' => 'text',
                'class' => 'form-control',
                'name' => 'fieldname',
                'id' => 'fieldname',
            ]],
            ['div' => [
                'class' => 'input-group-append',
            ]],
            ['span' => [
                'class' => 'input-group-text',
            ]],
            ['i' => [
                'class' => 'fa fa-plus',
                'aria-hidden' => 'true',
            ]], '/i',
            '/span',
            '/div',
            '/div',
            '/div',
        ], $result);
        //BootstrapFormHelper::prepend($input, $prepend); // For $prepend.
        //BootstrapFormHelper::append($input, $append); // For $append.
    }
}
