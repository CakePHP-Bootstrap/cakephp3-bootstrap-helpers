### Icons

-- TABS: icons-basic

-- TAB: PHP

```php
echo $this->Html->icon('pencil');
```

-- TAB: Markup
```markup
<i class="glyphicon glyphicon-pencil"></i>
```

-- TABS

### Icons - Easy icons

Sometimes, you may want to use the `BootstrapHtmlHelper::icon` method in combination with other helpers such as `BootstrapPaginatorHelper`
or `BootstrapFormHelper` to insert icons in buttons or other elements. To ease the process, some methods provide a shortcut to insert icons:

```php
// The following...
echo $this->Form->button($this->Html->icon('pencil'), ['escape' => false]);
// ...can be easily rewritten as:
echo $this->Form->button('i:pencil');
```

This process is called *easy icon*, the easy icon format is `i:icon-name` where `icon-name` is the name of the icon (the name is not checked by the
helpers, so you can use custom icon names if you want). You can use easy icon in most methods of the Bootstrap helpers, if you find a method which does
not work, do not hesitate to open an issue so that I can add support for it!

**Tip:** To disable easy icon, simply do `$this->MyHelper->easyIcon = false;` (replace `MyHelper` by anything relevant).

### Icons - Custom set of icons

In Bootstrap 3, the default set of icons is [Glyphicon](http://getbootstrap.com/components/#glyphicons), but the
helpers can be used to generate icons from any sets by customizing the `icon` template:

-- TABS: icons-favsgl

-- TAB: PHP

```php
echo $this->Html->icon('home');
$this->Html->templates([
    'icon' => '<i class="fa fa-{{type}}{{attrs.class}}"{{attrs}}></i>'
]);
echo $this->Html->icon('home');
```

-- TAB: Markup

```markup
<i class="glyphicon glyphicon-pencil"></i>
<i class="fa fa-pencil"></i>
```

-- TABS

**Note:** At the moment, if you want to use another set of icons with easy icon, the only way is to customize the templates of the `Html` property of
each of your helpers, e.g.:

```php
$this->Form->Html->templates([
    'icon' => '<i class="fa fa-{{type}}{{attrs.class}}"{{attrs}}></i>'
]);
```
