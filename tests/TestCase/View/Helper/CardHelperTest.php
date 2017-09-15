<?php

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\CardHelper;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Cake\View\View;

class CardHelperTest extends TestCase {

    /**
     * Instance of CardHelper.
     *
     * @var CardHelper
     */
    public $card;

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
        $this->card = new CardHelper($view);
        Configure::write('debug', true);
    }

    protected function reset() {
        $this->card->end();
    }

    public function testCreate() {
        $title = "My Modal";
        $id = "myModalId";
        // Test standard create with title
        $result = $this->card->create($title);
        $this->assertHtml([
            ['div' => [
                'class' => 'card card-default'
            ]],
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            $title,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'card-body'
            ]]
        ], $result);
        $this->reset();
        // Test standard create with title
        $result = $this->card->create($title, ['body' => false]);
        $this->assertHtml([
            ['div' => [
                'class' => 'card card-default'
            ]],
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            $title,
            '/h4',
            '/div'
        ], $result);
        $this->reset();
        // Test standard create without title
        $result = $this->card->create();
        $this->assertHtml([
            ['div' => [
                'class' => 'card card-default'
            ]]
        ], $result);
        $this->reset();
    }

    public function testHeader() {
        $content = 'Header';
        $htmlContent = '<b>'.$content.'</b>';
        $extraclass = 'my-extra-class';

        // Simple test
        $this->card->create();
        $result = $this->card->header($content);
        $this->assertHtml([
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            $content,
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should be escaped)
        $this->card->create();
        $result = $this->card->header($htmlContent);
        $this->assertHtml([
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            htmlspecialchars($htmlContent),
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should NOT be escaped)
        $this->card->create();
        $result = $this->card->header($htmlContent, ['escape' => false]);
        $this->assertHtml([
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            ['b' => true], $content, '/b',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with icon
        $iconContent = 'i:home Home';
        $this->card->create();
        $result = $this->card->header($iconContent);
        $this->assertHtml([
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            ['i' => [
                'class' => 'fa fa-home',
                'aria-hidden' => 'true'
            ]], '/i', ' Home',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with collapsible (should NOT be escaped)

        // Test with HTML content (should be escaped)
        $tmp = $this->card->create(null, ['collapsible' => true]);
        $result = $this->card->header($htmlContent);
        $this->assertHtml([
            ['div' => [
                'class' => 'card-heading',
                'role'  => 'tab',
                'id'    => 'heading-4'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            ['a' => [
                'role'          => 'button',
                'data-toggle'   => 'collapse',
                'href'          => '#collapse-4',
                'aria-expanded' => 'true',
                'aria-controls' => 'collapse-4'
            ]],
            htmlspecialchars($htmlContent),
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with HTML content (should NOT be escaped)
        $this->card->create(null, ['collapsible' => true]);
        $result = $this->card->header($htmlContent, ['escape' => false]);
        $this->assertHtml([
            ['div' => [
                'role'  => 'tab',
                'id'    => 'heading-5',
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            ['a' => [
                'role'          => 'button',
                'data-toggle'   => 'collapse',
                'href'          => '#collapse-5',
                'aria-expanded' => 'true',
                'aria-controls' => 'collapse-5'
            ]],
            ['b' => true], $content, '/b',
            '/a',
            '/h4',
            '/div'
        ], $result);
        $this->reset();

        // Test with icon
        $iconContent = 'i:home Home';
        $this->card->create(null, ['collapsible' => true]);
        $result = $this->card->header($iconContent);
        $this->assertHtml([
            ['div' => [
                'role'  => 'tab',
                'id'    => 'heading-6',
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            ['a' => [
                'role'          => 'button',
                'data-toggle'   => 'collapse',
                'href'          => '#collapse-6',
                'aria-expanded' => 'true',
                'aria-controls' => 'collapse-6'
            ]],
            ['i' => [
                'class' => 'fa fa-home',
                'aria-hidden' => 'true'
            ]], '/i', ' Home',
            '/a',
            '/h4',
            '/div'
        ], $result, true);
        $this->reset();
    }

    public function testFooter() {
        $content = 'Footer';
        $extraclass = 'my-extra-class';

        // Simple test
        $this->card->create();
        $result = $this->card->footer($content, ['class' => $extraclass]);
        $this->assertHtml([
            ['div' => [
                'class' => 'card-footer '.$extraclass
            ]],
            $content,
            '/div'
        ], $result);
        $this->reset();

    }

    public function testGroup() {

        $cardHeading = 'This is a card heading';
        $cardContent = 'A bit of HTML code inside!';

        $result = '';
        $result .= $this->card->startGroup();
        $result .= $this->card->create($cardHeading);
        $result .= $cardContent;
        $result .= $this->card->create($cardHeading);
        $result .= $cardContent;
        $result .= $this->card->create($cardHeading);
        $result .= $cardContent;
        $result .= $this->card->endGroup();
        $result .= $this->card->create($cardHeading);
        $result .= $cardContent;
        $result .= $this->card->end();

        $expected = [
            ['div' => [
                'class'                => 'card-group',
                'role'                 => 'tablist',
                'aria-multiselectable' => 'true',
                'id'                   => 'cardGroup-1'
            ]]
        ];

        for ($i = 0; $i < 3; ++$i) {
            $expected = array_merge($expected, [
                ['div' => [
                    'class' => 'card card-default'
                ]],
                ['div' => [
                    'class' => 'card-heading',
                    'role'  => 'tab',
                    'id'    => 'heading-'.$i
                ]],
                ['h4' => [
                    'class' => 'card-title'
                ]],
                ['a' => [
                    'role'          => 'button',
                    'data-toggle'   => 'collapse',
                    'href'          => '#collapse-'.$i,
                    'aria-expanded' => $i ? 'false' : 'true',
                    'aria-controls' => 'collapse-'.$i,
                    'data-parent'   => '#cardGroup-1'
                ]],
                $cardHeading,
                '/a',
                '/h4',
                '/div',
                ['div' => [
                    'class'           => 'card-collapse collapse'.($i ? '' : ' in'),
                    'role'            => 'tabcard',
                    'aria-labelledby' => 'heading-'.$i,
                    'id'              => 'collapse-'.$i
                ]],
                ['div' => [
                    'class' => 'card-body'
                ]],
                $cardContent,
                '/div',
                '/div',
                '/div'
            ]);
        }

        $expected = array_merge($expected, ['/div']);

        $expected = array_merge($expected, [
            ['div' => [
                'class' => 'card card-default'
            ]],
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            $cardHeading,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'card-body'
            ]],
            $cardContent,
            '/div',
            '/div'
        ]);

        $this->assertHtml($expected, $result, false);
    }

    public function testCardGroupInsideCard() {

        $cardHeading = 'This is a card heading';
        $cardContent = 'A bit of HTML code inside!';

        $result = '';
        $result .= $this->card->create($cardHeading);
        $result .= $this->card->startGroup();
        $result .= $this->card->create($cardHeading);
        $result .= $cardContent;
        $result .= $this->card->create($cardHeading);
        $result .= $cardContent;
        $result .= $this->card->endGroup();
        $result .= $this->card->end();

        $expected = [
            ['div' => [
                'class' => 'card card-default'
            ]],
            ['div' => [
                'class' => 'card-heading'
            ]],
            ['h4' => [
                'class' => 'card-title'
            ]],
            $cardHeading,
            '/h4',
            '/div',
            ['div' => [
                'class' => 'card-body'
            ]],
            ['div' => [
                'class'                => 'card-group',
                'role'                 => 'tablist',
                'aria-multiselectable' => 'true',
                'id'                   => 'cardGroup-1'
            ]]
        ];

        for ($i = 1; $i < 3; ++$i) {
            $expected = array_merge($expected, [
                ['div' => [
                    'class' => 'card card-default'
                ]],
                ['div' => [
                    'class' => 'card-heading',
                    'role'  => 'tab',
                    'id'    => 'heading-'.$i
                ]],
                ['h4' => [
                    'class' => 'card-title'
                ]],
                ['a' => [
                    'role'          => 'button',
                    'data-toggle'   => 'collapse',
                    'href'          => '#collapse-'.$i,
                    'aria-expanded' => ($i > 1) ? 'false' : 'true',
                    'aria-controls' => 'collapse-'.$i,
                    'data-parent'   => '#cardGroup-1'
                ]],
                $cardHeading,
                '/a',
                '/h4',
                '/div',
                ['div' => [
                    'class'           => 'card-collapse collapse'.($i > 1 ? '' : ' in'),
                    'role'            => 'tabcard',
                    'aria-labelledby' => 'heading-'.$i,
                    'id'              => 'collapse-'.$i
                ]],
                ['div' => [
                    'class' => 'card-body'
                ]],
                $cardContent,
                '/div',
                '/div',
                '/div'
            ]);
        }

        $expected = array_merge($expected, ['/div', '/div']);

        $this->assertHtml($expected, $result, false);

    }

}
