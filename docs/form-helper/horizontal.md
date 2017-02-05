### Creating an horizontal

You can create Bootstrap [horizontal forms](http://getbootstrap.com/css/#forms-horizontal) quite easily:

-- TABS: forms-horizontal

-- TAB: PHP

```php
<?php
    echo $this->Form->create(null, ['horizontal' => true]);
    echo $this->Form->input('username', ['type' => 'text']) ;
    echo $this->Form->input('password', ['type' => 'password']) ;
    echo $this->Form->input('remember', ['type' => 'checkbox']) ;
    echo $this->Form->submit('Log In') ;
    echo $this->Form->end() ;
?>
```

-- TAB: Markup

```markup
<form method="post" accept-charset="utf-8" class="form-horizontal" role="form" action="#">
    <div style="display:none;">
        <input type="hidden" name="_method" class="form-control "  value="POST" />
    </div>
    <div class="form-group text">
        <label class="col-md-2 control-label"  for="username">Username</label>
        <div class="col-md-6">
            <input type="text" name="username" class="form-control "  id="username" />
        </div>
    </div>
    <div class="form-group password">
        <label class="col-md-2 control-label"  for="password">Password</label>
        <div class="col-md-6">
            <input type="password" name="password" class="form-control "  id="password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-6">
            <div class="checkbox">
                <input type="hidden" name="remember" class="form-control "  value="0" />
                <label for="remember">
                    <input type="checkbox" name="remember" value="1" id="remember">
                    Remember
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-6">
            <input type="submit" class="btn btn-default" value="Log In">
        </div>
    </div>
</form>
```

-- TAB: Output

<form method="post" accept-charset="utf-8" class="form-horizontal" role="form" action="#">
    <div style="display:none;">
        <input type="hidden" name="_method" class="form-control "  value="POST" />
    </div>
    <div class="form-group text">
        <label class="col-md-2 control-label"  for="username">Username</label>
        <div class="col-md-6">
            <input type="text" name="username" class="form-control "  id="username" />
        </div>
    </div>
    <div class="form-group password">
        <label class="col-md-2 control-label"  for="password">Password</label>
        <div class="col-md-6">
            <input type="password" name="password" class="form-control "  id="password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-6">
            <div class="checkbox">
                <input type="hidden" name="remember" class="form-control "  value="0" />
                <label for="remember">
                    <input type="checkbox" name="remember" value="1" id="remember">
                    Remember
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-md-offset-2 col-md-6">
            <input type="submit" class="btn btn-default" value="Log In">
        </div>
    </div>
</form>

-- TABS

### Specifying the width of the columns

You can specify the width of the various columns when creating an horizontal forms:

```php
echo $this->Form->create(null, [
    'horizontal' => true,
    'columns' => [ // Total is 12, default is 2 / 10 / 0
        'label' => 2,
        'input' => 10,
        'error' => 0 // 0 for 'error' means that it will be put under the input
    ]
]);
```

You can also specify different widths for different display sizes:

-- TABS: form-horizontals-sizes-1

-- TAB: PHP

```php
<?php
    echo $this->Form->create(null, [
        'horizontal' => true,
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
    ]);
    echo $this->Form->input('username', ['type' => 'text']) ;
    echo $this->Form->input('password', ['type' => 'password']) ;
    echo $this->Form->input('remember', ['type' => 'checkbox']) ;
    echo $this->Form->submit('Log In') ;
    echo $this->Form->end() ;
?>
```

-- TAB: Markup

```markup
<form method="post" accept-charset="utf-8" class="form-horizontal" role="form" action="#">
    <div style="display:none;">
        <input type="hidden" name="_method" class="form-control "  value="POST" />
    </div>
    <div class="form-group text">
        <label class="col-sm-4 col-md-2 control-label"  for="username">Username</label>
        <div class="col-sm-4 col-md-6">
            <input type="text" name="username" class="form-control "  id="username" />
        </div>
    </div>
    <div class="form-group password">
        <label class="col-sm-4 col-md-2 control-label"  for="password">Password</label>
        <div class="col-sm-4 col-md-6">
            <input type="password" name="password" class="form-control "  id="password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-md-offset-2 col-sm-4 col-md-6">
            <div class="checkbox">
                <input type="hidden" name="remember" class="form-control "  value="0" />
                <label for="remember">
                    <input type="checkbox" name="remember" value="1" id="remember">
                    Remember
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-md-offset-2 col-sm-4 col-md-6">
            <input type="submit" class="btn btn-default" value="Log In">
        </div>
    </div>
</form>
```

-- TAB: Output

<form method="post" accept-charset="utf-8" class="form-horizontal" role="form" action="#">
    <div style="display:none;">
        <input type="hidden" name="_method" class="form-control "  value="POST" />
    </div>
    <div class="form-group text">
        <label class="col-sm-4 col-md-2 control-label"  for="username">Username</label>
        <div class="col-sm-4 col-md-6">
            <input type="text" name="username" class="form-control "  id="username" />
        </div>
    </div>
    <div class="form-group password">
        <label class="col-sm-4 col-md-2 control-label"  for="password">Password</label>
        <div class="col-sm-4 col-md-6">
            <input type="password" name="password" class="form-control "  id="password" />
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-md-offset-2 col-sm-4 col-md-6">
            <div class="checkbox">
                <input type="hidden" name="remember" class="form-control "  value="0" />
                <label for="remember">
                    <input type="checkbox" name="remember" value="1" id="remember">
                    Remember
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-4 col-md-offset-2 col-sm-4 col-md-6">
            <input type="submit" class="btn btn-default" value="Log In">
        </div>
    </div>
</form>

-- TABS

You can set the default column widths when configuring the helper:

```php
public $helpers = [
    'Form' => [
        'className' => 'Bootstrap.BootstrapForm',
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

### Non horizontal input inside horizontal form

It is possible to create a non-horizontal input inside an horizontal form, for instance to avoid compatibility
issue for various plugins. To do so, simply set the `horizontal` attribute to `false` before the input, and
reset it to `true` after:

-- TABS: form-horizontals-nh

-- TAB: PHP

```php
<?php 
    echo $this->Form->create(NULL, ['horizontal' => true]) ;
    echo $this->Form->input('horizontal_1'); 
    echo $this->Form->horizontal = false;
    echo $this->Form->input('non_horizontal'); 
    echo $this->Form->horizontal = true;
    echo $this->Form->input('horizontal_2'); 
    echo $this->Form->end() ;
?>
```

-- TAB: Markup

```markup


<form accept-charset="utf-8" action="#" class="form-horizontal" method="post">
    <div style="display:none;">
        <input class="form-control" name="_method" type="hidden" value="POST">
    </div>
    <div class="form-group text">
        <label class="col-md-2 control-label" for="horizontal-1">Horizontal 1</label>
        <div class="col-md-6">
            <input class="form-control" id="horizontal-1" name="horizontal_1" type="text">
        </div>
    </div>
    <div class="form-group text">
        <label class=" control-label" for="non-horizontal">Non Horizontal</label>
        <input class="form-control" id="non-horizontal" name="non_horizontal" type="text">
    </div>
    <div class="form-group text">
        <label class="col-md-2 control-label" for="horizontal-2">Horizontal 2</label>
        <div class="col-md-6">
            <input class="form-control" id="horizontal-2" name="horizontal_2" type="text">
        </div>
    </div>
</form>
```

-- TAB: Output

<form accept-charset="utf-8" action="#" class="form-horizontal" method="post">
    <div style="display:none;">
        <input class="form-control" name="_method" type="hidden" value="POST">
    </div>
    <div class="form-group text">
        <label class="col-md-2 control-label" for="horizontal-1">Horizontal 1</label>
        <div class="col-md-6">
            <input class="form-control" id="horizontal-1" name="horizontal_1" type="text">
        </div>
    </div>
    <div class="form-group text">
        <label class=" control-label" for="non-horizontal">Non Horizontal</label>
        <input class="form-control" id="non-horizontal" name="non_horizontal" type="text">
    </div>
    <div class="form-group text">
        <label class="col-md-2 control-label" for="horizontal-2">Horizontal 2</label>
        <div class="col-md-6">
            <input class="form-control" id="horizontal-2" name="horizontal_2" type="text">
        </div>
    </div>
</form>

-- TABS