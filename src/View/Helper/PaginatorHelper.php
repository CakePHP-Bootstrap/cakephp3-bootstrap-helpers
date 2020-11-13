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

/**
 * Pagination Helper class for easy generation of pagination links.
 *
 * PaginationHelper encloses all methods needed when working with pagination.
 *
 * @property \Cake\View\Helper\UrlHelper $Url
 * @property \Cake\View\Helper\NumberHelper $Number
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html
 */
class PaginatorHelper extends \Cake\View\Helper\PaginatorHelper {

    use ClassTrait;
    use EasyIconTrait;

    /**
     * Other helpers used by PanelHelper.
     *
     * @var array
     */
    public $helpers = [
        'Url', 'Number', 'Html'
    ];

    /**
     * Default configuration for this class.
     *
     * Options: Holds the default options for pagination links.
     *
     * The values that may be specified are:
     *
     * - `url` Url of the action. See Router::url().
     * - `url['sort']` the key that the recordset is sorted.
     * - `url['direction']` Direction of the sorting (default: 'asc').
     * - `url['page']` Page number to use in links.
     * - `model` The name of the model.
     * - `escape` Defines if the title field for the link should be escaped (default: true).
     *
     * Templates: the templates used by this class.
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
            'ellipsis' => '<li class="ellipsis disabled"><a>&hellip;</a></li>',
            'sort' => '<a href="{{url}}">{{text}}</a>',
            'sortAsc' => '<a class="asc" href="{{url}}">{{text}}</a>',
            'sortDesc' => '<a class="desc" href="{{url}}">{{text}}</a>',
            'sortAscLocked' => '<a class="asc locked" href="{{url}}">{{text}}</a>',
            'sortDescLocked' => '<a class="desc locked" href="{{url}}">{{text}}</a>',
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate',
    ];

    /**
     * Calculates the start and end for the pagination numbers.
     *
     * @param array $params Params from the numbers() method.
     * @param array $options Options from the numbers() method.
     * @return array An array with the start and end numbers.
     */
    protected function _getNumbersStartAndEnd($params, $options): array {
        $half = (int)($options['modulus'] / 2);
        $end = max(1 + $options['modulus'], $params['page'] + $half);
        $start = min($params['pageCount'] - $options['modulus'], $params['page'] - $half - $options['modulus'] % 2);

        // See the numbers method.
        $first = isset($options['first_']) ? $options['first_'] : $options['first'];
        $last = isset($options['last_']) ? $options['last_'] : $options['last'];

         if ($first) {
             $first = is_int($first) ? $first : 1;
             if ($start <= $first + 2) {
                 $start = 1;
             }
         }
         if ($last) {
             $last = is_int($last) ? $last : 1;
             if ($end >= $params['pageCount'] - $last - 1) {
                 $end = $params['pageCount'];
             }
         }
         $end = min($params['pageCount'], $end);
         $start = max(1, $start);
         return [$start, $end];
     }

    /**
     * Returns a set of numbers for the paged result set using a modulus to decide how
     * many numbers to show on each side of the current page (default: 8).
     *
     * ```
     * $this->Paginator->numbers(['first' => 2, 'last' => 2]);
     * ```
     *
     * Using the first and last options you can create links to the beginning and end of
     * the page set.
     *
     * ### Options
     *
     * - `before` Content to be inserted before the numbers, but after the first links.
     * - `after` Content to be inserted after the numbers, but before the last links.
     * - `model` Model to create numbers for, defaults to PaginatorHelper::defaultModel()
     * - `modulus` How many numbers to include on either side of the current page, defaults
     * to 8. Set to `false` to disable and to show all numbers.
     * - `first` Whether you want first links generated, set to an integer to define the
     * number of 'first' links to generate. If a string is set a link to the first page will
     * be generated with the value as the title.
     * - `last` Whether you want last links generated, set to an integer to define the
     * number of 'last' links to generate. If a string is set a link to the last page will
     * be generated with the value as the title.
     * - `size` Size of the pagination numbers (`'small'`, `'normal'`, `'large'`). Default
     * is `'normal'`.
     * - `templates` An array of templates, or template file name containing the templates
     * you'd like to use when generating the numbers. The helper's original templates will
     * be restored once numbers() is done.
     * - `url` An array of additional URL options to use for link generation.
     *
     * The generated number links will include the 'ellipsis' template when the `first` and
     * `last` options and the number of pages exceed the modulus. For example if you have 25
     * pages, and use the first/last options and a modulus of 8, ellipsis content will be
     * inserted after the first and last link sets.
     *
     * @param array $options Options for the numbers.
     *
     * @return string numbers string.
     * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html#creating-page-number-links
     */
    public function numbers(array $options = []): string {

        $defaults = [
            'before' => null, 'after' => null, 'model' => $this->defaultModel(),
            'modulus' => 8, 'first' => null, 'last' => null, 'url' => [],
            'prev' => null, 'next' => null, 'class' => '', 'size' => false
        ];
        $options += $defaults;

        $options = $this->addClass($options, 'pagination');

        switch ($options['size']) {
        case 'small':
            $options = $this->addClass($options, 'pagination-sm');
            break;
        case 'large':
            $options = $this->addClass($options, 'pagination-lg');
            break;
        }
        unset($options['size']);

        $options['before'] .= $this->Html->tag('ul', null, ['class' => $options['class']]);
        $options['after'] = '</ul>'.$options['after'];
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
            $title = $options['prev'];
            $opts  = [];
            if (is_array($title)) {
                $title = $title['title'];
                unset ($options['prev']['title']);
                $opts  = $options['prev'];
            }
            $prev = $this->prev($title, $opts);
        }
        unset($options['prev']);

