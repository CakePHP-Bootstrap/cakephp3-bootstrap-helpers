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
use Cake\View\StringTemplateTrait;

/**
 * Modal helper library.
 *
 * Automatic generation of Bootstrap HTML modals.
 *
 * @property \Bootstrap\View\Helper\HtmlHelper $Html
 */
class ModalHelper extends Helper {

    use ClassTrait;
    use EasyIconTrait;
    use StringTemplateTrait;

    /**
     * Other helpers used by ModalHelper.
     *
     * @var array
     */
    public $helpers = [
        'Html'
    ];

    /**
     * Default configuration for the ModalHelper.
     *
     * @var array
     */
    public $_defaultConfig = [
        'templates' => [
            'modalStart' => '<div class="modal fade{{attrs.class}}" tabindex="-1" role="dialog"{{attrs}}>{{dialogStart}}{{contentStart}}',
            'modalEnd' => '{{contentEnd}}{{dialogEnd}}</div>',
            'modalDialogStart' => '<div class="modal-dialog{{attrs.class}}" role="document"{{attrs}}>',
            'modalDialogEnd' => '</div>',
            'modalContentStart' => '<div class="modal-content{{attrs.class}}"{{attrs}}>',
            'modalContentEnd' => '</div>',
            'headerStart' => '<div class="modal-header{{attrs.class}}"{{attrs}}>',
            'headerEnd' => '</div>',
            'modalHeaderCloseButton' =>
                '<button type="button" class="close{{attrs.class}}" data-dismiss="modal" aria-label="{{label}}"{{attrs}}>{{content}}</button>',
            'modalHeaderCloseContent' => '<span aria-hidden="true">&times;</span>',
            'modalTitle' => '<h4 class="modal-title{{attrs.class}}"{{attrs}}>{{content}}</h4>',
            'bodyStart' => '<div class="modal-body{{attrs.class}}"{{attrs}}>',
            'bodyEnd' => '</div>',
            'footerStart' => '<div class="modal-footer{{attrs.class}}"{{attrs}}>',
            'footerEnd' => '</div>',
            'modalFooterCloseButton' => '<button type="button" class="btn btn-default{{attrs.class}}" data-dismiss="modal"{{attrs}}>{{content}}</button>'
        ],
        'templateClass' => 'Bootstrap\View\EnhancedStringTemplate'
    ];

    /**
     * Current part of the modal(`null`, `'header'`, `'body'`, `'footer'`).
     *
     * @var string
     */
    protected $_current = null;

    /**
     * Current id of the modal.
     *
     * @var mixed
     */
    protected $_currentId = null;

    /**
     * Open a modal
     *
     * If `$title` is a string, the modal header is created using `$title` as its
     * content and default options.
     *
     * ```php
     * echo $this->Modal->create('My Modal Title');
     * ```
     *
     * If the modal header is created, the modal body is automatically opened after
     * it, except if the `body` options is specified(see below).
     *
     * If `$title` is an array, it is used as `$options`.
     *
     * ```php
     * echo $this->Modal->create(['class' => 'my-modal-class']);
     * ```
     *
     * ### Options
     *
     * - `body` If `$title` is a string, set to `false` to not open the body after
       * the panel header. Default is `true`.
     * - `close` Set to `false` to not add a close button to the modal. Default is `true`.
     * - `id` Identifier of the modal. If specified, a `aria-labelledby` HTML attribute
     * will be added to the modal and the header will be set accordingly.
     * - `size` Size of the modal. Either a shortcut(`'lg'`/`'large'`/`'modal-lg'` or
     *(`'sm'`/`'small'`/`'modal-sm'`) or `false`(no size specified) or a custom class.
     * Other options will be passed to the `Html::div` method for creating the
     * outer modal `<div>`.
     *
     * @param array|string $title The modal title or an array of options.
     * @param array $options Array of options. See above.
     *
     * @return string An HTML string containing opening elements for a modal.
     */
    public function create($title = null, $options = []) {

        if(is_array($title)) {
            $options = $title;
        }

        $this->_currentId = null;
        $this->_current   = null;

        $options += [
            'id' => null,
            'close' => true,
            'body' => true,
            'size' => false,
            'templateVars' => []
        ];

        if($options['id']) {
            $this->_currentId = $options['id'];
            $options['aria-labelledby'] = $this->_currentId.'Label';
        }

        switch($options['size']) {
        case 'lg':
        case 'large':
        case 'modal-lg':
            $size = ' modal-lg';
            break;
        case 'sm':
        case 'small':
        case 'modal-sm':
            $size = ' modal-sm';
            break;
        case false:
            $size = '';
            break;
        default:
            $size = ' '.$options['size'];
            break;
        }
        $options = $this->addClass($options, $size);

        $dialogStart = $this->formatTemplate('modalDialogStart', []);
        $contentStart = $this->formatTemplate('modalContentStart', []);
        $res = $this->formatTemplate('modalStart', [
            'dialogStart' => $dialogStart,
            'contentStart' => $contentStart,
            'attrs' => $this->templater()->formatAttributes($options, ['body', 'close', 'size']),
            'templateVars' => $options['templateVars']
        ]);
        if(is_string($title) && $title) {
            $res .= $this->_createHeader($title, ['close' => $options['close']]);
            if($options['body']) {
                $res .= $this->_createBody();
            }
        }
        return $res;
    }

