CakePHP 3.x Helpers for Bootstrap
=================================

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Travis](https://img.shields.io/travis/Holt59/cakephp3-bootstrap-helpers.svg?style=flat-square)](https://travis-ci.org/Holt59/cakephp3-bootstrap-helpers)

CakePHP 3.0 Helpers to generate HTML with @Twitter Boostrap style: `Flash`, `Form`, `Html`, `Modal`, `Navbar`, `Panel` and `Paginator` helpers available!

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

Copyright (c) 2013-2016, Mikaël Capelle.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

See [LICENSE](LICENSE).