        if ($options['next']) {
            $title = $options['next'];
            $opts  = [];
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

        $ellipsis = $templater->format('ellipsis', []);
        $first = $this->_firstNumber($ellipsis, $params, $start, $options);
        $last = $this->_lastNumber($ellipsis, $params, $end, $options);

        $before = (is_int($options['first']) && $options['first'] > 1) ? $prev.$first : $first.$prev;
        $after  = (is_int($options['last']) && $options['last'] > 1) ? $last.$next : $next.$last;

        $options['before'] = $options['before'].$before;;
        $options['after']  = $after.$options['after'];

        // New options used to allow the _getNumbersStartAndEnd method to work correctly without having
        // the actual last and first number outputed by the _modulusNumbers.
        $options['first_'] = $options['first'];
        $options['last_'] = $options['last'];
        $options['first'] = null;
        $options['last'] = null;

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

    /**
     * Generates a "previous" link for a set of paged records.
     *
     * ### Options:
     *
     * - `disabledTitle` The text to used when the link is disabled. This
     *   defaults to the same text at the active link. Setting to false will cause
     *   this method to return ''.
     * - `escape` Whether you want the contents html entity encoded, defaults to true.
     * - `model` The model to use, defaults to `PaginatorHelper::defaultModel()`.
     * - `url` An array of additional URL options to use for link generation.
     * - `templates` An array of templates, or template file name containing the
     *   templates you'd like to use when generating the link for previous page.
     *   The helper's original templates will be restored once prev() is done.
     *
     * @param string $title Title for the link. Defaults to '<< Previous'.
     * @param array $options Options for pagination link. See above for list of keys.
     *
     * @return string A "previous" link or a disabled link.
     *
     * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html#creating-jump-links
     */
    public function prev($title = '<< Previous', array $options = []): string {
        list($options, $easyIcon) = $this->_easyIconOption($options);
        return $this->_injectIcon(parent::prev($title, $options), $easyIcon);
    }

    /**
     * Generates a "next" link for a set of paged records.
     *
     * ### Options:
     *
     * - `disabledTitle` The text to used when the link is disabled. This
     *   defaults to the same text at the active link. Setting to false will cause
     *   this method to return ''.
     * - `escape` Whether you want the contents html entity encoded, defaults to true
     * - `model` The model to use, defaults to `PaginatorHelper::defaultModel()`.
     * - `url` An array of additional URL options to use for link generation.
     * - `templates` An array of templates, or template file name containing the
     *   templates you'd like to use when generating the link for next page.
     *   The helper's original templates will be restored once next() is done.
     *
     * @param string $title Title for the link. Defaults to 'Next >>'.
     * @param array $options Options for pagination link. See above for list of keys.
     *
     * @return string A "next" link or $disabledTitle text if the link is disabled.
     *
     * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html#creating-jump-links
     */
    public function next($title = 'Next >>', array $options = []): string {
        list($options, $easyIcon) = $this->_easyIconOption($options);
        return $this->_injectIcon(parent::next($title, $options), $easyIcon);
    }

    /**
     * Returns a first or set of numbers for the first pages.
     *
     * ```
     * echo $this->Paginator->first('< first');
     * ```
     *
     * Creates a single link for the first page. Will output nothing if you are on the
     * first page.
     *
     * ```
     * echo $this->Paginator->first(3);
     * ```
     *
     * Will create links for the first 3 pages, once you get to the third or greater page.
     * Prior to that nothing will be output.
     *
     * ### Options:
     *
     * - `model` The model to use defaults to PaginatorHelper::defaultModel()
     * - `escape` Whether or not to HTML escape the text.
     * - `url` An array of additional URL options to use for link generation.
     *
     * @param string|int $first   if string use as label for the link. If numeric, the number
     * of page links you want at the beginning of the range.
     * @param array      $options An array of options.
     *
     * @return string numbers string.
     *
     * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html#creating-jump-links
     */
    public function first($first = '<< first', array $options = []): string {
        list($options, $easyIcon) = $this->_easyIconOption($options);
        return $this->_injectIcon(parent::first($first, $options), $easyIcon);
    }

    /**
     * Returns a last or set of numbers for the last pages.
     *
     * ```
     * echo $this->Paginator->last('last >');
     * ```
     *
     * Creates a single link for the last page. Will output nothing if you are on the
     * last page.
     *
     * ```
     * echo $this->Paginator->last(3);
     * ```
     *
     * Will create links for the last 3 pages. Once you enter the page range, no output
     * will be created.
     *
     * ### Options:
     *
     * - `model` The model to use defaults to PaginatorHelper::defaultModel()
     * - `escape` Whether or not to HTML escape the text.
     * - `url` An array of additional URL options to use for link generation.
     *
     * @param string|int $last    if string use as label for the link, if numeric print
     * page numbers.
     * @param array      $options Array of options.
     *
     * @return string numbers string.
     *
     * @link http://book.cakephp.org/3.0/en/views/helpers/paginator.html#creating-jump-links
     */
    public function last($last = 'last >>', array $options = []): string {
        list($options, $easyIcon) = $this->_easyIconOption($options);
        return $this->_injectIcon(parent::last($last, $options), $easyIcon);
    }

}

?>
