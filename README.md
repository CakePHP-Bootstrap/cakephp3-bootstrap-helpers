cakephp3-bootstrap3-helpers
===========================

CakePHP 3.0 Helpers to generate HTML with @Twitter Boostrap 3

This is the new repository for my CakePHP Bootstrap 3 Helpers (CakePHP 2.0 repository here: https://github.com/Holt59/cakephp-bootstrap3-helpers).

Working helpers: Html, Form, Modal, Paginator

<i>The <code>BootstrapNavbarHelper</code> is currently not working with CakePHP 3.</i>

How to use?
===========

Just add Helper files into your View/Helpers directory and load the helpers in you controller:
<pre><code>public $helpers = [
    'Html' => [
        'className' => 'BootstrapHtml'
    ],
    'Form' => [
        'className' => 'BootstrapForm'
    ],
    'Paginator' => [
        'className' => 'BootstrapPaginator'
    ],
    'Modal' => [
        'className' => 'BootstrapModal'
    ]
];</code></pre>

I tried to keep CakePHP helpers style. You can find the documentation directly in the Helpers files.

Copyright and license
=====================

Copyright 2013 Mikaël Capelle.

Licensed under the Apache License, Version 2.0 (the "License"); you may not use this work except in compliance with the License. You may obtain a copy of the License in the LICENSE file, or at:

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions and limitations under the License.
