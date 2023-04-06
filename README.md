# CakePHP 3.x Helpers for Bootstrap

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Packagist](https://img.shields.io/packagist/dt/holt59/cakephp3-bootstrap-helpers.svg?style=flat-square)](https://packagist.org/packages/holt59/cakephp3-bootstrap-helpers)
<!-- [![Travis](https://img.shields.io/travis/Holt59/cakephp3-bootstrap-helpers/master.svg?style=flat-square)](https://travis-ci.com/Holt59/cakephp3-bootstrap-helpers) -->

CakePHP 3.x Helpers to generate HTML with @Twitter Boostrap style: `Breadcrumbs`,
`Flash`, `Form`, `Html`, `Modal`, `Navbar`, `Panel` and `Paginator` helpers available!

## How to... ?

### Installation

If you want the latest **Bootstrap 3** version of the plugin:

- Add the plugin to your `composer.json` (see below if you want to use another branch / version):

```bash
composer require holt59/cakephp3-bootstrap-helpers:dev-master
# or the following if you want to use the Bootstrap 4 version (alpha)
composer require holt59/cakephp3-bootstrap-helpers:dev-4.0.3
```

- Load the plugin in your `config/bootstrap.php`:

```php
Plugin::load('Bootstrap');
```

- [Load the helpers](https://book.cakephp.org/3.0/en/views/helpers.html#configuring-helpers)
  you want in your `View/AppView.php`:

```php
$this->loadHelper('Html', [
    'className' => 'Bootstrap.Html',
    // Other configuration options...
]);
```

The full plugin documentation is available at
[https://cakephp-bootstrap.github.io/cakephp3-bootstrap-helpers/](https://cakephp-bootstrap.github.io/cakephp3-bootstrap-helpers/).

### Table of version and requirements

| Version | Bootstrap version | CakePHP version | Information |
|---------|-------------------|-----------------|-------------|
| [master](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/tree/master) | 3 | >= 3.7.0 | Current active V3 branch. |
| [4.0.3](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/tree/v4.0.3) | 4 | >= 3.7.0 | Current active V4 branch. |
| [4.0.2](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/tree/v4.0.2) | 4 | >= 3.7.0 | Latest V4 release. |
| [3.1.4](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/tree/v3.1.2) | 3 | >= 3.7.0 | Open issue(s) if necessary. |
| <= [3.1.2](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/tree/v3.1.1) | 3 | < 3.4.0 | Deprecated. |

## Contributing

Do not hesitate to [**post a github issue**](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/issues/new) or [**submit a pull request**](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/pulls) if you find a bug or want a new feature.

### Who is using it?

Non-exhaustive list of projects using these helpers, if you want to be in this list,
post a comment on [this issue](https://github.com/cakephp-bootstrap/cakephp3-bootstrap-helpers/issues/32).

- [**CakeAdmin**] (https://github.com/cakemanager/cakeadmin-lightstrap), LightStrap Theme for CakeAdmin

## Copyright and license

The MIT License (MIT)

Copyright (c) 2013-2023, Mikaël Capelle.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

See [LICENSE](LICENSE).
