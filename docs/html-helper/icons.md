### Icons

-- TABS: icons-basic

-- TAB: PHP

```php
<?php
    echo $this->Html->icon('pencil');
?>
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
<?php
    // The following... 
    echo $this->Form->button($this->Html->icon('pencil'), ['escape' => false]);
    // ...can be easily rewritten as:
    echo $this->Form->button('i:pencil');
?>
```

This process is called *easy icon*, the easy icon format is `i:icon-name` where icon-name is the name of the icon (the name is not checked by the
helpers, so you can use custom icon names if you want).

**Important:** The above code may throw errors if the HTML helper defined for the view is not `BootstrapHtmlHelper`. The set of icons 
used (Font Awesome or Glyphicon) is defined by the HTML helper settings.

**Tip:** To disable easy icon, simply do `$this->Form->easyIcon = false;` (replace `Form` by anything relevant).

Places where you can use easy icon (if not specified, the target is `$title`):

```php
// BootstrapPaginatorHelper
BootstrapPaginatorHelper::prev($title, array $options = []);
BootstrapPaginatorHelper::next($title, array $options = []);
BootstrapPaginatorHelper::numbers(array $options = []); // For `prev` and `next` options.

// BootstrapFormatHelper
BootstrapFormHelper::button($title, array $options = []);
BootstrapFormHelper::input($fieldName, array $options = []); // For `prepend` and `append` options.
BootstrapFormHelper::prepend($input, $prepend); // For $prepend.
BootstrapFormHelper::append($input, $append); // For $append.
```

### Icons - Font Awesome and Glyphicon

In Bootstrap 3, the default set of icons is [Glyphicon](http://getbootstrap.com/components/#glyphicons), but the 
helpers can be used to generate [Font Awesome](http://fontawesome.io/) icons.

To change the default set of icons, you can set the `useFontAwesome` to `true` in the helper configuration:

```php
public $helpers = [
    'Html' => [
        'className' => 'Bootstrap.BootstrapHtml',
        'useFontAwesome' => true // Add this line to set font awesome as default
    ]
];
```

-- TABS: icons-favsgl

-- TAB: PHP

```php
<?php
    echo $this->Html->icon('pencil');
    echo $this->Html->glIcon('pencil'); // Glyphicon icons are still available with BootstrapHtmlHelper::glIcon
    echo $this->Html->faIcon('pencil'); // FontAwesome are always available using BootstrapHtmlHelper::faIcon
?>
```

-- TAB: Markup

```markup
<i class="fa fa-pencil"></i>
<i class="fa fa-pencil"></i>
<i class="glyphicon glyphicon-pencil"></i>
```


-- TABS
