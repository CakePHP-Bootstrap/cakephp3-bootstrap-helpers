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


    protected $_defaultConfig = [
        'options' => [],
        'templates' => [
            'ellipsis' => '<li class="page-item disabled"><a class="page-link">...</a></li>',
            'nextActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'nextDisabled' => '<li class="page-item disabled"><a class="page-link">{{text}}</a></li>',
            'prevActive' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'prevDisabled' => '<li class="page-item disabled"><a class="page-link">{{text}}</a></li>',
            'first' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'last' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'number' => '<li class="page-item"><a class="page-link" href="{{url}}">{{text}}</a></li>',
            'current' => '<li class="page-item active"><a class="page-link" href="{{url}}">{{text}}</a></li>'
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
    public function numbers(array $options = array())
    {

        $class = 'pagination';
        if (isset($options['class'])) {
            $class .= ' ' . $options['class'];
            unset($options['class']);
        }

        if (isset($options['size'])) {
            switch ($options['size']) {
                case 'small':
                    $class .= ' pagination-sm';
                    break;
                case 'large':
                    $class .= ' pagination-lg';
                    break;
            }
            unset($options['size']);
        }

        $numbers = parent::numbers($options);

        return '<ul class="' . $class . '">' . $numbers . '</ul>';
    }


}

?>
