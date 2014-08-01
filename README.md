cakephp3-bootstrap3-helpers
===========================

CakePHP 3.0 Helpers to generate HTML with @Twitter Boostrap 3

This is the new repository for my CakePHP Bootstrap 3 Helpers (CakePHP 2.0 repository here: https://github.com/Holt59/cakephp-bootstrap3-helpers).

Working helpers: Html, Form, Modal, Paginator

<i>The <code>BootstrapNavbarHelper</code> is currently not working with CakePHP 3.</i>

How to use?
===========

Just add Helper files into your View/Helpers directory and load the helpers in you controller:
<pre><code>public $helpers = [
    'Html' => [
        'className' => 'BootstrapHtml'
    ],
    'Form' => [
        'className' => 'BootstrapForm'
    ],
    'Paginator' => [
        'className' => 'BootstrapPaginator'
    ],
    'Modal' => [
        'className' => 'BootstrapModal'
    ]
];</code></pre>

I tried to keep CakePHP helpers style. You can find the documentation directly in the Helpers files.

Html
====

Overload of <code>getCrumbList</code> and new functions availables:

<pre><code>$this->Html->label('My Label', 'primary') ;

$this->Html->badge('1') ;
$this->Html->badge('2') ;

$this->Html->alert('This is a warning alert!') ;
$this->Html->alert('This is a success alert!', 'success');</code></pre>

See the source for full documentation.

Form
====

Standard CakePHP code working with this helper!

<pre><code>$this->Form->create($myModel);
$this->Form->input('field1') ;
$this->Form->input('field2') ;
$this->Form->input('field3') ;
$this->Form->end('Submit') ;</code></pre>

Added possibility to create inline and horizontal forms: <code>$this->Form->create($myModal, ['horizontal' => true, 'inline' => false]);</code>

With <code>horizontal</code>, it is possible to specify the width of each columns:
<pre><code>$this->Form->create($myModal, [
    'horizontal' => true,
    'cols' => [ // Total is 12
        'label' => 2,
        'input' => 6,
        'error' => 4
    ]
]);</code></pre>

You can also set column widths for different screens:
<pre><code>$this->Form->create($myModal, [
    'horizontal' => true,
    'cols' => [ 
        'sm' => [
            'label' => 4,
            'input' => 4,
            'error' => 4
        ],
        'md' => [
            'label' => 2,
            'input' => 6,
            'error' => 4
        ]
    ]
]);</code></pre>

New functions available to create buttons group, toolbar and dropdown:
<pre><code>$this->Form->buttonGroup([$this->Form->button('1'), $this->Form->button('2')]) ;
$this->Form->buttonToolbar([
    $this->Form->buttonGroup([$this->Form->button('1'), $this->Form->button('2')]),
    $this->Form->buttonGroup([$this->Form->button('3'), $this->Form->button('4')])
]) ;
$this->Form->dropdownButton('My Dropdown', [
    $this->Html->link('Link 1'),
    $this->Html->link('Link 2'),
    'divider',
    $this->Html->link('Link 3')
]);</code></pre>

New options available when creating input to prepend / append button or text to input:
<pre><code>$this->Form->input('mail', [
    'prepend' => '@', 
    'append' => $this->Form->button('Send')
]) ;
$this->Form->input('mail', [
    'append' => [
        $this->Form->button('Button'),
        $this->Form->dropdownButton('Dropdown', [
            'A', 'B', 'divider', 'C'
        ]);
    ]
]) ;</code></pre>

It is possible to specify default button type and column width (for horizontal forms) when creating the helper:
<pre><code>// In your Controller
public $helpers = [
    'Form' => [
        'className' => 'BootstrapForm',
        'buttons' => [
            'type' => 'primary'
        ],
        'columns' => [
            'sm' => [
                'label' => 4,
                'input' => 4,
                'error' => 4
            ],
            'md' => [
                'label' => 2,
                'input' => 6,
                'error' => 4
            ]
        ]
    ]
];</code></pre>

Copyright and license
=====================

Copyright 2013 Mikaël Capelle.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
