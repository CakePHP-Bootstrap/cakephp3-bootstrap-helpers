### Create a Bootstrap form

Bootstrap form are created using the standard CakePHP way of creating forms (the `Form` helper should be
correctly configured, see [Installation](../index.md#Installation):

-- TABS: forms-basics

-- TAB: PHP

```php
echo $this->Form->create();
echo $this->Form->input('username', ['type' => 'text']) ;
echo $this->Form->input('password', ['type' => 'password']) ;
echo $this->Form->input('remember', ['type' => 'checkbox']) ;
echo $this->Form->submit('Log In') ;
echo $this->Form->end() ;
```

-- TAB: Markup

```markup
<form method="post" accept-charset="utf-8" role="form" action="#">
    <div style="display:none;">
        <input type="hidden" name="_method" class="form-control "  value="POST" />
    </div>
    <div class="form-group text">
        <label class=" control-label" for="username">Username</label>
        <input type="text" name="username" class="form-control "  id="username" />
    </div>
    <div class="form-group password">
        <label class=" control-label"  for="password">Password</label>
        <input type="password" name="password" class="form-control "  id="password" />
    </div>
    <div class="form-group">
        <div class="checkbox">
            <input type="hidden" name="remember" class="form-control "  value="0" />
            <label for="remember"><input type="checkbox" name="remember" value="1" id="remember">Remember</label>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-default" value="Log In">
    </div>
</form>
```

-- TAB: Output

<form method="post" accept-charset="utf-8" role="form" action="#">
    <div style="display:none;">
        <input type="hidden" name="_method" class="form-control "  value="POST" />
    </div>
    <div class="form-group text">
        <label class=" control-label" for="username">Username</label>
        <input type="text" name="username" class="form-control "  id="username" />
    </div>
    <div class="form-group password">
        <label class=" control-label"  for="password">Password</label>
        <input type="password" name="password" class="form-control "  id="password" />
    </div>
    <div class="form-group">
        <div class="checkbox">
            <input type="hidden" name="remember" class="form-control "  value="0" />
            <label for="remember"><input type="checkbox" name="remember" value="1" id="remember">Remember</label>
        </div>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-default" value="Log In">
    </div>
</form>

-- TABS
