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
namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Navbar helper library.
 *
 * Automatic generation of Bootstrap HTML navbars.
 *
 * @property \Bootstrap\View\Helper\FormHelper $Form
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class NavbarHelper extends Helper
{
    use ClassTrait;
    use EasyIconTrait;
    use StringTemplateTrait;
    use UrlComparerTrait;

    /**
     * Other helpers used by NavbarHelper.
     *
     * @var array
     */
    public $helpers = [
        'Html', 'Url',
    ];

    /**
     * Default configuration for the helper.
     *
     * - `autoActiveLink` Set to `true` to automatically add `active` class
     * when given URL for a link matches the current URL. Default is `true`.
     * - `autoButtonLink` Set to  true` to automatically create buttons instead
     * of links when outside a menu. Default is `true`.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'navbarStart' => '<nav class="navbar{{attrs.class}}"{{attrs}}>{{containerStart}}{{header}}{{responsiveStart}}',
            'navbarEnd' => '{{responsiveEnd}}{{containerEnd}}</nav>',
            'containerStart' => '<div class="container{{attrs.class}}"{{attrs}}>',
            'containerEnd' => '</div>',
            'responsiveStart' => '<div class="collapse navbar-collapse{{attrs.class}}" id="{{id}}"{{attrs}}>',
            'responsiveEnd' => '</div>',
            'header' => '{{brand}}{{toggleButton}}',
            'toggleButton' =>
    '<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#{{id}}" aria-controls="{{id}}" aria-label="{{label}}" aria-expanded="false">
    <span class="navbar-toggler-icon"></span>
</button>',
            'brand' => '<a class="navbar-brand{{attrs.class}}" href="{{url}}"{{attrs}}>{{content}}</a>',
            'brandImage' => '<img alt="{{brandname}}" src="{{src}}"{{attrs}} />',
            'dropdownMenuStart' => '<div class="dropdown-menu{{attrs.class}}"{{attrs}}>',
            'dropdownMenuEnd' => '</div>',
            'dropdownLink' =>
    '<a href="{{url}}" class="nav-link dropdown-toggle{{attrs.class}}" data-toggle="dropdown" role="button"
aria-haspopup="true" aria-expanded="false">{{content}}</a>',
            'innerMenuStart' => '<li class="nav-item dropdown{{attrs.class}}"{{attrs}}>{{dropdownLink}}{{dropdownMenuStart}}',
            'innerMenuEnd' => '{{dropdownMenuEnd}}</li>',
            'innerMenuItem' => '{{link}}',
            'innerMenuItemLink' => '<a href="{{url}}" class="dropdown-item{{attrs.class}}"{{attrs}}>{{content}}</a>',
            'innerMenuItemActive' => '{{link}}',
            'innerMenuItemLinkActive' => '<a href="{{url}}" class="dropdown-item active{{attrs.class}}"{{attrs}}>{{content}}</a>',
            'innerMenuItemDivider' => '<div role="separator" class="dropdown-divider{{attrs.class}}"{{attrs}}></div>',
            'innerMenuItemHeader' => '<h6 class="dropdown-header{{attrs.class}}"{{attrs}}>{{content}}</h6>',
            'outerMenuStart' => '<ul class="navbar-nav mr-auto{{attrs.class}}"{{attrs}}>',
            'outerMenuEnd' => '</ul>',
            'outerMenuItem' => '<li class="nav-item{{attrs.class}}"{{attrs}}>{{link}}</li>',
            'outerMenuItemLink' => '<a href="{{url}}" class="nav-link{{attrs.class}}"{{attrs}}>{{content}}</a>',
            'outerMenuItemActive' => '<li class="nav-item active{{attrs.class}}"{{attrs}}>{{link}}</li>',
            'outerMenuItemLinkActive' => '<a href="{{url}}" class="nav-link{{attrs.class}}"{{attrs}}>{{content}}</a>',
            'navbarText' => '<span class="navbar-text{{attrs.class}}"{{attrs}}>{{content}}</span>',
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate',
        'autoActiveLink' => true,
    ];

    /**
     * Indicates if the navbar is responsive or not.
     *
     * @var bool
     */
    protected $_responsive = false;

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
     * - `container` Wrap the inner content inside a container. Default is `false`.
     * - `fixed` [Fixed navbar](https://getbootstrap.com/docs/4.0/components/navbar/#placement). Possible values are `'top'`, `'bottom'`, `false`. Default is `false`.
     * - `theme` [Navbar theme](https://getbootstrap.com/docs/4.0/components/navbar/#color-schemes). Either a `'light'`, `'dark'` or an array with]
     * 2 values corresponding to the theme and the background (e.g. `['dark', 'primary']`). Can also be set to `false` to disable automatic theming. Default is `'light'`.
     * - `collapse` Specify when the navbar should collapse. Possible values are `false` (never), `true` (always) or a string (`'lg'`, `'xl'`, ...).
     * Default is `'lg'`.
     * - `sticky` [Sticky navbar](https://getbootstrap.com/docs/4.0/components/navbar/#placement). Default is `false`.
     * - `templateVars` Provide template variables for the template.
     * - Other attributes will be assigned to the navbar element.
     *
     * @param string|null $brand Brand name.
     * @param array $options Array of options. See above.
     * @return string containing the HTML starting element of the navbar.
     */
    public function create(?string $brand = null, array $options = []): string
    {
        $options += [
            'id' => 'navbar',
            'fixed' => false,
            'collapse' => 'lg',
            'sticky' => false,
            'theme' => 'light',
            'container' => false,
            'templateVars' => [],
        ];

        $this->_responsive = $options['collapse'] !== false;
        $this->_container = $options['container'];

        /** Generate options for outer div. **/
        if ($options['theme'] !== false) {
            $scheme = $options['theme'];
            $bg = null;
            if (is_array($scheme)) {
                [$scheme, $bg] = $scheme;
            }
            if ($bg === null) {
                $bg = $scheme;
            }
            $options = $this->addClass($options, 'navbar-' . $scheme);
            if ($bg !== false) {
                $options = $this->addClass($options, 'bg-' . $bg);
            }
        }

        if ($options['fixed'] !== false) {
            $fixed = $options['fixed'];
            if ($fixed === true) {
                $fixed = 'top';
            }
            $options = $this->addClass($options, 'fixed-' . $fixed);
        }
        if ($options['sticky'] !== false) {
            $options = $this->addClass($options, 'sticky-top');
        }

        if ($brand) {
            if (is_string($brand)) {
                $brand = [
                    'name' => $brand,
                    'url' => '/',
                ];
            }
            $brand = $this->formatTemplate('brand', [
                'content' => $brand['name'],
                'url' => $this->Url->build($brand['url']),
                'attrs' => $this->templater()->formatAttributes($brand, ['name', 'url']),
                'templateVars' => $options['templateVars'],
            ]);
        }

        $toggleButton = '';
        if ($this->_responsive) {
            $toggleButton = $this->formatTemplate('toggleButton', [
                'label' => __('Toggle navigation'),
                'id' => $options['id'],
            ]);
            if ($options['collapse'] !== true) {
                $options = $this->addClass($options, 'navbar-expand-' . $options['collapse']);
            }
        } else {
            $options = $this->addClass($options, 'navbar-expand');
        }

        $containerStart = '';
        if ($this->_container) {
            $containerStart = $this->formatTemplate('containerStart', [
                'attrs' => $this->templater()->formatAttributes([]),
                'templateVars' => $options['templateVars'],
            ]);
        }

        $responsiveStart = '';
        if ($this->_responsive) {
            $responsiveStart .= $this->formatTemplate('responsiveStart', [
                'id' => $options['id'],
                'attrs' => $this->templater()->formatAttributes([]),
                'templateVars' => $options['templateVars'],
            ]);
        }

        $header = '';
        if ($this->_responsive || $brand) {
            $header = $this->formatTemplate('header', [
                'toggleButton' => $toggleButton,
                'brand' => $brand,
            ]);
        }

        return $this->formatTemplate('navbarStart', [
            'header' => $header,
            'responsiveStart' => $responsiveStart,
            'containerStart' => $containerStart,
            'attrs' => $this->templater()->formatAttributes($options, ['id', 'fixed', 'collapse', 'sticky', 'theme', 'container']),
            'templateVars' => $options['templateVars'],
        ]);
    }

    /**
     * Add a link to the navbar or to a menu.
     *
     *  Encapsulate links with `beginMenu()`, `endMenu()` to create
     * a horizontal hover menu in the navbar or a dropdown menu.
     *
     * ### Options
     *
     * - `active` Indicates if the link is the current one. Default is automatically
     * deduced if `autoActiveLink` is on, otherwize default is `false`.
     * - `templateVars` Provide template variables for the templates.
     * - Other attributes will be assigned to the navbar link element.
     *
     * @param string $name The link text.
     * @param string|array $url The link URL (CakePHP way).
     * @param array $options Array of attributes for the wrapper tag (outer menu) or
     * the link (inner menu).
     * @param array $linkOptions Array of attributes for the link in outer menu. Not used
     * if in inner menu.
     * @return string A HTML tag wrapping the link.
     */
    public function link(string $name, $url = '', array $options = [], array $linkOptions = []): string
    {
        $url = $this->Url->build($url);
        $options += [
            'active' => [],
            'templateVars' => [],
        ];
        $linkOptions += [
            'templateVars' => [],
        ];
        if (is_string($options['active'])) {
            $options['active'] = [];
        }
        if ($this->getConfig('autoActiveLink') && is_array($options['active'])) {
            $options['active'] = $this->compareUrls($url, null, $options['active']);
        }
        $active = $options['active'] ? 'Active' : '';
        $level = $this->_level > 1 ? 'inner' : 'outer';
        $template = $level . 'MenuItem' . $active;
        $linkTemplate = $level . 'MenuItemLink' . $active;

        // inner menu, no wrapper elements, options go directly for link
        if ($level === 'inner') {
            $linkOptions = $options;
        }

        $link = $this->formatTemplate($linkTemplate, [
            'content' => $name,
            'url' => $url,
            'attrs' => $this->templater()->formatAttributes($linkOptions, ['active']),
            'templateVars' => $linkOptions['templateVars'],
        ]);

        return $this->formatTemplate($template, [
            'link' => $link,
            'attrs' => $this->templater()->formatAttributes($options, ['active']),
            'templateVars' => $options['templateVars'],
        ]);
    }

    /**
     * Add a divider to an inner menu of the navbar.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the divider template.
     * - Other attributes will be assigned to the divider element.
     *
     * @param array $options Array of options. See above.
     * @return string A HTML dropdown divider tag.
     */
    public function divider(array $options = []): string
    {
        $options += ['templateVars' => []];

        return $this->formatTemplate('innerMenuItemDivider', [
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars'],
        ]);
    }

    /**
     * Add a header to an inner menu of the navbar.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the header template.
     * - Other attributes will be assigned to the header element.
     * *
     * @param string $name Title of the header.
     * @param array $options Array of options for the wrapper tag.
     * @return string A HTML header tag.
     */
    public function header(string $name, array $options = []): string
    {
        $options += ['templateVars' => []];

        return $this->formatTemplate('innerMenuItemHeader', [
            'content' => $name,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars'],
        ]);
    }

    /**
     * Add a text to the navbar.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the text template.
     * - Other attributes will be assigned to the text element.
     *
     * @param string $text The text message.
     * @param array $options Array attributes for the wrapper element.
     * @return string A HTML element wrapping the text for the navbar.
     */
    public function text(string $text, array $options = []): string
    {
        $options += [
            'templateVars' => [],
        ];

        return $this->formatTemplate('navbarText', [
            'content' => $text,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars'],
        ]);
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
     * ### Options
     *
     * - `templateVars` Provide template variables for the menu template.
     * - Other attributes will be assigned to the menu element.
     *
     * ### Link Options
     *
     * - Other attributes will be assigned to the link element.
     *
     * ### List Options
     *
     * - Other attributes will be assigned to the list element.
     *
     * @param string|array|null $name Name of the menu.
     * @param string|array $url URL for the menu.
     * @param array $options Array of options for the wrapping element.
     * @param array $linkOptions Array of options for the link. See above.
     * @param array $listOptions Array of options for the openning `ul` elements.
     * @return string HTML elements to start a menu.
     */
    public function beginMenu(
        $name = null,
        $url = null,
        array $options = [],
        array $linkOptions = [],
        array $listOptions = []
    ): string {
        $template = 'outerMenuStart';
        $templateOptions = [];
        if (is_array($name)) {
            $options = $name;
        }
        $options += [
            'templateVars' => [],
        ];
        if ($this->_level == 1) {
            $template = 'innerMenuStart';
            $templateOptions['dropdownLink'] = $this->formatTemplate('dropdownLink', [
                'content' => $name,
                'url' => $url ? $this->Url->build($url) : '#',
                'attrs' => $this->templater()->formatAttributes($linkOptions),
            ]);
            $templateOptions['dropdownMenuStart'] = $this->formatTemplate('dropdownMenuStart', [
                'attrs' => $this->templater()->formatAttributes($listOptions),
            ]);
        }
        $this->_level += 1;

        return $this->formatTemplate($template, $templateOptions + [
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars'],
        ]);
    }

    /**
     * End a menu.
     *
     * @return string HTML elements to close a menu.
     */
    public function endMenu(): string
    {
        $template = 'outerMenuEnd';
        $options = [];
        if ($this->_level == 2) {
            $template = 'innerMenuEnd';
            $options['dropdownMenuEnd'] = $this->formatTemplate('dropdownMenuEnd', []);
        }
        $this->_level -= 1;

        return $this->formatTemplate($template, $options);
    }

    /**
     * Close a navbar.
     *
     * @return string HTML elements to close the navbar.
     */
    public function end(): string
    {
        $containerEnd = '';
        if ($this->_container) {
            $containerEnd = $this->formatTemplate('containerEnd', []);
        }
        $responsiveEnd = '';
        if ($this->_responsive) {
            $responsiveEnd = $this->formatTemplate('responsiveEnd', []);
        }

        return $this->formatTemplate('navbarEnd', [
            'containerEnd' => $containerEnd,
            'responsiveEnd' => $responsiveEnd,
        ]);
    }
}
