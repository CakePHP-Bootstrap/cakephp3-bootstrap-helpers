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
     * @param $icon Name of the icon.
     *
     **/
    public function icon ($icon, $options = []) {
        return $this->config('useFontAwesome')?
            $this->faIcon($icon, $options) : $this->glIcon($icon, $options);
    }

    /**
     * Create a font awesome icon.
     *
     * @param $icon Name of the icon.
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
     * @param $icon Name of the icon.
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
     * @param text The label text
     * @param type The label type (default, primary, success, warning, info, danger)
     * @param options Options for span
     *
     * The second parameter may either be $type or $options (in this case, the third parameter
     * is useless, and the label type can be specified in the $options array).
     *
     * Extra options
     *  - type The type of the label (useless if $type specified)
     *
     **/
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
     * @param text The badge text
     * @param options Options for span
     *
     *
     **/
    public function badge ($text, $options = []) {
        $options = $this->addClass($options, 'badge') ;
        return $this->tag('span', $text, $options) ;
    }

    /**
     *
     * Get crumb lists in a HTML list, with bootstrap like style.
     *
     * @param $options Options for list
     * @param $startText Text to insert before list
     *
     * Unusable options:
     *      - Separator
     **/
    public function getCrumbList(array $options = [], $startText = false) {
        $options['separator'] = '' ;
        $options = $this->addClass($options, 'breadcrumb') ;
        return parent::getCrumbList ($options, $startText) ;
    }

    /**
     *
     * Create a Twitter Bootstrap style alert block, containing text.
     *
     * @param $text The alert text
     * @param $type The type of the alert
     * @param $options Options that will be passed to Html::div method
     *
     * The second parameter may either be $type or $options (in this case, the third parameter
     * is useless, and the label type can be specified in the $options array).
     *
     * Available BootstrapHtml options:
     *      - type: string, type of alert (default, error, info, success ; useless if
     *    $type is specified)
     *
     **/
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
     * @param $text The HTML tag inner text.
     * @param $tooltip The tooltip text.
     * @param $options
     *
     * @options tag The tag to use (default 'span').
     * @options data-toggle HTML attribute (default 'tooltip').
     * @options placement HTML attribute (default from config).
     * @optioms title HTML attribute (default $tooltip).
     *
     * @return The text wrapped in the specified tag with a tooltip.
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
     * @param $widths
     *      - The width (in %) of the bar (style primary, without display)
     *      - An array of bar, with (for each bar) :
     *        - width (only field required)
     *        - type (primary, info, danger, success, warning, default is primary)
     *        - min (integer, default 0)
     *        - max (integer, default 100)
     *        - display (boolean, default false, for text display)
     * @param $options Options that will be passed to Html::div method (only for main div)
     *
     * If $widths is only a integer (first case), $options may contains value for the fields
     * specified above.
     *
     * Available BootstrapHtml options:
     *      - striped: boolean, specify if progress bar should be striped
     *      - active: boolean, specify if progress bar should be active
     *
     **/
    public function progress ($widths, $options = []) {
        $options += [
            'striped' => false,
            'active'  => false
        ];
        $striped = $options['striped'];
        $active  = $options['active'];
        unset($options['active'], $options['striped']) ;
        $bars = '' ;
        if (is_array($widths)) {
            foreach ($widths as $width) {
                $width += [
                    'type' => $this->config('progress.type'),
                    'min'  => 0,
                    'max'  => 100,
                    'display' => false
                ];
                $class = 'progress-bar progress-bar-'.$width['type'];
                $bars .= $this->div($class,
                                    $width['display'] ? $width['width'].'%' : '',
                                    [
                                        'aria-valuenow' => $width['width'],
                                        'aria-valuemin' => $width['min'],
                                        'aria-valuemax' => $width['max'],
                                        'role' => 'progressbar',
                                        'style' => 'width: '.$width['width'].'%;'
                                    ]
                );
            }
        }
        else {
            $options += [
                'type' => $this->config('progress.type'),
                'min'  => 0,
                'max'  => 100,
                'display' => false
            ];
            $class = 'progress-bar progress-bar-'.$options['type'];
            $bars = $this->div($class,
                               $options['display'] ? $widths.'%' : '',
                               [
                                   'aria-valuenow' => $widths,
                                   'aria-valuemin' => $options['min'],
                                   'aria-valuemax' => $options['max'],
                                   'role' => 'progressbar',
                                   'style' => 'width: '.$widths.'%;'
                               ]
            );
            unset($options['type'], $options['min'],
                  $options['max'], $options['display']);
        }
        $options = $this->addClass($options, 'progress') ;
        if ($active) {
            $options = $this->addClass($options, 'active') ;
        }
        if ($striped) {
            $options = $this->addClass($options, 'progress-striped') ;
        }
        $classes = $options['class'];
        unset($options['class']) ;
        return $this->div($classes, $bars, $options) ;
    }

    /**
     *
     * Create & return a twitter bootstrap dropdown menu.
     *
     * @param $menu HTML tags corresponding to menu options (which will be wrapped
     *              into <li> tag). To add separator, pass 'divider'.
     * @param $options Attributes for the wrapper (change it with tag)
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
     * @param $breakIndex int|string divisible index that will trigger a new row
     * @param $data array collection of data used to render each column
     * @param $determineContent callable a callback that will be called with the
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
