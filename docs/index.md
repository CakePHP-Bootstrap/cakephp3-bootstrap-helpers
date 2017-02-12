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

Once the plugin is loaded, you just need to enable them into your `AppController` like this:

```php
public $helpers = [
    'Html' => [
        'className' => 'Bootstrap.BootstrapHtml'
    ],
    'Form' => [
        'className' => 'Bootstrap.BootstrapForm'
    ],
    'Paginator' => [
        'className' => 'Bootstrap.BootstrapPaginator'
    ],
    'Modal' => [
        'className' => 'Bootstrap.BootstrapModal'
    ]
];
```

See [CakePHP documentation](http://book.cakephp.org/3.0/en/views/helpers.html) for more information on how to enable helpers in your 
controllers (especially if you want to use the default CakePHP helpers together with these helpers).

Tip! Do not forget to add bootstrap CSS (and javascript) files to your pages for the helpers to work.