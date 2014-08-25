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

namespace Bootstrap3\View\Helper;

use Cake\View\Helper\PaginatorHelper;

class BootstrapPaginatorHelper extends PaginatorHelper {


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
          
        $options['before'] = '<ul class="'.$class.'">' ;
        $options['after'] = '</ul>' ;

        if (isset($options['prev'])) {
            $options['before'] .= $this->prev($options['prev']) ;
        }

        if (isset($options['next'])) {
            $options['after'] = $this->next($options['next']).$options['after'] ;
        }
                
        return parent::numbers ($options) ;
    }


}

?>
