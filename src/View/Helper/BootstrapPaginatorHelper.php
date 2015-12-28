<?php

/**
* Bootstrap Paginator Helper
*
*
* PHP 5
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*      http://www.apache.org/licenses/LICENSE-2.0
*
*
* @copyright Copyright (c) Mikaël Capelle (http://mikael-capelle.fr)
* @link http://mikael-capelle.fr
* @package app.View.Helper
* @since Apache v2
* @license http://www.apache.org/licenses/LICENSE-2.0
*/

namespace Bootstrap\View\Helper;

use Cake\View\Helper\PaginatorHelper;

class BootstrapPaginatorHelper extends PaginatorHelper {

    use BootstrapTrait ;

    public function __construct ($view, $config = []) {
        $this->templates([
            'nextActive' => '<li><a href="{{url}}">{{text}}</a></li>',
            'nextDisabled' => '<li class="disabled"><a>{{text}}</a></li>',
            'prevActive' => '<li><a href="{{url}}">{{text}}</a></li>',
            'prevDisabled' => '<li class="disabled"><a>{{text}}</a></li>',
            'first' => '<li><a href="{{url}}">{{text}}</a></li>',
            'last' => '<li><a href="{{url}}">{{text}}</a></li>',
            'number' => '<li><a href="{{url}}">{{text}}</a></li>',
            'current ' => '<li class="active"><a href="{{url}}">{{text}}</a></li>'
        ]);
        
        parent::__construct($view, $config);
    }
    
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
    public function numbers (array $options = array()) {       
        
        $class = 'pagination' ;

        if (isset($options['class'])) {
            $class .= ' '.$options['class'] ;
            unset($options['class']) ;
        }
        
        if (isset($options['size'])) {
            switch ($options['size']) {
            case 'small':
                $class .= ' pagination-sm' ;
                break ;
            case 'large':
                $class .= ' pagination-lg' ;
                break ;
            }
            unset($options['size']) ;
        }
          
        if (!isset($options['before'])) {
            $options['before'] = '<ul class="'.$class.'">' ;
        }
        
        if (!isset($options['after'])) {
            $options['after'] = '</ul>' ;
        }

        if (isset($options['prev'])) {
            $title = $options['prev'] ;
            $opts  = [] ;
            if (is_array($title)) {
                $title = $title['title'] ;
                unset ($options['prev']['title']) ;
                $opts  = $options['prev'] ;
            }
            $options['before'] .= $this->prev($title, $opts) ;
        }

        if (isset($options['next'])) {
            $title = $options['next'] ;
            $opts  = [] ;
            if (is_array($title)) {
                $title = $title['title'];
                unset ($options['next']['title']);
                $opts  = $options['next'];
            }
            $options['after'] = $this->next($title, $opts).$options['after'] ;
        }
                
        return parent::numbers ($options) ;
    }

    public function prev ($title = '<< Previous', array $options = []) {
        return $this->_easyIcon ('parent::prev', $title, $options);
    }

    public function next ($title = 'Next >>', array $options = []) {
        return $this->_easyIcon ('parent::next', $title, $options);
    }


}

?>
