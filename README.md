CakePHP 3.x Helpers for Bootstrap
=================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Travis](https://img.shields.io/travis/Holt59/cakephp3-bootstrap-helpers/4.0.1-alpha.svg?style=flat-square)](https://travis-ci.com/Holt59/cakephp3-bootstrap-helpers)
[![Packagist](https://img.shields.io/packagist/dt/holt59/cakephp3-bootstrap-helpers.svg?style=flat-square)](https://packagist.org/packages/holt59/cakephp3-bootstrap-helpers)

CakePHP 3.x Helpers to generate HTML with @Twitter Boostrap style: `Breadcrumbs`, `Flash`, `Form`, `Html`, `Modal`, `Navbar`,
`Panel` and `Paginator` helpers available!

Work in progress
================

I am currently working on rewritting the helpers for Bootstrap 4, current work status:

- `BreadcrumbsHelper` - **alpha**, minor changes, should work properly.
- `FlashHelper` - No changes.
- `FormHelper` - **alpha**, important changes, expect errors or strange outputs.
- `HtmlHelper` - **alpha**, minor changes, should work properly.
- `ModalHelper` - **alpha**, minor changes, should work properly.
- `NavbarHelper` - **alpha,**, no huge changes, should work properly.
- `PaginatorHelper` - **alpha**, minor changes, should work properly.
- `CardHelper` (previously `PanelHelper`) - **alpha**, important changes, work in progress.

#### Breaking changes from previous versions

- `FormHelper`:
  - it is not possible to place error message in a separate column in horizontal form, the error message
  will always be put below the input.

- `NavbarHelper`:
  - the following options of the `create()` methods have been removed or have had their meaning changed:
    - `static` is now `sticky`
    - `responsive` has been dropped and replace by a `collapse` option with the following meaning: `true`
    for always collapsed, `false` for never collapsed, or a string representing indicating when the content
    should collapse (`'lg'` is the default).
    - `fluid` has been dropped.
    - `container` is a new option (`bool`, default is `false`) to indicate if the content of the navbar
    should be wrapped inside a `<div class="container"></div>`.
    - `inverse` has been dropped.
  - a new `theme` option has been added to the `create()` method to control, possible values are `'light'`,
  `'dark'`, `false` (no theme), or an array `[$theme, $bg]`.
  - when adding a `link()` to an inner menu, the `$linkOptions` parameters is not usable anymore and the
  `$options` parameters should be used directly (their are no wrapping `<li>` anymore for inner menu).

How to... ?
===========

#### Installation

If you want the latest **Bootstrap 4** version of the plugin:

- Add the plugin to your `composer.json`:

```
composer require holt59/cakephp3-bootstrap-helpers:dev-4.0.1-alpha
```

- Load the plugin in your `config/bootstrap.php`:

```php
Plugin::load('Bootstrap');
```

- [Load the helpers](https://book.cakephp.org/3.0/en/views/helpers.html#configuring-helpers) you want in your `View/AppView.php`:

```php
$this->loadHelper('Html', [
    'className' => 'Bootstrap.Html',
    // Other configuration options...
]);
```

The full plugin documentation is available at https://holt59.github.io/cakephp3-bootstrap-helpers/.

#### Table of version and requirements

| Version | Bootstrap version | CakePHP version | Information |
|---------|-------------------|-----------------|-------------|
| [master](https://github.com/Holt59/cakephp3-bootstrap-helpers/tree/master) | 3 | >= 3.5.0 | Current active branch. |
| [3.1.2](https://github.com/Holt59/cakephp3-bootstrap-helpers/tree/v3.1.2) | 3 | >= 3.4.0, < 3.5.0 | Not actively maintained (open issue(s) if necessary). |
| <= [3.1.1](https://github.com/Holt59/cakephp3-bootstrap-helpers/tree/v3.1.1) | 3 | < 3.4.0 | Deprecated. |
| [4.0.1-alpha](https://github.com/Holt59/cakephp3-bootstrap-helpers/tree/4.0.1-alpha) | 4 | >= 3.4.0 | Alpha, most helpers available. |

#### Contributing

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