    /**
     * Closes a modal, cleans part that have not been closed correctly and optionaly
     * adds a footer with buttons to the modal.
     *
     * If `$buttons` is not null, the `footer()` method will be used to create the modal
     * footer using `$buttons` and `$options`:
     *
     * ```php
     * echo $this->Modal->end([$this->Form->button('Save'), $this->Form->button('Close')]);
     * ```
     *
     * @param array $buttons Array of buttons for the `footer()` method or `null`.
     * @param array $options Array of options for the `footer()` method.
     *
     * @return string An HTML string containing closing tags for the modal.
     */
    public function end($buttons = NULL, $options = []) {
        $res = $this->_cleanCurrent();
        if($buttons !== null) {
            $res .= $this->footer($buttons, $options);
        }
        $res .= $this->formatTemplate('modalEnd', [
            'contentEnd' => $this->formatTemplate('modalContentEnd', []),
            'dialogEnd' => $this->formatTemplate('modalDialogEnd', [])
        ]);
        return $res;
    }

    /**
     * Cleans the current modal part and return necessary HTML closing elements.
     *
     * @return string An HTML string containing closing elements.
     */
    protected function _cleanCurrent() {
        if($this->_current) {
            $current = $this->_current;
            $this->_current = null;
            return $this->formatTemplate($current.'End', []);
        }
        return '';
    }

    /**
     * Cleans the current modal part, create a new ones with the given content, and
     * update the internal `_current` variable if necessary.
     *
     * @param string $part The name of the part(`'header'`, `'body'`, `'footer'`).
     * @param string $content The content of the part or `null`.
     * @param array $options Array of options for the `Html::tag` method.
     *
     * @return string
     */
    protected function _part($part, $content = null, $options = []) {
        $options += [
            'templateVars' => []
        ];
        $out = $this->_cleanCurrent();
        $out .= $this->formatTemplate($part.'Start', [
            'attrs' => $this->templater()->formatAttributes($options, ['close']),
            'templateVars' => $options
        ]);
        $this->_current = $part;
        if ($content) {
            $out .= $content;
            $out .= $this->_cleanCurrent();
        }
        return $out;
    }

    /**
     * Create or open a modal header.
     *
     * ### Options
     *
     * - `close` Set to `false` to not add a close button to the modal. Default is `true`.
     * - `templateVars` Provide template variables for the `headerStart` template.
     * - Other attributes will be assigned to the modal header element.
     *
     * @param string $text The modal header content, or null to only open the header.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal header or the complete modal
     * header.
     *
     * @see `BootstrapModalHelper::header`
     */
    protected function _createHeader($title = null, $options = []) {
        $options += [
            'close' => true
        ];
        $out = null;
        if($title) {
            $out = '';
            if($options['close']) {
                $out .= $this->formatTemplate('modalHeaderCloseButton', [
                    'content' => $this->formatTemplate('modalHeaderCloseContent', []),
                    'label' => __('Close')
                ]);
            }
            $out .= $this->formatTemplate('modalTitle', [
                'content' => $title,
                'attrs' => $this->templater()->formatAttributes([
                    'id' => $this->_currentId ? $this->_currentId.'Label' : false
                ])
            ]);
        }
        return $this->_part('header', $out, $options);
    }
    /**
     * Create or open a modal body.
     *
     * ### Options
     * - `templateVars` Provide template variables for the `bodyStart` template.
     * - Other attributes will be assigned to the modal body element.
     *
     * @param string $text The modal body content, or `null` to only open the body.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal body or the complete modal
     * body.
     *
     * @see `BootstrapModalHelper::body`
     */
    protected function _createBody($text = null, $options = []) {
        return $this->_part('body', $text, $options);
    }

