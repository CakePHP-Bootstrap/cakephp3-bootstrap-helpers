### Bootstrap buttons

### Button groups

You can create [button groups](http://getbootstrap.com/components/#btn-groups) using the `Bootstrap.FormHelper`:

-- TABS: button-groups-1

-- TAB: PHP

```php
echo $this->Form->buttonGroup([$this->Form->button('1'), $this->Form->button('2')]) ;
```

-- TAB: Markup

```markup
<div class="btn-group">
    <button class="btn btn-default" type="submit">1</button>
    <button class="btn btn-default" type="submit">2</button>
</div>
```

-- Output

<div class="btn-group">
    <button class="btn btn-default" type="submit">1</button>
    <button class="btn btn-default" type="submit">2</button>
</div>

-- TABS

### Button toolbars

You can create [button toolbars](http://getbootstrap.com/components/#btn-groups-toolbar) using the `Bootstrap.FormHelper` by
combining multiple button groups:

-- TABS: button-toolbars-1

-- TAB: PHP

```php
echo $this->Form->buttonToolbar([
    $this->Form->buttonGroup([$this->Form->button('1'), $this->Form->button('2')]),
    $this->Form->buttonGroup([$this->Form->button('3'), $this->Form->button('4')])
]) ;
```

-- TAB: Markup

```markup
<div class="btn-toolbar">
    <div class="btn-group">
        <button class="btn btn-default" type="submit">1</button>
        <button class="btn btn-default" type="submit">2</button>
    </div>
    <div class="btn-group">
        <button class="btn btn-default" type="submit">3</button>
        <button class="btn btn-default" type="submit">4</button>
    </div>
</div>
```

-- Output

<div class="btn-toolbar">
    <div class="btn-group">
        <button class="btn btn-default" type="submit">1</button>
        <button class="btn btn-default" type="submit">2</button>
    </div>
    <div class="btn-group">
        <button class="btn btn-default" type="submit">3</button>
        <button class="btn btn-default" type="submit">4</button>
    </div>
</div>

-- TABS

### Dropdown buttons

You can create [dropdown buttons](http://getbootstrap.com/components/#btn-dropdowns) using the `Bootstrap.FormHelper`:

-- TABS: button-dropdowns-1

-- TAB: PHP

```php
echo $this->Form->dropdownButton('My Dropdown', [
    $this->Html->link('Link 1', '#'),
    $this->Html->link('Link 2', '#'),
    'divider',
    $this->Html->link('Link 3', '#')
]);
```

-- TAB: Markup

```markup
<div class="btn-group">
    <button data-toggle="dropdown" class="dropdown-toggle btn btn-default">My Dropdown <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li><a href="#">Link 1</a></li>
        <li><a href="#">Link 2</a></li>
        <li class="divider"></li>
        <li><a href="#">Link 3</a></li>
    </ul>
</div>
```

-- Output

<div class="btn-group">
    <button data-toggle="dropdown" class="dropdown-toggle btn btn-default">My Dropdown <span class="caret"></span></button>
    <ul class="dropdown-menu">
        <li><a href="#">Link 1</a></li>
        <li><a href="#">Link 2</a></li>
        <li class="divider"></li>
        <li><a href="#">Link 3</a></li>
    </ul>
</div>

-- TABS
