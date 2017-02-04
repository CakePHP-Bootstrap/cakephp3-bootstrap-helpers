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
     * - `progressTextFormat` Format string to display text in progress bar.
     * - `tooltip` Default options for tooltips.
     * - `useFontAwesome` Set to true to use FontAwesome icon instead of glyphicon.
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
            'javascriptend' => '</script>'
        ],
        'useFontAwesome' => false,
        'progressTextFormat' => '%d%% Complete',
        'tooltip' => [
            'tag'       => 'span',
            'placement' => 'right',
            'toggle'    => 'tooltip'
        ],
        'label' => [
            'tag'  => 'span',
            'type' => 'default'
        ],
        'alert' => [
            'type' => 'warning'
        ],
        'progress' => [
            'type' => 'primary'
        ]
    ];

    /**
     * Create a glyphicon or font awesome icon depending on the value of the configuration
     * option `useFontAwesome`.
     *
     * **Note:** This method is a generic way of calling faIcon or glIcon depending on default
     * chosen type of icons.
     *
     * @param string $icon    Name of the icon
     * @param array  $options Extra attributes for the `<i>` tag.
     *
     * @return string The HTML icon.
     */
    public function icon($icon, array $options = []) {
        return $this->config('useFontAwesome')?
               $this->faIcon($icon, $options) : $this->glIcon($icon, $options);
    }

    /**
     * Create a font awesome icon.
     *
     * @param string $icon    Name of the icon
     * @param array  $options Extra attributes for the `<i>` tag.
     *
     * @return string The HTML icon.
     */
    public function faIcon($icon, $options = []) {
        $options = $this->addClass($options, 'fa');
        $options = $this->addClass($options, 'fa-'.$icon);
        $options += [
            'aria-hidden' => 'true'
        ];

        return $this->tag('i', '', $options);
    }

    /**
     * Create a glyphicon icon.
     *
     * @param string $icon    Name of the icon
     * @param array  $options Extra attributes for the `<i>` tag.
     *
     * @return string The HTML icon.
     */
    public function glIcon($icon, $options = []) {
        $options = $this->addClass($options, 'glyphicon');
        $options = $this->addClass($options, 'glyphicon-'.$icon);
        $options += [
            'aria-hidden' => 'true'
        ];

        return $this->tag('i', '', $options);
    }

    /**
     * Create a Twitter Bootstrap span label.
     *
     * The second parameter may either be `$type` or `$options` (in this case,
     * the third parameter is not used, and the label type can be specified in the
     * `$options` array).
     *
     * ### Options
     *
     * - `tag` The HTML tag to use.
     * - `type` The type of the label.
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
        $options += [
            'tag' => $this->config('label.tag'),
            'type' => $this->config('label.type')
        ];
        $type = $options['type'];
        $tag = $options['tag'];
        unset ($options['type'], $options['tag']);
        $options = $this->addClass($options, 'label');
        $options = $this->addClass($options, 'label-'.$type);
        return $this->tag($tag, $text, $options);
    }

    /**
     * Create a Twitter Bootstrap badge.
     *
     * @param string $text The badge text.
     *
     * @param array $options Array of attributes for the span element.
     */
    public function badge($text, $options = []) {
        $options = $this->addClass($options, 'badge');
        return $this->tag('span', $text, $options);
    }


    /**
     * @deprecated 3.3.6 (CakePHP) Use the BreadcrumbsHelper instead.
     */
    public function getCrumbList(array $options = [], $startText = false) {
        $options['separator'] = '';
        $options = $this->addClass($options, 'breadcrumb');
        return parent::getCrumbList ($options, $startText);
    }

    /**
     * Create a Twitter Bootstrap style alert block, containing text.
     *
     * The second parameter may either be `$type` or `$options` (in this case,
     * the third parameter is not used, and the label type can be specified in the
     * `$options` array).
     *
     * ### Options
     *
     * - `type` The type of the label.
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string       $text The alert text.
     * @param string|array $type The type of the alert.
     * @param array        $options Options that will be passed to the `Html::div` method.
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
        $options += [
            'type' => $this->config('alert.type')
        ];
        $button = $this->tag('button', '&times;', [
            'type' => 'button',
            'class' => 'close',
            'data-dismiss' => 'alert',
            'aria-hidden' => true
        ]);
        $type = $options['type'];
        unset($options['type']);
        $options = $this->addClass($options, 'alert');
        if ($type) {
            $options = $this->addClass($options, 'alert-'.$type);
        }
        $class = $options['class'];
        unset($options['class']);
        return $this->div($class, $button.$text, $options);
    }

    /**
     * Create a Twitter Bootstrap style tooltip.
     *
     * ### Options
     *
     * - `data-toggle` The 'data-toggle' HTML attribute.
     * - `placement` The `data-placement` HTML attribute.
     * - `tag` The tag to use.
     * - `title` The title of the tooltip (default to $tooltip).
     * - Other attributes will be assigned to the wrapper element.
     *
     * @param string $text    The HTML tag inner text.
     * @param string $tooltip The tooltip text.
     * @param array  $options An array of options. See above. Default values are retrieved
     * from the configuration.
     *
     * @return string The text wrapped in the specified HTML tag with a tooltip.
     */
    public function tooltip($text, $tooltip, $options = []) {
        $options += [
            'tag'         => $this->config('tooltip.tag'),
            'data-toggle' => $this->config('tooltip.toggle'),
            'placement'   => $this->config('tooltip.placement'),
            'title'       => $tooltip
        ];
        $options['data-placement'] = $options['placement'];
        $tag = $options['tag'];
        unset($options['placement'], $options['tag']);
        return $this->tag($tag, $text, $options);
    }

    /**
     * Create a Twitter Bootstrap style progress bar.
     *
     * ### Options:
     *
     * - `active` If `true` the progress bar will be active. Default is `false`.
     * - `format` Format string for the text value (see configuration for default).
     * - `max` Maximum value for the progress bar. Default is `100`.
     * - `min` Minimum value for the progress bar. Default is `0`.
     * - `striped` If `true` the progress bar will be striped. Default is `false`.
     * - `type` A string containing the `type` of the progress bar (primary, info, danger,
     * success, warning). Default to `'primary'`.
     * - Other attributes will be assigned to the wrapper element.
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
        $options += [
            'striped' => false,
            'active'  => false,
            'format' => $this->config('progressTextFormat')
        ];
        $striped = $options['striped'];
        $active  = $options['active'];
        unset($options['active'], $options['striped']);
        $bars = '';
        if (!is_array($widths)) {
            $widths = [
                array_merge([
                    'width' => $widths
                ], $options)
            ];
        }
        foreach ($widths as $width) {
            $width += [
                'type' => $this->config('progress.type'),
                'min'  => 0,
                'max'  => 100
            ];
            $class = 'progress-bar progress-bar-'.$width['type'];
            $content = $this->tag('span', sprintf($options['format'], $width['width']), [
                'class' => 'sr-only'
            ]);
            $bars .= $this->div($class, $content, [
                'aria-valuenow' => $width['width'],
                'aria-valuemin' => $width['min'],
                'aria-valuemax' => $width['max'],
                'role' => 'progressbar',
                'style' => 'width: '.$width['width'].'%;'
            ]);
        }
        $options = $this->addClass($options, 'progress');
        if ($active) {
            $options = $this->addClass($options, 'active');
        }
        if ($striped) {
            $options = $this->addClass($options, 'progress-striped');
        }
        $classes = $options['class'];
        unset($options['class'], $options['active'], $options['type'],
              $options['striped'], $options['format']);
        return $this->div($classes, $bars, $options);
    }

    /**
     * Create & return a twitter bootstrap dropdown menu.
     *
     * @param array $menu HTML tags corresponding to menu options (which will be wrapped
     *              into `<li>` tag). To add separator, pass `'divider'`.
     * @param array $options Attributes for the wrapper (change it with tag).
     *
     * @return string
     */
    public function dropdown (array $menu = [], array $options = []) {
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
     * @param int|string $breakIndex       Divisible index that will trigger a new row
     * @param array      $data             Collection of data used to render each column
     * @param callable   $determineContent A callback that will be called with the
     * data required to render an individual column
     *
     * @return string
     */
    public function splicedRows ($breakIndex, array $data, callable $determineContent) {
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
