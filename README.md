CakePHP 3.x Helpers for Bootstrap 3
===================================

CakePHP 3.0 Helpers to generate HTML with @Twitter Boostrap 3: `Html`, `Form`, `Modal` and `Paginator` helpers available!

<i>This is the new repository for my CakePHP Bootstrap 3 Helpers (CakePHP 2.0 repository here: https://github.com/Holt59/cakephp-bootstrap3-helpers).</i>

Do not hesitate to...
 - **Post a github issue** if you find a bug or want a new feature.
 - **Send me a message** if you have troubles installing or using the plugin.

Installation
============

Run
`composer require holt59/cakephp3-bootstrap3-helpers:dev-master`
or add the following into your composer.json and run `composer update`.
```json
"require": {
  "holt59/cakephp3-bootstrap3-helpers": "dev-master"
}
```

Don't forget to load the plugin in your `/config/bootstrap.php` file:
```php
Plugin::load('Bootstrap3');
```

If you do not use `composer`, simply clone the repository into your `plugins/Bootstrap3` folder, and add `'autoload' => true` when loading the plugin:

```php
Plugin::load('Bootstrap3', ['autoload' => true]);
```


How to use?
===========

Just add Helper files into your View/Helpers directory and load the helpers in you controller:
```php
public $helpers = [
    'Html' => [
        'className' => 'Bootstrap3.BootstrapHtml'
    ],
    'Form' => [
        'className' => 'Bootstrap3.BootstrapForm'
    ],
    'Paginator' => [
        'className' => 'Bootstrap3.BootstrapPaginator'
    ],
    'Modal' => [
        'className' => 'Bootstrap3.BootstrapModal'
    ]
];
```

I tried to keep CakePHP helpers style. You can find the documentation directly in the Helpers files.

Html
====

Overload of <code>getCrumbList</code> and new functions availables:

```php
echo $this->Html->label('My Label', 'primary') ;

echo $this->Html->badge('1') ;
echo $this->Html->badge('2') ;

echo $this->Html->alert('This is a warning alert!') ;
echo $this->Html->alert('This is a success alert!', 'success');
```

See the source for full documentation.

Form
====

Standard CakePHP code working with this helper!

```php
echo $this->Form->create();
echo $this->Form->input('username') ;
echo $this->Form->input('password') ;
echo $this->Form->input('remember') ;
echo $this->Form->submit('Log In') ;
echo $this->Form->end() ;
```

Will output:

```html
<form method="post" accept-charset="utf-8" role="form" action="/login">
  <div style="display:none;">
    <input class="form-control" value="POST" type="hidden" name="_method" id="_method">
  </div>
  <div class="form-group text">
    <label class=" control-label" for="username">Username</label>
    <input class="form-control" id="username" type="text" name="username">
  </div>
  <div class="form-group password">
    <label class=" control-label" for="password">Password</label>
    <input class="form-control" id="password" type="password" name="password">
  </div>
  <div class="form-group">
    <div class="checkbox">
      <label>
        <input class="form-control" value="0" type="hidden" name="remember" id="remember">
        <input type="checkbox" name="remember" value="1" id="remember">
        Remember me
      </label>
    </div>
  </div>
  <div class="form-group">
    <input type="submit" class="btn btn-primary" value="Log In">
  </div>
</form>
```

Added possibility to create inline and horizontal forms: `$this->Form->create($myModal, ['horizontal' => true, 'inline' => false]);`

```php
echo $this->Form->create(null, ['horizontal' => true]);
echo $this->Form->input('username') ;
echo $this->Form->input('password') ;
echo $this->Form->input('remember') ;
echo $this->Form->submit('Log In') ;
echo $this->Form->end() ;
```

Will output:

```html
<form method="post" accept-charset="utf-8" class="form-horizontal" role="form" action="/CakePHP3/">
  <div style="display:none;">
    <input class="form-control" value="POST" type="hidden" name="_method" id="_method">
  </div>
  <div class="form-group text">
    <label class="col-md-2 control-label" for="username">Username</label>
    <div class="col-md-6">
      <input class="form-control" id="username" type="text" name="username">
    </div>
  </div>
  <div class="form-group password">
    <label class="col-md-2 control-label" for="password">Password</label>
    <div class="col-md-6">
      <input class="form-control" id="password" type="password" name="password">
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-2 col-md-6">
      <div class="checkbox">
        <label>
          <input class="form-control" value="0" type="hidden" name="remember" id="remember">
          <input type="checkbox" name="remember" value="1" id="remember">
          Remember me
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-md-offset-2 col-md-6">
      <input type="submit" class="btn btn-primary" value="Log In">
    </div>
  </div>
</form>
```

With <code>horizontal</code>, it is possible to specify the width of each columns:
```php
echo $this->Form->create($myModal, [
    'horizontal' => true,
    'cols' => [ // Total is 12
        'label' => 2,
        'input' => 6,
        'error' => 4
    ]
]);
```

You can also set column widths for different screens:
```php
echo $this->Form->create($myModal, [
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
]);
```

New functions available to create buttons group, toolbar and dropdown:
```php
echo $this->Form->buttonGroup([$this->Form->button('1'), $this->Form->button('2')]) ;
echo $this->Form->buttonToolbar([
    $this->Form->buttonGroup([$this->Form->button('1'), $this->Form->button('2')]),
    $this->Form->buttonGroup([$this->Form->button('3'), $this->Form->button('4')])
]) ;
echo $this->Form->dropdownButton('My Dropdown', [
    $this->Html->link('Link 1'),
    $this->Html->link('Link 2'),
    'divider',
    $this->Html->link('Link 3')
]);
```

New options available when creating input to prepend / append button or text to input:
```php
echo $this->Form->input('mail', [
    'prepend' => '@', 
    'append' => $this->Form->button('Send')
]) ;
echo $this->Form->input('mail', [
    'append' => [
        $this->Form->button('Button'),
        $this->Form->dropdownButton('Dropdown', [
            $this->Html->link('A', []), 
            $this->Html->link('B', []),
            'divider', 
            $this->Html->link('C', [])
        ])
    ]
]) ;
```

It is possible to specify default button type and column width (for horizontal forms) when creating the helper:
```php
// In your Controller
public $helpers = [
    'Form' => [
        'className' => 'Bootstrap3.BootstrapForm',
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
];
```

Modal
=====

Simple helper to create modal, 3 ways of using it:

**First one (simple) - You should use this one if possible!**

```php
<?php
// Start a modal with a title, default value for 'close' is true
echo $this->Modal->create("My Modal Form", ['id' => 'MyModal', 'close' => false]) ; 
?>
<p>Here I write the body of my modal !</p>
<?php
// Close the modal, output a footer with a 'close' button
echo $this->Modal->end() ;
// It is possible to specify custom buttons:
echo $this->Modal->end([
    $this->Form->button('Submit', ['bootstrap-type' => 'primary']),   
    $this->Form->button('Close', ['data-dismiss' => 'modal']) 
]);
?>
```

Output:

```html
<div id="MyModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labbeledby="MyModalLabel" class="modal fade" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header ">
        <!-- With 'close' => true, or without specifying:
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
        <h4 class="modal-title" id="MyModalLabel">My Modal Form</h4>
      </div>
      <div class="modal-body ">    
        <p>Here I write the body of my modal !</p>
      </div>
      <div class="modal-footer ">
        <button class="btn btn-primary btn-primary" type="submit">Submit</button>
        <button data-dismiss="modal" class="btn btn-primary" type="submit">Close</button>
      </div>
    </div>
  </div>
</div>
```

**Second one - No HTML, the various section are split in different methods.**
```php
<?php
echo $this->Modal->create(['id' => 'MyModal2']) ;
echo $this->Modal->header('My Title', ['close' => false]) ; 
echo $this->Modal->body('My Body', ['class' => 'my-body-class']) ;
echo $this->Modal->footer([
    $this->Form->button('Submit', ['bootstrap-type' => 'primary']),   
    $this->Form->button('Close', ['data-dismiss' => 'modal']) 
]) ;
echo $this->Modal->end() ;
?>
```

**Third one (advanced) - You start each section manually, but you can customize almost everything!**
```php
<?php
echo $this->Modal->create(['id' => 'MyModal3']) ;
echo $this->Modal->header(['class' => 'my-header-class']) ; 
?>
<h4>My Header!</h4>
<?php
echo $this->Modal->body() ;
?>
<p>My body!</p>
<?php
echo $this->Modal->footer(['close' => false]) ;
?>
<p>My footer... Oops, no buttons!</p>
<?php
echo $this->Modal->end() ;
?>
```

With the two last versions, it is possible to omit a part:
```php
<?php
echo $this->Modal->create() ;
echo $this->Modal->body() ; // No header
echo $this->Modal->footer() ; // Footer with close button (default)
echo $this->Modal->end() ;
?>
```

**Info:** You can use the `BootstrapFormHelper` to create toggle button for your modals!

```php
echo $this->Form->button('Toggle Form', ['data-toggle' => 'modal', 'data-target' => '#MyModal']) ;
```

Copyright and license
=====================

Copyright 2013 Mikaël Capelle.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
