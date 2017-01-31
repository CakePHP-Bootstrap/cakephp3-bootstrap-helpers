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

    use BootstrapTrait ;

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
            'placement' => 'right'
        ],
        'label' => [
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
     *
     * Create a glyphicon or font awesome icon depending on $this->_useFontAwesome.
     *
     * @param string $icon Name of the icon
     * @param array $options Array of options.
     * @return string A HTML icon.
     */
    public function icon ($icon, array $options = []) {
        return $this->config('useFontAwesome')?
               $this->faIcon($icon, $options) : $this->glIcon($icon, $options);
    }

    /**
     * Create a font awesome icon.
     *
     * @param string $icon Name of the icon
     * @param array $options Array of options.
     * @return string A HTML icon.
     */
    public function faIcon ($icon, $options = []) {
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
     * @param string $icon Name of the icon
     * @param array $options Array of options.
     * @return string A HTML icon.
     */
    public function glIcon ($icon, $options = []) {
        $options = $this->addClass($options, 'glyphicon');
        $options = $this->addClass($options, 'glyphicon-'.$icon);
        $options += [
            'aria-hidden' => 'true'
        ];

        return $this->tag('i', '', $options);
    }

    /**
     *
     * Create a Twitter Bootstrap span label.
     *
     * The second parameter may either be $type or $options (in this case, the third parameter
     * is useless, and the label type can be specified in the $options array).
     *
     * ### Options
     *
     * - `type` - The type of the label (see label.type in configuration for the default value)
     * - Other attributes will be assigned to the span element.
     *
     * @param string $text The label text
     * @param string|array $type The label type (default, primary, success, warning, info, danger)
     * @param array $options Array of options. See above.
     * @return A HTML label element
     */
    public function label ($text, $type = null, $options = []) {
        if (is_string($type)) {
            $options['type'] = $type ;
        }
        else if (is_array($type)) {
            $options = $type ;
        }
        $options += [
            'type' => $this->config('label.type')
        ];
        $type = $options['type'];
        unset ($options['type']) ;
        $options = $this->addClass($options, 'label') ;
        $options = $this->addClass($options, 'label-'.$type) ;
        return $this->tag('span', $text, $options) ;
    }

    /**
     *
     * Create a Twitter Bootstrap span badge.
     *
     * @param string $text The badge text
     * @param array $options Array of attributes for the span element.
     */
    public function badge ($text, $options = []) {
        $options = $this->addClass($options, 'badge') ;
        return $this->tag('span', $text, $options) ;
    }


    /**
     * Returns breadcrumbs as a (x)html list
     *
     * This method uses HtmlHelper::tag() to generate list and its elements. Works
     * similar to HtmlHelper::getCrumbs(), so it uses options which every
     * crumb was added with.
     *
     * ### Options
     *
     * - `firstClass` Class for wrapper tag on the first breadcrumb, defaults to 'first'
     * - `lastClass` Class for wrapper tag on current active page, defaults to 'last'
     *
     * @param array $options Array of HTML attributes to apply to the generated list elements.
     * @param string|array|bool $startText This will be the first crumb, if false it defaults to first crumb in array. Can
     *   also be an array, see `HtmlHelper::getCrumbs` for details.
     * @return string|null Breadcrumbs HTML list.
     * @link http://book.cakephp.org/3.0/en/views/helpers/html.html#creating-breadcrumb-trails-with-htmlhelper
     * @deprecated 3.3.6 Use the BreadcrumbsHelper instead
     */
    public function getCrumbList(array $options = [], $startText = false) {
        $options['separator'] = '' ;
        $options = $this->addClass($options, 'breadcrumb') ;
        return parent::getCrumbList ($options, $startText) ;
    }

    /**
     *
     * Create a Twitter Bootstrap style alert block, containing text.
     *
     * The second parameter may either be $type or $options (in this case, the third parameter
     * is useless, and the label type can be specified in the $options array).
     *
     * ### Options
     *
     * - `type` - The type of the label (see label.type in configuration for the default value)
     * - Other attributes will be assigned to the span element.
     *
     * @param string $text The alert text
     * @param string|array $type The type of the alert
     * @param array $options Options that will be passed to Html::div method
     * @return string A HTML bootstrap alert element
     */
    public function alert ($text, $type = null, $options = []) {
        if (is_string($type)) {
            $options['type'] = $type ;
        }
        else if (is_array($type)) {
            $options = $type ;
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
        unset($options['type']) ;
        $options = $this->addClass($options, 'alert') ;
        if ($type) {
            $options = $this->addClass($options, 'alert-'.$type) ;
        }
        $class = $options['class'] ;
        unset($options['class']) ;
        return $this->div($class, $button.$text, $options) ;
    }

    /**
     * Create a Twitter Bootstrap style tooltip.
     *
     * ### Options
     *
     * - `tag` - The tag to use (default 'span')
     * - `data-toggle` - The 'data-toggle' HTML attribute (default 'tooltip')
     * - `placement` - HTML attribute (see configuration value of tooltip.placement for default)
     * - `title` - The title of the tooltip (default to $tooltip)
     * - Other attributes will be assigned to the span element.
     *
     * @param string $text The HTML tag inner text.
     * @param string $tooltip The tooltip text.
     * @param array $options An array of options. See above.
     * @return string The text wrapped in the specified HTML tag with a tooltip.
     *
     **/
    public function tooltip($text, $tooltip, $options = []) {
        $options += [
            'tag'         => 'span',
            'data-toggle' => 'tooltip',
            'placement'   => $this->config('tooltip.placement'),
            'title'       => $tooltip
        ];
        $options['data-placement'] = $options['placement'];
        $tag = $options['tag'];
        unset($options['placement'], $options['tag']);
        return $this->tag($tag, $text, $options);
    }

    /**
     *
     * Create a Twitter Bootstrap style progress bar.
     *
     * @param int|array $widths
     *   - The width (in %) of the bar (style primary, without display)
     *   - An array of bar, with (for each bar) :
     *      - width (only field required)
     *      - type (primary, info, danger, success, warning, default is primary)
     *      - min (integer, default 0)
     *      - max (integer, default 100)
     *      - display (boolean, default false, for text display)
     * @param array $options Array of options that will be passed to Html::div
     *
     * If $widths is only a integer (first case), $options may contains value for the fields
     * specified above.
     *
     * Available BootstrapHtml options:
     *      - striped: boolean, specify if progress bar should be striped
     *      - active: boolean, specify if progress bar should be active
     *
     **/
    public function progress ($widths, array $options = []) {
        $options += [
            'striped' => false,
            'active'  => false,
            'format' => $this->config('progressTextFormat')
        ];
        $striped = $options['striped'];
        $active  = $options['active'];
        unset($options['active'], $options['striped']) ;
        $bars = '' ;
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
                'max'  => 100,
                'display' => false
            ];
            $class = 'progress-bar progress-bar-'.$width['type'];
            $content = $this->tag('span', sprintf($options['format'], $width['width']), [
                'class' => $width['display'] ? '': 'sr-only'
            ]);
            $bars .= $this->div($class, $content, [
                'aria-valuenow' => $width['width'],
                'aria-valuemin' => $width['min'],
                'aria-valuemax' => $width['max'],
                'role' => 'progressbar',
                'style' => 'width: '.$width['width'].'%;'
            ]);
        }
        $options = $this->addClass($options, 'progress') ;
        if ($active) {
            $options = $this->addClass($options, 'active') ;
        }
        if ($striped) {
            $options = $this->addClass($options, 'progress-striped') ;
        }
        $classes = $options['class'];
        unset($options['class'], $options['active'], $options['type'],
              $options['striped'], $options['format']) ;
        return $this->div($classes, $bars, $options) ;
    }

    /**
     *
     * Create & return a twitter bootstrap dropdown menu.
     *
     * @param array $menu HTML tags corresponding to menu options (which will be wrapped
     *              into <li> tag). To add separator, pass 'divider'.
     * @param array $options Attributes for the wrapper (change it with tag)
     *
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
     * @param int|string $breakIndex Divisible index that will trigger a new row
     * @param array $data Collection of data used to render each column
     * @param callable $determineContent A callback that will be called with the
     * data required to render an individual column
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
