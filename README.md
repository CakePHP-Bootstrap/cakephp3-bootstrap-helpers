CakePHP 3.x Helpers for Bootstrap
=================================

CakePHP 3.0 Helpers to generate HTML with @Twitter Boostrap style: `Html`, `Form`, `Modal` and `Paginator` helpers available!

**Important:** From now, this repository will not target a specific bootstrap version, so you may encounter errors if you are requiring `cakephp3-bootstrap3-helpers` instead of `cakephp3-bootstrap-helpers` or loading `Bootstrap3` plugin instead of `Bootstrap`. Do not hesitate to open a new issue if you have trouble getting the plugin working.

**Important:** The current version of bootstrap is v3.3.5, a new `bootstrap4-alpha` branch has been created with the aim of supporting Bootstrap 4. When the final version of Bootstrap 4 will be released, the `bootstrap4-alpha` branch will become the `master` branch of this plugin. See bellow if you want to always target a specific version of Bootstrap.

How to... ?
===========

**Installation**

If you want the latest version of the plugin (currently **Boostrap 3**):
```
composer require holt59/cakephp3-bootstrap-helpers:dev-master
```

If you want the latest **Bootstrap 3** version of the plugin:
```
composer require holt59/cakephp3-bootstrap-helpers:~3.0
```

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

Copyright 2013 Mikaël Capelle.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
