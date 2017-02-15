### Create a Bootstrap navbar

The `Bootstrap.NavbarHelper` works in a similar way to the standard form helper, you first need to load it:

```php
public $helpers = [
    'Navbar' => [
        'className' => 'Bootstrap.NavbarHelper',
        'autoActiveLink' => true // This is the default value, see below for more information.
    ]
];
```

You are now ready to create your navbar, see the basic example below.

-- TABS: navbar-basics

-- TAB: PHP

```php
echo $this->Navbar->create('Holt59', array('fixed' => 'top', 'responsive' => 'false', 'inverse' => true));
echo $this->Navbar->beginMenu();
    echo $this->Navbar->link('Link', '/', ['class' => 'active']);
    echo $this->Navbar->link('Blog', array('controller' => 'pages', 'action' => 'test'));
    echo $this->Navbar->beginMenu('Dropdown');
        echo $this->Navbar->header('Header 1');
        echo $this->Navbar->link('Action');
        echo $this->Navbar->link('Another action');
        echo $this->Navbar->link('Something else here');
        echo $this->Navbar->divider();
        echo $this->Navbar->header('Header 2');
        echo $this->Navbar->link('Another action');
    echo $this->Navbar->endMenu();
echo $this->Navbar->endMenu();
echo $this->Navbar->searchForm();
echo $this->Navbar->text('Signed in as <a href="#" class="classtest">Holt59</a>, <a href="#">Log Out</a>');
echo $this->Navbar->end();
```

-- TAB: Markup

```markup
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="/" class="navbar-brand">Holt59</a></div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link</a></li>
                <li><a href="#">Blog</a></li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">Dropdown<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">Header 1</li>
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider" role="separator"></li>
                        <li class="dropdown-header">Header 2</li>
                        <li><a href="#">Another action</a></li>
                    </ul>
                </li>
            </ul><!--
            <form method="post" accept-charset="utf-8" class="navbar-form navbar-left form-search" role="form" action="/">
                <div style="display:none;"><input type="hidden" name="_method" class="form-control"  value="POST" /></div>
                <div class="form-group text">
                    <div class="input-group"><input type="text" name="search" class="form-control"  id="search" placeholder="Search... " /><span class="input-group-btn"><button class="btn btn-default" type="submit">Search</button></span></div>
                </div>
            </form>-->
            <p class="navbar-text">Signed in as <a href="#" class="classtest navbar-link">Holt59</a>, <a href="#" class="navbar-link">Log Out</a></p>
        </div>
    </div>
</nav>
```

-- TAB: Output

<nav class="navbar navbar-inverse">
    <div>
        <div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="/" class="navbar-brand">Holt59</a></div>
        <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="#">Link</a></li>
                <li><a href="#">Blog</a></li>
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" class="dropdown-toggle">Dropdown<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">Header 1</li>
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li class="divider" role="separator"></li>
                        <li class="dropdown-header">Header 2</li>
                        <li><a href="#">Another action</a></li>
                    </ul>
                </li>
            </ul>
            <p class="navbar-text pull-right">Signed in as <a href="#" class="classtest navbar-link">Holt59</a>, <a href="#" class="navbar-link">Log Out</a></p>
        </div>
    </div>
</nav>

-- TABS

### Automatic deduction of the active links

The `autoActiveLink` configuration option allows the navbar helper to deduce the active link automatically and add an `active` class. This feature
is based on the [`UrlComparerTrait`]() class, and may not work everywhere.

```php
// Assume the current URL is /
$this->Navbar->config('autoActiveLink', true);
echo $this->Navbar->link('Link', '/');
// <li class="active">...</li>
echo $this->Navbar->link('Link', '/pages/test');
// <li>...</li>
echo $this->Navbar->link('Link', '#anchor');
// <li class="active">...</li>

// You can disable it easily (here or directly when loading the helper):
$this->Navbar->config('autoActiveLink', false);
echo $this->Navbar->link('Link', '/');
// <li>...</li>
```

If you found a bug in the `UrlComparerTrait`, do not hesitate to open an [issue](https://github.com/Holt59/cakephp3-bootstrap-helpers/issues/new).