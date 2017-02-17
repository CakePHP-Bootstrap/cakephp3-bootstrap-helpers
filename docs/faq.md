### Frequently Asked Questions

---

#### My site does not look like Bootstrap, why?

If you loaded the helpers correctly, check that you correctly include the stylesheets and
scripts from Bootstrap, since the helpers do not include them automatically.

---

#### Where have the `faIcon` and `glIcon` methods gone?

The `faIcon` and `glIcon` have been dropped in 3.1.0, alongside with the `useFontAwesone` option. If you
want to use a custom set of icons, you can customize the `icon` template:

```php
$this->Html->templates([
    'icon' => '<i class="fa fa-{{type}}"></i>'
]);
```

---

#### I cannot use easy icon, why?

The easy icon features relies on the `Bootstrap.Html` helper, so it only works if the `Html` helper
associated to your view is `Bootstrap.Html`:

```php
public $helpers = [
    'Html' => [
        'className' => 'Bootstrap.Html'
    ]
];
```
