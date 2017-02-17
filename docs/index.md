### Dependencies

This repository contains a set of **helpers** that will help you combine **CakePHP** and **Bootstrap**.
These helpers do not require any dependencies except the two obvious ones:

- [CakePHP 3.x](http://cakephp.org/) The helpers have been developped since CakePHP 3 beta, and will be kept up to date
with the current CakePHP developpment.
- [Bootstrap](http://getbootstrap.com/) The helpers currently work with Bootstrap 3, but will be updated soon to
work with Bootstrap 4.

### Installation

-- TABS: Installation

-- TAB: Composer

Since v3, CakePHP uses `composer`, the easiest way to set up the Bootstrap helpers is by either running:

```bash
composer require holt59/cakephp3-bootstrap-helpers:dev-master
```

or adding the following to your `composer.json` and run `composer update`:

```javascript
"require": {
    "holt59/cakephp3-bootstrap-helpers": "dev-master"
}
```

Do not forget to load the plugin by adding the following line into your /config/bootstrap.php file:

```php
Plugin::load('Bootstrap');
```

-- TAB: Manual

If you do not use `composer`, simply clone the repository into a `plugins/Bootstrap` folder by running:

```bash
git clone https://github.com/Holt59/cakephp3-bootstrap-helpers.git plugins/Bootstrap
```

And then load the plugin with autoload set to true in your `config/bootstrap.php` file:

```php
Plugin::load('Bootstrap' ['autoload' => true]);
```

-- TABS

### Using the helpers

Once the plugin is loaded, you need to enable them:

```php
// In your AppController class for instance:
public $helpers = [
    'Form' => [
        'className' => 'Bootstrap.Form'
    ],
    'Html' => [
        'className' => 'Bootstrap.Html'
    ],
    'Modal' => [
        'className' => 'Bootstrap.Modal'
    ],
    'Navbar' => [
        'className' => 'Bootstrap.Navbar'
    ],
    'Paginator' => [
        'className' => 'Bootstrap.Paginator'
    ],
    'Panel' => [
        'className' => 'Bootstrap.Panel'
    ]
];
```

You should be careful when mixing the Bootstrap helpers with other helpers, see the [FAQ](faq.md).
Do not forget to add the bootstrap style and script files to your view (e.g. in `Layout/default.ctp`):

```php
echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
echo $this->Html->script([
    'https://code.jquery.com/jquery-1.12.4.min.js',
    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'
]);
```

See [CakePHP documentation](http://book.cakephp.org/3.0/en/views/helpers.html) for more information on how to enable helpers in your
controllers (especially if you want to use the default CakePHP helpers together with these helpers).
