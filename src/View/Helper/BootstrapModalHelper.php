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

class BootstrapModalHelper extends Helper {

    use BootstrapTrait ;

    /**
     * Other helpers used by BootstrapModalHelper.
     *
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Current part of the modal (`null`, `'header'`, `'body'`, `'footer'`).
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
     * it, except if the `body` options is specified (see below).
     *
     * If `$title` is an array, it is used as `$options`.
     *
     * ```php
     * echo $this->Modal->create(['class' => 'my-modal-class']);
     * ```
     *
     * ### Options
     *
     * - `aria-hidden` HTML attribute. Default is `'true'`.
     * - `body` If `$title` is a string, set to `false` to not open the body after
       * the panel header. Default is `true`.
     * - `close` Set to `false` to not add a close button to the modal. Default is `true`.
     * - `id` Identifier of the modal. If specified, a `aria-labelledby` HTML attribute
     * will be added to the modal and the header will be set accordingly.
     * - `role` HTML attribute. Default is `'dialog'`.
     * - `size` Size of the modal. Either a shortcut (`'lg'`/`'large'`/`'modal-lg'` or
     * (`'sm'`/`'small'`/`'modal-sm'`) or `false` (no size specified) or a custom class.
     * - `tabindex` HTML attribute. Default is `-1`.
     * Other options will be passed to the `Html::div` method for creating the
     * outer modal `<div>`.
     *
     * @param array|string $title   The modal title or an array of options.
     * @param array        $options Array of options. See above.
     *
     * @return An HTML string containing opening elements for a modal.
     */
    public function create($title = null, $options = []) {

        if (is_array($title)) {
            $options = $title ;
        }

        $this->_currentId = null;
        $this->_current   = null;

        $options += [
            'id' => null,
            'close' => true,
            'body' => true,
            'tabindex' => -1,
            'role' => 'dialog',
            'aria-hidden' => 'true',
            'size' => false
        ];

        $close = $options['close'];
        $body  = $options['body'];
        unset ($options['close'], $options['body']) ;


        if ($options['id']) {
            $this->_currentId = $options['id'] ;
            $options['aria-labelledby'] = $this->_currentId.'Label' ;
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
        unset($options['size']);

        $options = $this->addClass($options, 'modal fade');
        $res = $this->Html->tag('div', null, $options)
             .$this->Html->div('modal-dialog'.$size).$this->Html->div('modal-content');
        if (is_string($title) && $title) {
            $res .= $this->_createHeader($title, ['close' => $close]) ;
            if ($body) {
                $res .= $this->_createBody();
            }
        }
        return $res ;
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
     * @param array|null $buttons Array of buttons for the `footer()` method or `null`.
     * @param array      $options Array of options for the `footer()` method.
     *
     * @return string An HTML string containing closing tags for the modal.
     */
    public function end($buttons = NULL, $options = []) {
        $res = $this->_cleanCurrent();
        if ($buttons !== null) {
            $res .= $this->footer($buttons, $options) ;
        }
        $res .= '</div></div></div>' ;
        return $res ;
    }

    /**
     * Cleans the current modal part and return necessary HTML closing elements.
     *
     * @return string An HTML string containing closing elements.
     */
    protected function _cleanCurrent() {
        if ($this->_current) {
            $this->_current = null ;
            return '</div>';
        }
        return '' ;
    }

    /**
     * Cleans the current modal part, create a new ones with the given content, and
     * update the internal `_current` variable if necessary.
     *
     * @param string $part    The name of the part (`'header'`, `'body'`, `'footer'`).
     * @param string $content The content of the part or `null`.
     * @param array  $options Array of options for the `Html::tag` method.
     *
     * @return string
     */
    protected function _part($part, $content = null, $options = []) {
        $out = $this->_cleanCurrent().$this->Html->tag('div', $content, $options);
        if (!$content)
            $this->_current = $part;
        return $out;
    }

    /**
     * Create or open a modal header.
     *
     * ### Options
     *
     * - `close` Set to `false` to not add a close button to the modal. Default is `true`
     * - Other attributes for the modal header `<div>`.
     *
     * @param string|null $text The modal header content, or null to only open the header.
     * @param array       $options Array of options. See above.
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

        $close = $options['close'];
        unset($options['close']) ;

        $options = $this->addClass($options, 'modal-header');

        $out = null;
        if ($title) {
            $out = '';
            if ($close) {
                $out .= $this->Html->tag('button', '&times;', [
                    'type' => 'button',
                    'class' => 'close',
                    'data-dismiss' => 'modal',
                    'aria-hidden' => 'true'
                ]);
            }
            $out .= $this->Html->tag('h4', $title, [
                'class' => 'modal-title',
                'id' => $this->_currentId ? $this->_currentId.'Label' : false
            ]);
        }

        return $this->_part('header', $out, $options);
    }
    /**
     * Create or open a modal body.
     *
     * @param string|null $text The modal body content, or null to only open the body.
     * @param array       $options Array of options for the body `<div>`.
     *
     * @return string A formated opening tag for the modal body or the complete modal
     * body.
     *
     * @see `BootstrapModalHelper::body`
     */
    protected function _createBody($text = null, $options = []) {
        $options = $this->addClass($options, 'modal-body');
        return $this->_part('body', $text, $options);
    }

    /**
     * Create or open a modal footer.
     *
     * If `$content` is `null` and the `'close'` option (see below) is `true`, a close
     * button is created inside the footer.
     *
     * ### Options
     *
     * - `close` Set to `true` to add a close button to the footer if `$content` is
     * empty. Default is `true`.
     * Other attributes for the footer div.
     *
     * @param string|null $content The modal footer content, or null to only open the footer.
     * @param array       $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal footer or the complete modal
     * footer.
     */
    protected function _createFooter($content = null, $options = []) {
        $options += [
            'close' => true
        ];
        $close = $options['close'];
        unset($options['close']);

        if (!$content  && $close) {
            $content .= '<button type="button" class="btn btn-default" data-dismiss="modal">'
                     .__('Close')
                     .'</button>' ;
        }

        $options = $this->addClass($options, 'modal-footer');
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
     * - `close` Set to `false` to not add a close button to the modal. Default is `true`
     * - Other attributes for the modal header `<div>`.
     *
     * @param string|array $text The modal header content, or an array of options.
     * @param array        $options Array of options. See above.
     *
     * @return string A formated opening tag for the modal header or the complete modal
     * header.
     */
    public function header($info = null, $options = []) {
        if (is_array($info)) {
            $options = $info;
            $info = null;
        }
        return $this->_createHeader($info, $options) ;
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
     * @param array|string $info The body content, or `null`, or an array of options.
     * `$options`.
     * @param array $options Array of options for the modal body `<div>`.
     *
     * @return string
     */
    public function body($info = null, $options = []) {
        if (is_array($info)) {
            $options = $info;
            $info = null;
        }
        return $this->_createBody($info, $options) ;
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
     * @param string|array $buttons The footer content, or `null`, or an array of options.
     * @param array        $options Array of options for the modal footer `<div>`.
     *
     * @return string A formated opening tag for the modal footer or the complete modal
     * footer.
     */
    public function footer($buttons = null, $options = []) {
        if (is_array($buttons)) {
            if (!empty($buttons) && $this->_isAssociativeArray($buttons)) {
                $options = $buttons;
                $buttons = null;
            }
            else {
                $buttons = implode('', $buttons);
            }
        }
        return $this->_createFooter($buttons, $options) ;
    }

}

?>
