CakePHP 3.x Helpers for Bootstrap
=================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Travis](https://img.shields.io/travis/Holt59/cakephp3-bootstrap-helpers.svg?style=flat-square)](https://travis-ci.org/Holt59/cakephp3-bootstrap-helpers)

CakePHP 3.x Helpers to generate HTML with @Twitter Boostrap style: `Breadcrumbs`, `Flash`, `Form`, `Html`, `Modal`, `Navbar`, 
`Panel` and `Paginator` helpers available!

Version 3.1 of the helpers is out!
==================================

A new major version **3.1** of the helpers is now out. This version brings major changes to the way helpers internally works by using
templates instead of the standard `tag()` and `div()` method.

**Changes**

- Most methods are now based on templates, meaning that:
    - Options like `tag`, `aria-*`, `data-*`, ..., have been dropped from various methods.
    - The `templateVars` options is now usable with most methods.
    - There might be escaping issue since the old `div()` and `tag()` methods did not escape content be default, while
the template based methods do. Feel free to open an [issue](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/new) if 
you encounter problems with escaping.
- The new `EasyIconTrait` now depends on the `Html` property, and not on the `_View->Html` property, meaning that easy icon
can be used even if the `Html` helper associated to the view is not `BootstrapHtmlHelper`.

Some minor changes that do not impact the user interface:
- The `BootstrapTrait` class has been split in two classes: `BootstrapTrait` and `EasyIconTrait`. 
- The test cases have been updated and strenghten to avoid bad modification in the code.

**Migrating to 3.1**

List of changes that need refactoring in your code:

- `BootstrapHtmlHelper`
    - The `faIcon` and `glIcon` have been dropped.
    - The `useFontAwesome` options has been dropped, the new way is to customize the `icon` template.
    - It is no longer possible to use custom `tag` to render labels, badges, alerts (still possible for `tooltip`).
- `BootstrapNavbarHelper`
    - The `autoButtonLink` options has been dropped, this was misleading for many users.

Some options such as `aria-*`, `data-*`, have been dropped from various methods since these are now included in the templates,
if you want to customize them, you should modify the template.


How to... ?
===========

**Installation**

If you want the latest **Bootstrap 3** version of the plugin:
```
composer require holt59/cakephp3-bootstrap-helpers:dev-master
```
```php
// in config/bootstrap.php
Plugin::load('Bootstrap');
```

```php
// in your AppController
public $helpers = [
    'Form' => [
        'className' => 'Bootstrap.BootstrapForm'
    ],
    /* ... */
];
```
---


If you want to test the **Bootstrap 4** version of the plugin (alpha):
```
composer require holt59/cakephp3-bootstrap-helpers:dev-v4.0.0-alpha
```

**Documentation**

The full plugin documentation is available at https://holt59.github.io/cakephp3-bootstrap-helpers/.

**Contributing**

Do not hesitate to [**post a github issue**](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/new) or [**submit a pull request**](https://github.com/Holt59/cakephp3-bootstrap-helpers/pulls) if you find a bug or want a new feature.

Who is using it?
================

Non-exhaustive list of projects using these helpers, if you want to be in this list, do not hesitate to [email me](mailto:capelle.mikael@gmail.com) or post a comment on [this issue](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/32).

 - [**CakeAdmin**] (https://github.com/cakemanager/cakeadmin-lightstrap), LightStrap Theme for CakeAdmin

Copyright and license
=====================

The MIT License (MIT)

Copyright (c) 2013-2017, Mikaël Capelle.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

See [LICENSE](LICENSE).
