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

use Cake\View\Helper\HtmlHelper;

/**
 * Html Helper class for easy use of HTML widgets.
 *
 * HtmlHelper encloses all methods needed while working with HTML pages.
 *
 * @property UrlHelper $Url
 *
 * @link http://book.cakephp.org/3.0/en/views/helpers/html.html
 */
class BootstrapHtmlHelper extends HtmlHelper {

    use BootstrapTrait;

    /**
     * Default config for the helper.
     *
     * ### Options:
     *
     * - `alert` Default options for alert.
     * - `label` Default options for labels.
     * - `progress` Default options for progress bar.
     * - `tooltip` Default options for tooltips.
     * - See [CakePHP documentation](https://api.cakephp.org/3.3/class-Cake.View.Helper.HtmlHelper.html#$_defaultConfig) for extra configuration options.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'meta' => '<meta{{attrs}}/>',
            'metalink' => '<link href="{{url}}"{{attrs}}/>',
            'link' => '<a href="{{url}}"{{attrs}}>{{content}}</a>',
            'mailto' => '<a href="mailto:{{url}}"{{attrs}}>{{content}}</a>',
            'image' => '<img src="{{url}}"{{attrs}}/>',
            'tableheader' => '<th{{attrs}}>{{content}}</th>',
            'tableheaderrow' => '<tr{{attrs}}>{{content}}</tr>',
            'tablecell' => '<td{{attrs}}>{{content}}</td>',
            'tablerow' => '<tr{{attrs}}>{{content}}</tr>',
            'block' => '<div{{attrs}}>{{content}}</div>',
            'blockstart' => '<div{{attrs}}>',
            'blockend' => '</div>',
            'tag' => '<{{tag}}{{attrs}}>{{content}}</{{tag}}>',
            'tagstart' => '<{{tag}}{{attrs}}>',
            'tagend' => '</{{tag}}>',
            'tagselfclosing' => '<{{tag}}{{attrs}}/>',
            'para' => '<p{{attrs}}>{{content}}</p>',
            'parastart' => '<p{{attrs}}>',
            'css' => '<link rel="{{rel}}" href="{{url}}"{{attrs}}/>',
            'style' => '<style{{attrs}}>{{content}}</style>',
            'charset' => '<meta charset="{{charset}}"/>',
            'ul' => '<ul{{attrs}}>{{content}}</ul>',
            'ol' => '<ol{{attrs}}>{{content}}</ol>',
            'li' => '<li{{attrs}}>{{content}}</li>',
            'javascriptblock' => '<script{{attrs}}>{{content}}</script>',
            'javascriptstart' => '<script>',
            'javascriptlink' => '<script src="{{url}}"{{attrs}}></script>',
            'javascriptend' => '</script>',

            // New templates for Bootstrap
            'icon' => '<i aria-hidden="true" class="glyphicon glyphicon-{{type}}{{attrs.class}}"{{attrs}}></i>',
            'label' => '<span class="label label-{{type}}{{attrs.class}}"{{attrs}}>{{content}}</span>',
            'badge' => '<span class="badge{{attrs.class}}"{{attrs}}>{{content}}</span>',
            'alert' => '<div class="alert alert-{{type}}{{attrs.class}}" role="alert"{{attrs}}>{{close}}{{content}}</div>',
            'alertCloseButton' => 
                '<button type="button" class="close{{attrs.class}}" data-dismiss="alert" aria-label="{{label}}"{{attrs}}>{{content}}</button>',
            'alertCloseContent' => '<span aria-hidden="true">&times;</span>',
            'tooltip' => '<{{tag}} data-toggle="{{toggle}}" data-placement="{{placement}}" title="{{tooltip}}">{{content}}</{{tag}}>',
            'progressBar' => 
'<div class="progress-bar progress-bar-{{type}}{{attrs.class}}" role="progressbar" 
aria-valuenow="{{width}}" aria-valuemin="{{min}}" aria-valuemax="{{max}}" style="width: {{width}}%%;"{{attrs}}>{{inner}}</div>',
            'progressBarInner' => '<span class="sr-only">{{width}}%%</span>',
            'progressBarContainer' => '<div class="progress{{attrs.class}}"{{attrs}}>{{content}}</div>'
        ],
        'templateClass' => 'Bootstrap\View\BootstrapStringTemplate',
        'tooltip' => [
            'tag'       => 'span',
            'placement' => 'right',
            'toggle'    => 'tooltip'
        ],
        'label' => [
            'type' => 'default'
        ],
        'alert' => [
            'type' => 'warning',
            'close' => true
        ],
        'progress' => [
            'type' => 'primary'
        ]
    ];

    /**
     * Create an icon using the template `icon`.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the `icon` template.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $icon Name of the icon.
     * @param array $options Array of options. See above.
     *
     * @return string The HTML icon.
     */
    public function icon($icon, array $options = []) {
        $options += [
            'templateVars' => []
        ];
        return $this->formatTemplate('icon', [
            'type' => $icon,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Create a Twitter Bootstrap span label.
     *
     * The second parameter may either be `$type` or `$options` (in which case 
     * the third parameter is not used, and the label type can be specified in the
     * `$options` array).
     *
     * ### Options
     *
     * - `tag` The HTML tag to use.
     * - `type` The type of the label.
     * - `templateVars` Provide template variables for the `label` template.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $text The label text
     * @param string|array $type The label type (default, primary, success, warning,
     * info, danger) or the array of options (see `$options`).
     * @param array $options Array of options. See above. Default values are retrieved
     * from the configuration.
     *
     * @return string The HTML label element.
     */
    public function label($text, $type = null, $options = []) {
        if (is_string($type)) {
            $options['type'] = $type;
        }
        else if (is_array($type)) {
            $options = $type;
        }
        $options += $this->config('label') + [
            'templateVars' => []
        ];
        $type = $options['type'];
        return $this->formatTemplate('label', [
            'type' => $options['type'],
            'content' => $text,
            'attrs' => $this->templater()->formatAttributes($options, ['type']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Create a Twitter Bootstrap badge.
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the `badge` template.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $text The badge text.
     *
     * @param array $options Array of attributes for the span element.
     */
    public function badge($text, $options = []) {
        $options += [
            'templateVars' => []
        ];
        return $this->formatTemplate('badge', [
            'content' => $text,
            'attrs' => $this->templater()->formatAttributes($options),
            'templateVars' => $options['templateVars']
        ]);
    }


    /**
     * @deprecated 3.3.6 (CakePHP) Use the BreadcrumbsHelper instead.
     */
    public function getCrumbList(array $options = [], $startText = false) {
        $options['separator'] = '';
        $options = $this->addClass($options, 'breadcrumb');
        return parent::getCrumbList($options, $startText);
    }

    /**
     * Create a Twitter Bootstrap style alert block, containing text.
     *
     * The second parameter may either be `$type` or `$options` (in this case,
     * the third parameter is not used, and the alert type can be specified in the
     * `$options` array).
     *
     * ### Options
     *
     * - `close` Dismissible alert. See configuration for default.
     * - `type` The type of the alert. See configuration for default.
     * - `templateVars` Provide template variables for the `alert` template.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $text The alert text.
     * @param string|array $type The type of the alert.
     * @param array $options Array of options. See above.
     *
     * @return string A HTML bootstrap alert element.
     */
    public function alert($text, $type = null, $options = []) {
        if (is_string($type)) {
            $options['type'] = $type;
        }
        else if (is_array($type)) {
            $options = $type;
        }
        $options += $this->config('alert') + [
            'templateVars' => []
        ];
        $close = null;
        if ($options['close']) {
            $closeContent = $this->formatTemplate('alertCloseContent', [
                'templateVars' => $options['templateVars']
            ]);
            $close = $this->formatTemplate('alertCloseButton', [
                'label' => __('Close'),
                'content' => $closeContent,
                'attrs' => $this->templater()->formatAttributes([]),
                'templateVars' => $options['templateVars']
            ]);
            $options = $this->addClass($options, 'alert-dismissible');
        }
        return $this->formatTemplate('alert', [
            'type' => $options['type'],
            'close' => $close,
            'content' => $text,
            'attrs' => $this->templater()->formatAttributes($options, ['close', 'type']),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Create a Twitter Bootstrap style tooltip.
     *
     * ### Options
     *
     * - `toggle` The 'data-toggle' HTML attribute.
     * - `placement` The `data-placement` HTML attribute.
     * - `tag` The tag to use.
     * - `templateVars` Provide template variables for the `tooltip` template.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $text The HTML tag inner text.
     * @param string $tooltip The tooltip text.
     * @param array  $options An array of options. See above. Default values are retrieved
     * from the configuration.
     *
     * @return string The text wrapped in the specified HTML tag with a tooltip.
     */
    public function tooltip($text, $tooltip, $options = []) {
        $options += $this->config('tooltip') + [
            'tooltip' => $tooltip,
            'templateVars' => []
        ];
        return $this->formatTemplate('tooltip', [
            'content' => $text,
            'attrs' => $this->templater()->formatAttributes($options, ['tag', 'toggle', 'placement', 'tooltip']),
            'templateVars' => array_merge($options, $options['templateVars'])
        ]);
    }

    /**
     * Create a Twitter Bootstrap style progress bar.
     *
     * ### Bar options:
     *
     * - `active` If `true` the progress bar will be active. Default is `false`.
     * - `max` Maximum value for the progress bar. Default is `100`.
     * - `min` Minimum value for the progress bar. Default is `0`.
     * - `striped` If `true` the progress bar will be striped. Default is `false`.
     * - `type` A string containing the `type` of the progress bar (primary, info, danger,
     * success, warning). Default to `'primary'`.
     * - `templateVars` Provide template variables for the `progressBar` template.
     * - Other attributes will be assigned to the progress bar element.
     *
     * @param int|array $widths
     *   - `int` The width (in %) of the bar.
     *   - `array` An array of bars, with, for each bar, the following fields:
     *      - `width` **required** The width of the bar.
     *      - Other options possible (see above).
     * @param array $options Array of options. See above.
     *
     * @return string The HTML bootstrap progress bar.
     */
    public function progress($widths, array $options = []) {
        $options += $this->config('progress') + [
            'striped' => false,
            'active'  => false,
            'min' => 0,
            'max' => 100,
            'templateVars' => []
        ];
        if (!is_array($widths)) {
            $widths = [
                ['width' => $widths]
            ];
        }
        $bars = '';
        foreach ($widths as $width) {
            $width += $options;
            if ($width['striped']) {
                $width = $this->addClass($width, 'progress-bar-striped');
            }
            if ($width['active']) {
                $width = $this->addClass($width, 'active');
            }
            $inner = $this->formatTemplate('progressBarInner', [
                'width' => $width['width']
            ]);

            $bars .= $this->formatTemplate('progressBar', [
                'inner' => $inner,
                'type' => $width['type'],
                'min' => $width['min'],
                'max' => $width['max'],
                'width' => $width['width'],
                'attrs' => $this->templater()->formatAttributes($width, ['striped', 'active', 'min', 'max', 'type', 'width']),
                'templateVars' => $width['templateVars']
            ]);
        }
        return $this->formatTemplate('progressBarContainer', [
            'content' => $bars,
            'attrs' => $this->templater()->formatAttributes([]),
            'templateVars' => $options['templateVars']
        ]);
    }

    /**
     * Create & return a twitter bootstrap dropdown menu.
     *
     * @deprecated 3.1.0
     *
     * @param array $menu HTML tags corresponding to menu options (which will be wrapped
     *              into `<li>` tag). To add separator, pass `'divider'`.
     * @param array $options Attributes for the wrapper (change it with tag).
     *
     * @return string
     */
    public function dropdown(array $menu = [], array $options = []) {
        $output = '' ;
        foreach ($menu as $action) {
            if ($action === 'divider' || (is_array($action) && $action[0] === 'divider')) {
                $output .= '<li role="presentation" class="divider"></li>' ;
            }
            elseif (is_array($action)) {
                if ($action[0] === 'header') {
                    $output .= '<li role="presentation" class="dropdown-header">'
                            .$action[1]
                            .'</li>' ;
                }
                else {
                    if ($action[0] === 'link') {
                        array_shift($action); // Remove first cell
                    }
                    $name = array_shift($action) ;
                    $url  = array_shift($action) ;
                    $action['role'] = 'menuitem' ;
                    $action['tabindex'] = -1 ;
                    $output .= '<li role="presentation">'
                            .$this->link($name, $url, $action).'</li>';
                }
            }
            else {
                $output .= '<li role="presentation">'.$action.'</li>' ;
            }
        }
        $options = $this->addClass($options, 'dropdown-menu');
        $options['role'] = 'menu';
        $options += ['tag' => 'ul'];
        $tag = $options['tag'];
        unset($options['tag']);
        return $this->tag($tag, $output, $options) ;
    }

    /**
     * Create a formatted collection of elements while
     * maintaining proper bootstrappy markup. Useful when
     * displaying, for example, a list of products that would require
     * more than the maximum number of columns per row.
     *
     * @deprecated 3.1.0
     *
     * @param int|string $breakIndex       Divisible index that will trigger a new row
     * @param array      $data             Collection of data used to render each column
     * @param callable   $determineContent A callback that will be called with the
     * data required to render an individual column
     *
     * @return string
     */
    public function splicedRows($breakIndex, array $data, callable $determineContent) {
        $rowsHtml = '<div class="row">';

        $count = 1;
        foreach ($data as $index => $colData) {
            $rowsHtml .= $determineContent($colData);

            if ($count % $breakIndex === 0) {
                $rowsHtml .= '<div class="clearfix hidden-xs hidden-sm"></div>';
            }

            $count++;
        }

        $rowsHtml .= '</div>';
        return $rowsHtml;

    }

}

?>
