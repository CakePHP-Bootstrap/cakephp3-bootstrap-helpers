### getCrumbList

Overload of [Cake\View\Helper\HTMLHelper::getCrumbList](http://api.cakephp.org/3.0/class-Cake.View.Helper.HtmlHelper.html#_getCrumbList).

-- TABS: getCrumbList

-- TAB: PHP
```php
$this->Html->addCrumb('Home', '/');
$this->Html->addCrumb('Pages', ['controller' => 'pages']);
$this->Html->addCrumb('About', ['controller' => 'pages', 'action' => 'about']);
echo $this->Html->getCrumbList();
```

-- TAB: Markup
```markup
<ul class="breadcrumb">
    <li class="first"><a href="/">Home</a></li>
    <li><a href="/pages">Pages</a></li>
    <li class="last"><a href="/pages/about">About</a></li>
</ul>
```

-- TAB: Output

<ul class="breadcrumb">
    <li class="first"><a href="/">Home</a></li>
    <li><a href="/pages">Pages</a></li>
    <li class="last"><a href="/pages/about">About</a></li>
</ul>

-- TABS
