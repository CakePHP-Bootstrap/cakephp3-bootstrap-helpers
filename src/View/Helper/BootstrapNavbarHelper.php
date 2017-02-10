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

/**
 * Navbar helper library.
 *
 * Automatic generation of Bootstrap HTML navbars.
 *
 * @property \Bootstrap\View\Helper\BootstrapFormHelper $Form
 * @property \Bootstrap\View\Helper\BootstrapHtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class BootstrapNavbarHelper extends Helper {

    use \Cake\View\StringTemplateTrait;
    use EasyIconTrait;
    use BootstrapTrait;
    use UrlComparerTrait;

    /**
     * Other helpers used by BootstrapNavbarHelper.
     *
     * @var array
     */
    public $helpers = [
        'Form' => [
            'className' => 'Bootstrap.BootstrapForm'
        ],
        'Html' => [
            'className' => 'Bootstrap.BootstrapHtml'
        ],
        'Url'
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
    public $_defaultConfig = [
        'templates' => [
            'navbarStart' => '<nav class="navbar navbar-{{type}}{{attrs.class}}"{{attrs}}>{{containerStart}}{{header}}{{responsiveStart}}',
            'navbarEnd' => '{{responsiveEnd}}{{containerEnd}}</nav>',
            'containerStart' => '<div class="{{containerClass}}{{attrs.class}}"{{attrs}}>',
            'containerEnd' => '</div>',
            'responsiveStart' => '<div class="collapse navbar-collapse{{attrs.class}}" id="{{id}}"{{attrs}}>',
            'responsiveEnd' => '</div>',
            'header' => '<div class="navbar-header{{attrs.class}}"{{attrs}}>{{toggleButton}}{{brand}}</div>',
            'toggleButton' => 
'<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#{{id}}" aria-expanded="false">
    <span class="sr-only">{{content}}</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
</button>',
            'brand' => '<a class="navbar-brand{{attrs.class}}" href="{{url}}"{{attrs}}>{{content}}</a>',
            'brandImage' => '<img alt="{{brandname}}" src="{{src}}"{{attrs}} />',
            'dropdownMenuStart' => '<ul class="dropdown-menu{{attrs.class}}"{{attrs}}>',
            'dropdownMenuEnd' => '</ul>',
            'dropdownLink' => 
'<a href="{{url}}" class="dropdown-toggle{{attrs.class}}" data-toggle="dropdown" role="button" 
aria-haspopup="true" aria-expanded="false">{{content}}{{caret}}</a>',
            'innerMenuStart' => '<li class="dropdown{{attrs.class}}"{{attrs}}>{{dropdownLink}}{{dropdownMenuStart}}',
            'innerMenuEnd' => '{{dropdownMenuEnd}}</li>',
            'innerMenuItem' => '<li{{attrs}}>{{link}}</li>',            
            'innerMenuItemLink' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'innerMenuItemActive' => '<li class="active{{attrs.class}}"{{attrs}}>{{link}}</li>',
            'innerMenuItemLinkActive' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'innerMenuItemDivider' => '<li role="separator" class="divider{{attrs.class}}"{{attrs}}></li>',
            'innerMenuItemHeader' => '<li class="dropdown-header{{attrs.class}}"{{attrs}}>{{content}}</li>',
            'outerMenuStart' => '<ul class="nav navbar-nav{{attrs.class}}"{{attrs}}>',
            'outerMenuEnd' => '</ul>',
            'outerMenuItem' => '<li{{attrs}}>{{link}}</li>',
            'outerMenuItemLink' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'outerMenuItemActive' => '<li class="active{{attrs.class}}"{{attrs}}>{{link}}</li>',
            'outerMenuItemLinkActive' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'navbarText' => '<p class="navbar-text{{attrs.class}}"{{attrs}}>{{content}}</p>',
        ],
        'templateClass' => 'Bootstrap\View\BootstrapStringTemplate',
        'autoActiveLink' => true
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
     * - `fixed` [Fixed navbar](http://getbootstrap.com/components/#navbar-fixed-top). Possible values are `'top'`, `'bottom'`, `false`. Default is `false`.
     * - `fluid` Fluid navabar. Default is `false`.
     * - `inverse` [Inverted navbar](http://getbootstrap.com/components/#navbar-inverted). Default is `false`.
     * - `responsive` Responsive navbar. Default is `true`.
     * - `static` [Static navbar](http://getbootstrap.com/components/#navbar-static-top). Default is `false`.
     * - `templateVars` Provide template variables for the template.
     * - Other attributes will be assigned to the navbar element.
     *
     * @param string $brand Brand name.
     * @param array $options Array of options. See above.
     *
     * @return A string containing the HTML starting element of the navbar.
     */
    public function create($brand, $options = []) {

        $options += [
            'id' => 'navbar',
            'fixed' => false,
            'responsive' => true,
            'static' => false,
            'inverse' => false,
            'fluid' => false,
            'templateVars' => []
        ];

        $this->_responsive = $options['responsive'];
        $fixed = $options['fixed'];
        $static = $options['static'];
        $inverse = $options['inverse'];
        $fluid = $options['fluid'];

        /** Generate options for outer div. **/
        $type = $inverse ? 'inverse' : 'default';

        if ($fixed !== false) {
            $options = $this->addClass($options, 'navbar-fixed-'.$fixed);
        }
        if ($static !== false) {
            $options = $this->addClass($options, 'navbar-static-top');
        }

        if ($brand) {
            if (is_string($brand)) {
                $brand = [
                    'name' => $brand,
                    'url' => '/'
                ];
            }
            $brand = $this->formatTemplate('brand', [
                'content' => $brand['name'],
                'url' => $this->Url->build($brand['url']),
                'attrs' => $this->templater()->formatAttributes($brand, ['name', 'url']),
                'templateVars' => $options['templateVars']
            ]);
        }

        $toggleButton = '';
        if ($this->_responsive) {
            $toggleButton = $this->formatTemplate('toggleButton', [
                'content' => __('Toggle navigation'),
                'id' => $options['id']
            ]);
        }

        $containerStart = $this->formatTemplate('containerStart', [
            'containerClass' => $fluid ? 'container-fluid' : 'container',
            'attrs' => $this->templater()->formatAttributes([]),
            'templateVars' => $options['templateVars']
        ]);

        $responsiveStart = '';
        if ($this->_responsive) {
            $responsiveStart .= $this->formatTemplate('responsiveStart', [
                'id' => $options['id'],
                'attrs' => $this->templater()->formatAttributes([]),
                'templateVars' => $options['templateVars']
            ]);
        }

        $header = '';
        if ($this->_responsive || $brand) {
            $header = $this->formatTemplate('header', [
                'toggleButton' => $toggleButton,
                'brand' => $brand
            ]);
        }

        return $this->formatTemplate('navbarStart', [
            'header' => $header,
            'type' => $type,
            'responsiveStart' => $responsiveStart,
            'containerStart' => $containerStart,
            'attrs' => $this->templater()->formatAttributes($options, ['id', 'fixed', 'responsive', 'static', 'fluid', 'inverse']),
            'templateVars' => $options['templateVars']
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
     * @param array $options Array of attributes for the wrapper tag.
     * @param array $linkOptions Array of attributes for the link.
     *
     * @return string A HTML tag wrapping the link.
     */
    public function link($name, $url = '', array $options = [], array $linkOptions = []) {
        $url = $this->Url->build($url);
        $options += [
            'active' => $this->config('autoActiveLink') && $this->compareUrls($url),
            'templateVars' => []
        ];
        $linkOptions += [
            'templateVars' => []
        ];
        $active = $options['active'] ? 'Active' : '';
        $level = $this->_level > 1 ? 'inner' : 'outer';
        $template = $level.'MenuItem'.$active;
        $linkTemplate = $level.'MenuItemLink'.$active;
        $link = $this->formatTemplate($linkTemplate, [
            'content' => $name,
            'url' => $url,
            'attrs' => $this->templater()->formatAttributes($linkOptions),
            'templateVars' => $linkOptions['templateVars']
        ]);
        return $this->formatTemplate($template, [
            'link' => $link,
            'attrs' => $this->templater()->formatAttributes($options, ['active']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Add a button to the navbar.
     *
     * @param string $name Text of the button.
     * @param array $options Options sent to the `Form::button` method.
     *
     * @return string A HTML navbar button.
     */
    public function button($name, array $options = []) {
        $options += [
            'type' => 'button'
        ];
        $options = $this->addClass($options, 'navbar-btn');
        return $this->Form->button($name, $options);
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
     *
     * @return A HTML dropdown divider tag.
     */
    public function divider(array $options = []) {
        $options += ['templateVars' => []];
        return $this->formatTemplate('innerMenuItemDivider', [
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Add a header to an inner menu of the navbar.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the header template.
     * - Other attributes will be assigned to the header element.
     **
     * @param string $name Title of the header.
     * @param array $options Array of options for the wrapper tag.
     *
     * @return A HTML header tag.
     */
    public function header($name, array $options = []) {
        $options += ['templateVars' => []];
        return $this->formatTemplate('innerMenuItemHeader', [
            'content' => $name,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
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
     *
     * @return string A HTML element wrapping the text for the navbar.
     */
    public function text($text, $options = []) {
        $options += [
            'templateVars' => []
        ];
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
        return $this->formatTemplate('navbarText', [
            'content' => $text,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
    }


    /**
     * Add a search form to the navbar.
     *
     * ### Options
     *
     * - `align` Search form alignment. Default is `'left'`.
     * - Other options will be passed to the `Form::searchForm` method.
     *
     * @deprecated 3.1.0
     *
     * @param mixed $model   Model for BootstrapFormHelper::searchForm method.
     * @param array $options Array of options. See above.
     *
     * @return string An HTML search form for the navbar.
     */
    public function searchForm($model = null, $options = []) {
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
     * ### Options
     *
     * - `templateVars` Provide template variables for the menu template.
     * - Other attributes will be assigned to the menu element.
     *
     * ### Link Options
     *
     * - `caret` HTML caret element. Default is `'<span class="caret"></span>'`.
     * - Other attributes will be assigned to the link element.
     *
     * ### List Options
     *
     * - Other attributes will be assigned to the list element.
     *
     * @param string $name Name of the menu.
     * @param string|array $url URL for the menu.
     * @param array $options Array of options for the wrapping element.
     * @param array $linkOptions Array of options for the link. See above.
     * @param array $listOptions Array of options for the openning `ul` elements.
     *
     * @return string HTML elements to start a menu.
     */
    public function beginMenu($name = null, $url = null, $options = [],
                              $linkOptions = [], $listOptions = []) {
        $template = 'outerMenuStart';
        $templateOptions = [];
        if (is_array($name)) {
            $options = $name;
        }
        $options += [
            'templateVars' => []
        ];
        if ($this->_level == 1) {
            $linkOptions += [
                'caret' => '<span class="caret"></span>'
            ];
            $template = 'innerMenuStart';
            $templateOptions['dropdownLink'] = $this->formatTemplate('dropdownLink', [
                'content' => $name,
                'caret' => $linkOptions['caret'],
                'url' => $url ? $this->Url->build($url) : '#',
                'attrs' => $this->templater()->formatAttributes($linkOptions, ['caret'])
            ]);
            $templateOptions['dropdownMenuStart'] = $this->formatTemplate('dropdownMenuStart', [
                'attrs' => $this->templater()->formatAttributes($listOptions)
            ]);
        }
        $this->_level += 1;
        return $this->formatTemplate($template, $templateOptions + [
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * End a menu.
     *
     * @return string HTML elements to close a menu.
     */
    public function endMenu() {
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
    public function end() {
        $containerEnd = $this->formatTemplate('containerEnd', []);
        $responsiveEnd = '';
        if ($this->_responsive) {
            $responsiveEnd = $this->formatTemplate('responsiveEnd', []);
        }
        return $this->formatTemplate('navbarEnd', [
            'containerEnd' => $containerEnd,
            'responsiveEnd' => $responsiveEnd
        ]);
    }

}

?>
