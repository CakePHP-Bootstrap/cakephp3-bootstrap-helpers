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
            'ellipsis' => '<li class="ellipsis">...</li>',
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
