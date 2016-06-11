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

    public $helpers = ['Html'];

    protected $_current = null;

    protected $_currentId = null;

    /**
     *
     * Create a Twitter Bootstrap like modal.
     *
     * @param array|string $title If array, works as $options, otherwize used as the modal title.
     * @param array $options Options for the main div of the modal.
     *
     * Extra options (useless if $title not specified) :
     *     - close: Add close buttons to header (default true)
     *     - no-body: Do not open the body after the create (default false)
     *     - size: Modal size (small, large or custom classes)
     **/
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
     *
     * End a modal. If $buttons is not null, the ModalHelper::footer functions is called
     * with $buttons and $options arguments.
     *
     * @param array|null $buttons
     * @param array $options
     *
     **/
    public function end ($buttons = NULL, $options = []) {
        $res = $this->_cleanCurrent();
        if ($buttons !== null) {
            $res .= $this->footer($buttons, $options) ;
        }
        $res .= '</div></div></div>' ;
        return $res ;
    }

    protected function _cleanCurrent () {
        if ($this->_current) {
            $this->_current = null ;
            return '</div>';
        }
        return '' ;
    }

    protected function _part ($part, $content = null, $options = []) {
        $out = $this->_cleanCurrent().$this->Html->tag('div', $content, $options);
        if (!$content)
            $this->_current = $part;
        return $out;
    }

    protected function _createHeader ($title = null, $options = []) {
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

    protected function _createBody ($text = null, $options = []) {
        $options = $this->addClass($options, 'modal-body');
        return $this->_part('body', $text, $options);
    }

    protected function _createFooter ($buttons = null, $options = []) {
        $options += [
            'close' => true
        ];
        $close = $options['close'];
        unset($options['close']);

        $content = '';
        if (!$buttons && $close) {
            $content .= '<button type="button" class="btn btn-default" data-dismiss="modal">'
                     .__('Close')
                     .'</button>' ;
        }
        $content .= $buttons;

        $options = $this->addClass($options, 'modal-footer');
        return $this->_part('footer', $buttons, $options);
    }

    /**
     *
     * Create / Start the header. If $info is specified as a string, create and return the
     * whole header, otherwize only open the header.
     *
     * @param array|string $info If string, use as the modal title, otherwize works as $options.
     * @param array $options Options for the header div.
     *
     * Special option (if $info is string):
     *     - close: Add the 'close' button in the header (default true).
     *
     **/
    public function header ($info = null, $options = []) {
        if (is_array($info)) {
            $options = $info;
            $info = null;
        }
        return $this->_createHeader($info, $options) ;
    }

    /**
     *
     * Create / Start the body. If $info is not null, it is used as the body content,
     * otherwize start the body div.
     *
     * @param array|string $info If string, use as the body content, otherwize works as $options.
     * @param array $options Options for the footer div.
     *
     *
     **/
    public function body ($info = null, $options = []) {
        if (is_array($info)) {
            $options = $info;
            $info = null;
        }
        return $this->_createBody($info, $options) ;
    }

    /**
     *
     * Create / Start the footer. If $buttons is specified as an associative arrays or as null,
     * start the footer, otherwize create the footer with the specified buttons.
     *
     * @param array|string $buttons If string, use as the footer content, if list, concatenate
     *        values in the list as content (use for buttons purpose), otherwize works as $options.
     * @param array $options Options for the footer div.
     *
     * Special option (if $buttons is NOT NULL but empty):
     *     - close: Add the 'close' button to the footer (default true).
     *
     **/
    public function footer ($buttons = null, $options = []) {
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
