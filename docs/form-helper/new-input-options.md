### Prepend or append content to inputs

You can prepend and append add-ons or buttons to inputs using custom options of the `Bootstrap.FormHelper::input` method.

-- TABS: form-new-options-1

-- TAB: PHP

```php
echo $this->Form->input('mail', [
    'prepend' => '@',
    'append' => $this->Form->button('Send')
]) ;
```

-- TAB: Markup

```markup
<div class="form-group text">
    <label class=" control-label"  for="mail">Mail</label>
    <div class="input-group">
        <span class="input-group-addon">@</span>
        <input type="text" name="mail" class="form-control " id="mail" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Send</button>
        </span>
    </div>
</div>
```

-- TAB: Output

<div class="form-group text">
    <label class=" control-label"  for="mail">Mail</label>
    <div class="input-group">
        <span class="input-group-addon">@</span>
        <input type="text" name="mail" class="form-control " id="mail" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Send</button>
        </span>
    </div>
</div>

-- TABS

### Add dropdown buttons

You can easily prepend or append dropdown buttons to inputs by using custom options of the `Bootstrap.FormHelper::input` method
combined with the `Bootstrap.FormHelper::dropdown` method.

-- TABS: form-new-options-2

-- TAB: PHP

```php
echo $this->Form->input('mail', [
    'append' => [
        $this->Form->button('Button'),
        $this->Form->dropdownButton('Dropdown', [
            $this->Html->link('A', '#'),
            $this->Html->link('B', '#'),
            'divider',
            $this->Html->link('C', '#')
        ])
    ]
]) ;
```

-- TAB: Markup

```markup
<div class="form-group text">
    <label class=" control-label"  for="mail">Mail</label>
    <div class="input-group">
        <input type="text" name="mail" class="form-control "  id="mail" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Button</button>
            <div class="btn-group">
                <button data-toggle="dropdown" class="dropdown-toggle btn btn-default">Dropdown <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">A</a></li>
                    <li><a href="#">B</a></li>
                    <li class="divider"></li>
                    <li><a href="#">C</a></li>
                </ul>
            </div>
        </span>
    </div>
</div>
```

-- TAB: Output

<div class="form-group text">
    <label class=" control-label"  for="mail">Mail</label>
    <div class="input-group">
        <input type="text" name="mail" class="form-control "  id="mail" />
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">Button</button>
            <div class="btn-group">
                <button data-toggle="dropdown" class="dropdown-toggle btn btn-default">Dropdown <span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="#">A</a></li>
                    <li><a href="#">B</a></li>
                    <li class="divider"></li>
                    <li><a href="#">C</a></li>
                </ul>
            </div>
        </span>
    </div>
</div>

-- TABS

### Add help message to inputs

You can add help messages to inputs by specifying the `help` option:

-- TABS: form-new-options-help

-- TAB: PHP

```php
echo $this->Form->input('mail', [
    'help' => 'Hey guy, you need some help?'
]) ;
```

-- TAB: Markup

```markup
<div class="form-group text">
    <label class=" control-label"  for="mail">Mail</label>
    <input type="text" name="mail" class="form-control " id="mail" />
    <p class="help-block">Hey guy, you need some help?</p>
</div>
```

-- TAB: Output

<div class="form-group text">
    <label class=" control-label"  for="mail">Mail</label>
    <input type="text" name="mail" class="form-control " id="mail" />
    <p class="help-block">Hey guy, you need some help?</p>
</div>

-- TABS
