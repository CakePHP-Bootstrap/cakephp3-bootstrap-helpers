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

use Cake\View\Helper\PaginatorHelper;

class BootstrapPaginatorHelper extends PaginatorHelper {

    use BootstrapTrait ;

    /**
     * Default config for this class
     *
     * Options: Holds the default options for pagination links
     *
     * The values that may be specified are:
     *
     * - `url` Url of the action. See Router::url()
     * - `url['sort']`  the key that the recordset is sorted.
     * - `url['direction']` Direction of the sorting (default: 'asc').
     * - `url['page']` Page number to use in links.
     * - `model` The name of the model.
     * - `escape` Defines if the title field for the link should be escaped (default: true).
     *
     * Templates: the templates used by this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'options' => [],
        'templates' => [
            'nextActive' => '<li><a href="{{url}}">{{text}}</a></li>',
            'nextDisabled' => '<li class="disabled"><a>{{text}}</a></li>',
            'prevActive' => '<li><a href="{{url}}">{{text}}</a></li>',
            'prevDisabled' => '<li class="disabled"><a>{{text}}</a></li>',
            'counterRange' => '{{start}} - {{end}} of {{count}}',
            'counterPages' => '{{page}} of {{pages}}',
            'first' => '<li><a href="{{url}}">{{text}}</a></li>',
            'last' => '<li><a href="{{url}}">{{text}}</a></li>',
            'number' => '<li><a href="{{url}}">{{text}}</a></li>',
            'current' => '<li class="active"><a href="{{url}}">{{text}}</a></li>',
            'ellipsis' => '<li class="ellipsis disabled"><a>...</a></li>',
            'sort' => '<a href="{{url}}">{{text}}</a>',
            'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
            'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
            'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
            'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
        ]
    ];

    /**
     *
     * Get pagination link list.
     *
     * @param $options Options for link element
     *
     * Extra options:
     *  - size small/normal/large (default normal)
     *
     **/
    public function numbers (array $options = []) {

        $defaults = [
            'before' => null, 'after' => null, 'model' => $this->defaultModel(),
            'modulus' => 8, 'first' => null, 'last' => null, 'url' => [],
            'prev' => null, 'next' => null, 'class' => '', 'size' => false
        ];
        $options += $defaults;

        $options = $this->addClass($options, 'pagination');

        switch ($options['size']) {
        case 'small':
            $options = $this->addClass($options, 'pagination-sm') ;
            break ;
        case 'large':
            $options = $this->addClass($options, 'pagination-lg') ;
            break ;
        }
        unset($options['size']) ;

        $options['before'] .= $this->Html->tag('ul', null, ['class' => $options['class']]);
        $options['after'] = '</ul>'.$options['after'] ;
        unset($options['class']);

        $params = (array)$this->params($options['model']) + ['page' => 1];
        if ($params['pageCount'] <= 1) {
            return false;
        }

        $templater = $this->templater();
        if (isset($options['templates'])) {
            $templater->push();
            $method = is_string($options['templates']) ? 'load' : 'add';
            $templater->{$method}($options['templates']);
        }

        $first = $prev = $next = $last = '';

        /* Previous and Next buttons (addition from standard PaginatorHelper). */

        if ($options['prev']) {
            $title = $options['prev'] ;
            $opts  = [] ;
            if (is_array($title)) {
                $title = $title['title'] ;
                unset ($options['prev']['title']) ;
                $opts  = $options['prev'] ;
            }
            $prev = $this->prev($title, $opts) ;
        }
        unset($options['prev']);

        if ($options['next']) {
            $title = $options['next'] ;
            $opts  = [] ;
            if (is_array($title)) {
                $title = $title['title'];
                unset ($options['next']['title']);
                $opts  = $options['next'];
            }
            $next = $this->next($title, $opts);
        }
        unset($options['next']);

        /* Custom First and Last. */

        list($start, $end) = $this->_getNumbersStartAndEnd($params, $options);

        if ($options['last']) {
            $ellipsis = isset($options['ellipsis']) ?
                      $options['ellipsis'] : is_int($options['last']);
            $ellipsis = $ellipsis ? $templater->format('ellipsis', []) : '';
            $last = $this->_lastNumber($ellipsis, $params, $end, $options);
        }

        if ($options['first']) {
            $ellipsis = isset($options['ellipsis']) ?
                      $options['ellipsis'] : is_int($options['first']);
            $ellipsis = $ellipsis ? $templater->format('ellipsis', []) : '';
            $first = $this->_firstNumber($ellipsis, $params, $start, $options);
        }

        unset($options['ellipsis']);

        $before = is_int($options['first']) ? $prev.$first : $first.$prev;
        $after  = is_int($options['last']) ? $last.$next : $next.$last;
        $options['before'] = $options['before'].$before;;
        $options['after']  = $after.$options['after'];
        $options['first']  = $options['last'] = false;

        if ($options['modulus'] !== false && $params['pageCount'] > $options['modulus']) {
            $out = $this->_modulusNumbers($templater, $params, $options);
        } else {
            $out = $this->_numbers($templater, $params, $options);
        }

        if (isset($options['templates'])) {
            $templater->pop();
        }


        return $out;
    }

    public function prev ($title = '<< Previous', array $options = []) {
        return $this->_easyIcon('parent::prev', $title, $options);
    }

    public function next ($title = 'Next >>', array $options = []) {
        return $this->_easyIcon('parent::next', $title, $options);
    }

    public function first($first = '<< first', array $options = []) {
        return $this->_easyIcon('parent::first', $first, $options);
    }

    public function last($last = 'last >>', array $options = []) {
        return $this->_easyIcon('parent::last', $last, $options);
    }

}

?>