    /**
     * Create or open a modal footer.
     *
     * If `$content` is `null` and the `'close'` option(see below) is `true`, a close
     * button is created inside the footer.
     *
     * ### Options
     *
     * - `close` Set to `true` to add a close button to the footer if `$content` is
     * empty. Default is `true`.
     * - `templateVars` Provide template variables for the `footerStart` template.
     * - Other attributes will be assigned to the modal footer element.
     *
     * @param string $content The modal footer content, or `null` to only open the footer.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal footer or the complete modal
     * footer.
     */
    protected function _createFooter($content = null, $options = []) {
        $options += [
            'close' => true
        ];
        if(!$content  && $options['close']) {
            $content .= $this->formatTemplate('modalFooterCloseButton', [
                'content' => __('Close')
            ]);
        }
        return $this->_part('footer', $content, $options);
    }

    /**
     * Create or open a modal header.
     *
     * If `$text` is a string, create a modal header using the specified content
     * and `$options`.
     *
     * ```php
     * echo $this->Modal->header('Header Content', ['class' => 'my-class']);
     * ```
     *
     * If `$text` is `null`, create a formated opening tag for a modal header using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Modal->header(null, ['class' => 'my-class']);
     * ```
     *
     * If `$text` is an array, used it as `$options` and create a formated opening tag for
     * a modal header.
     *
     * ```php
     * echo $this->Modal->header(['class' => 'my-class']);
     * ```
     *
     * ### Options
     *
     * - `close` Set to `false` to not add a close button to the modal. Default is `true`.
     * - `templateVars` Provide template variables for the `headerStart` template.
     * - Other attributes will be assigned to the modal header element.
     *
     * @param string|array $text The modal header content, or an array of options.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal header or the complete modal
     * header.
     */
    public function header($info = null, $options = []) {
        if(is_array($info)) {
            $options = $info;
            $info = null;
        }
        return $this->_createHeader($info, $options);
    }

    /**
     * Create or open a modal body.
     *
     * If `$content` is a string, create a modal body using the specified content and
     * `$options`.
     *
     * ```php
     * echo $this->Modal->body('Modal Content', ['class' => 'my-class']);
     * ```
     *
     * If `$content` is `null`, create a formated opening tag for a modal body using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Modal->body(null, ['class' => 'my-class']);
     * ```
     *
     * If `$content` is an array, used it as `$options` and create a formated opening tag for
     * a modal body.
     *
     * ```php
     * echo $this->Modal->body(['class' => 'my-class']);
     * ```
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the `bodyStart` template.
     * - Other attributes will be assigned to the modal body element.
     *
     * @param array|string $info The body content, or `null`, or an array of options.
     * `$options`.
     * @param array $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal body or the complete modal
     * body.
     */
    public function body($info = null, $options = []) {
        if(is_array($info)) {
            $options = $info;
            $info = null;
        }
        return $this->_createBody($info, $options);
    }

    /**
     * Create or open a modal footer.
     *
     * If `$buttons` is a string, create a modal footer using the specified content
     * and `$options`.
     *
     * ```php
     * echo $this->Modal->footer('Footer Content', ['class' => 'my-class']);
     * ```
     *
     * If `$buttons` is `null`, create a **formated opening tag** for a modal footer using the
     * specified `$options`.
     *
     * ```php
     * echo $this->Modal->footer(null, ['class' => 'my-class']);
     * ```
     *
     * If `$buttons` is an associative array, used it as `$options` and create a
     * **formated opening tag** for a modal footer.
     *
     * ```php
     * echo $this->Modal->footer(['class' => 'my-class']);
     * ```
     *
     * If `$buttons` is a non-associative array, its elements are glued together to
     * create the content. This can be used to generate a footer with buttons:
     *
     * ```php
     * echo $this->Modal->footer([$this->Form->button('Close'), $this->Form->button('Save')]);
     * ```
     *
     * ### Options
     *
     * - `templateVars` Provide template variables for the `footerStart` template.
     * - Other attributes will be assigned to the modal footer element.
     *
     * @param string|array $buttons The footer content, or `null`, or an array of options.
     * @param array        $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal footer or the complete modal
     * footer.
     */
    public function footer($buttons = null, $options = []) {
        if(is_array($buttons)) {
            if(!empty($buttons) && $this->_isAssociativeArray($buttons)) {
                $options = $buttons;
                $buttons = null;
            }
            else {
                $buttons = implode('', $buttons);
            }
        }
        return $this->_createFooter($buttons, $options);
    }

}

?>
