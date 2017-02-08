<?php
/**
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 * You may obtain a copy of the License at
 *
 *     https://opensource.org/licenses/mit-license.php
 *
 *
 * @copyright Copyright (c) Mikaël Capelle (https://typename.fr)
 * @license https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\Routing\Router;

class BootstrapNavbarHelper extends Helper {

    use BootstrapTrait;

    public $helpers = [
        'Html',
        'Form' => [
            'className' => 'Bootstrap.BootstrapForm'
        ]
    ];

    /**
     * Automatic detection of active link (`class="active"`).
     *
     * @var bool
     */
    public $autoActiveLink = true;

    /**
     * Automatic button link when not in a menu.
     *
     * @var bool
     */
    public $autoButtonLink = true;

    protected $_fixed = false;
    protected $_static = false;
    protected $_responsive = false;
    protected $_inverse = false;
    protected $_fluid = false;

    /**
     * Menu level (0 = out of menu, 1 = main horizontal menu, 2 = dropdown menu).
     *
     * @var int
     */
    protected $_level = 0;

    /**
     * Create a new navbar.
     *
     * ### Options:
     * - `fixed` [Fixed navbar](http://getbootstrap.com/components/#navbar-fixed-top). Possible values are `'top'`, `'bottom'`, `false`. Default is `false`.
     * - `fluid` Fluid navabar. Default is `false`.
     * - `inverse` [Inverted navbar](http://getbootstrap.com/components/#navbar-inverted). Default is `false`.
     * - `responsive` Responsive navbar. Default is `true`.
     * - `static` [Static navbar](http://getbootstrap.com/components/#navbar-static-top). Default is `false`.
     *
     * @param string $brand   Brand name.
     * @param array  $options Array of options. See above.
     *
     * @return A string containing the HTML starting element of the navbar.
     */
    public function create($brand, $options = []) {

        $options += [
            'fixed' => false,
            'responsive' => true,
            'static' => false,
            'inverse' => false,
            'fluid' => false
        ];

        $this->_fixed = $options['fixed'];
        $this->_responsive = $options['responsive'];
        $this->_static = $options['static'];
        $this->_inverse = $options['inverse'];
        $this->_fluid = $options['fluid'];
        unset($options['fixed'], $options['responsive'],
              $options['fluid'], $options['static'],
              $options['inverse']);

        /** Generate options for outer div. **/
        $options = $this->addClass($options, 'navbar');
        if ($this->_inverse) {
            $options = $this->addClass($options , 'navbar-inverse');
        }
        else {
            $options = $this->addClass($options , 'navbar-default');
        }
        if ($this->_fixed !== false) {
            $options = $this->addClass($options, 'navbar-fixed-'.$this->_fixed);
        }
        else if ($this->_static !== false) {
            $options = $this->addClass($options, 'navbar-static-top');
        }

        if ($brand) {
            if (is_string($brand)) {
                $brand = $this->Html->link ($brand, '/', [
                    'class' => 'navbar-brand',
                    'escape' => false
                ]);
            }
            else if (is_array($brand) && array_key_exists('url', $brand)) {
                $brand += [
                    'options' => []
                ];
                $brand['options'] = $this->addClass ($brand['options'], 'navbar-brand');
                $brand = $this->Html->link ($brand['name'], $brand['url'], $brand['options']);
            }
        }

        $toggleButton = '';
        if ($this->_responsive) {
            $toggleButton = $this->Html->tag(
                'button', implode('', [
                    $this->Html->tag('span', __('Toggle navigation'),
                                     ['class' => 'sr-only']),
                    $this->Html->tag('span', '', ['class' => 'icon-bar']),
                    $this->Html->tag('span', '', ['class' => 'icon-bar']),
                    $this->Html->tag('span', '', ['class' => 'icon-bar'])
                ]), [
                    'type' => 'button',
                    'class' => 'navbar-toggle collapsed',
                    'data-toggle' => 'collapse',
                    'data-target' => '.navbar-collapse',
                    'aria-expanded' => 'false'
                ]);
        }

        $rightOpen = '';
        if ($this->_responsive || $brand) {
            $rightOpen = $this->Html->tag('div', $toggleButton.$brand,
                                          ['class' => 'navbar-header']);
        }

        if ($this->_responsive) {
            $rightOpen .= $this->Html->tag('div', null, [
                'class' => 'collapse navbar-collapse'
            ]);
        }


        /** Add and return outer div openning. **/
        return $this->Html->tag('nav', null, $options)
            .$this->Html->tag('div', null, [
                'class' => $this->_fluid ? 'container-fluid' : 'container'
            ]).$rightOpen;
    }

    /**
     * Add a link to the navbar or to a menu.
     *
     * Links outside a menu are realized as buttons. Encapsulate links with
     * `beginMenu()`, `endMenu()` to create a horizontal hover menu in the navbar.
     *
     * @param string       $name        The link text.
     * @param string|array $url         The link URL (sent to `Html::link` method).
     * @param array        $options     Array of options for the `<li>` tag.
     * @param array        $linkOptions Array of options for the `Html::link` method.
     *
     * @return string A HTML `<li>` tag wrapping the link.
     */
    public function link($name, $url = '', array $options = [], array $linkOptions = []) {
        if ($this->_level == 0 && $this->autoButtonLink) {
            $options = $this->addClass($options, 'btn btn-default navbar-btn');
            return $this->Html->link($name, $url, $options);
        }
        if (Router::url() == Router::url($url) && $this->autoActiveLink) {
            $options = $this->addClass($options, 'active');
        }
        return $this->Html->tag('li', $this->Html->link($name, $url, $linkOptions),
                                $options);
    }

    /**
     * Add a button to the navbar.
     *
     * @param string $name    Text of the button.
     * @param array  $options Options sent to the `Form::button` method.
     *
     * @return string A HTML navbar button.
     */
    public function button($name, array $options = []) {
        $options = $this->addClass($options, 'navbar-btn');
        return $this->Form->button($name, $options);
    }

    /**
     * Add a divider to the navbar or to a menu.
     *
     * @param array $options Array of options for the `<li>` tag.
     *
     * @return A HTML divider `<li>` tag.
     */
    public function divider(array $options = []) {
        $options = $this->addClass ($options, 'divider');
        $options['role'] = 'separator';
        return $this->Html->tag('li', '', $options);
    }

    /**
     * Add a header to the navbar or to a menu, should not be used outside a submenu.
     *
     * @param string $name    Title of the header.
     * @param array  $options Array of options for the `<li>` tag.
     *
     * @return A HTML header `<li>` tag.
     */
    public function header($name, array $options = []) {
        $options = $this->addClass($options, 'dropdown-header');
        return $this->Html->tag('li', $name, $options);
    }

    /**
     * Add a text to the navbar.
     *
     * ### Options:
     *
     * - `tag` The HTML tag used to wrap the text. Default is `'p'`.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $text The text message.
     * @param array  $options Array of options. See above.
     *
     * @return string A HTML element wrapping the text for the navbar.
     */
    public function text($text, $options = []) {
        $options += [
            'tag' => 'p'
        ];
        $tag     = $options['tag'];
        unset($options['tag']);
        $options = $this->addClass($options, 'navbar-text');
        $text = preg_replace_callback('/<a([^>]*)?>([^<]*)?<\/a>/i', function($matches) {
            $attrs = preg_replace_callback ('/class="(.*)?"/', function ($m) {
                $cl = $this->addClass (['class' => $m[1]], 'navbar-link');
                return 'class="'.$cl['class'].'"';
            }, $matches[1], -1, $count);
            if ($count == 0) {
                $attrs .= ' class="navbar-link"';
            }
            return '<a'.$attrs.'>'.$matches[2].'</a>';
        }, $text);
        return $this->Html->tag($tag, $text, $options);
    }


    /**
     * Add a serach form to the navbar.
     *
     * ### Options:
     *
     * - `align` Search form alignment. Default is `'left'`.
     * - Other options will be passed to the `Form::searchForm` method.
     *
     * @param mixed $model   Model for BootstrapFormHelper::searchForm method.
     * @param array $options Array of options. See above.
     *
     * @return string An HTML search form for the navbar.
     */
    public function searchForm ($model = null, $options = []) {
        $options += [
            'align' => 'left'
        ];
        $options = $this->addClass($options, ['navbar-form',  'navbar-'.$options['align']]);
        unset ($options['align']);
        return $this->Form->searchForm($model, $options);
    }

    /**
     * Start a new menu.
     *
     * Two types of menus exist:
     * - Horizontal hover menu in the navbar (level 0).
     * - Vertical dropdown menu (level 1).
     * The menu level is determined automatically: A dropdown menu needs to be part of
     * a hover menu. In the hover menu case, pass the options array as the first argument.
     *
     * You can populate the menu with `link()`, `divider()`, and sub menus.
     * Use `'class' => 'navbar-right'` option for flush right.
     *
     * **Note:** The `$linkOptions` and `$listOptions` parameters are not used for menu
     * at level 0 (horizontal menu).
     *
     * ### Link Options:
     *
     * - `aria-expanded` HTML attribute. Default is `'false'`.
     * - `aria-haspopup` HTML attribute. Default is `'true'`.
     * - `caret` HTML caret element. Default is `'<span class="caret"></span>'`.
     * - `data-toggle` HTML attribute. Default is `'dropdown'`.
     * - `escape` CakePHP option. Default is `false`.
     * - `role` HTML attribute. Default is `'button'`.
     *
     * @param string       $name        Name of the menu.
     * @param string|array $url         URL for the menu.
     * @param array        $options     Array of options for the wrapping `<li>` element.
     * @param array        $linkOptions Array of options for the link. See above.
     * element (`Html::link` method).
     * @param array        $listOptions Array of options for the openning `ul` elements.
     *
     * @return string HTML elements to start a menu.
     */
    public function beginMenu($name = null, $url = null, $options = [],
                              $linkOptions = [], $listOptions = []) {
        $res = '';
        if ($this->_level == 0) {
            $options = is_array($name) ? $name : [];
            $options = $this->addClass($options, ['nav', 'navbar-nav']);
            $res = $this->Html->tag('ul', null, $options);
        }
        else {
            $linkOptions += [
                'data-toggle' => 'dropdown',
                'role' => 'button',
                'aria-haspopup' => 'true',
                'aria-expanded' => 'false',
                'caret' => '<span class="caret"></span>',
                'escape' => false
            ];
            $caret = $linkOptions['caret'];
            unset($linkOptions['caret']);
            $link  = $this->Html->link($name.$caret, $url ? $url : '#', $linkOptions);
            $options     = $this->addClass($options, 'dropdown');
            $listOptions = $this->addClass($listOptions, 'dropdown-menu');
            $res = $this->Html->tag('li', null, $options)
                 .$link.$this->Html->tag('ul', null, $listOptions);
        }
        $this->_level += 1;
        return $res;
    }

    /**
     * End a menu.
     *
     * @return string HTML elements to close a menu.
     */
    public function endMenu () {
        $this->_level -= 1;
        return '</ul>'.($this->_level == 1 ? '</li>' : '');
    }

    /**
     * Close a navbar.
     *
     * @return string HTML elements to close the navbar.
     */
    public function end () {
        $res = '</div></div>';
        if ($this->_responsive) {
            $res .= '</div>';
        }
        return $res;
    }

}

?>
