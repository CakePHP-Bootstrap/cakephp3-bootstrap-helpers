### Description of the feature

The `autoActiveLink` configuration option allows the navbar helper to deduce the active link automatically and add an `active` class to the
surrounding `<li>` element.

### Enabling the feature

The feature is enabled by default, you can enable or disable it by doing:

```php
$this->Navbar->setConfig('autoActiveLink', false);
$this->Navbar->setConfig('autoActiveLink', true);
```

When the feature is disable, you can always specify the `active` option to the `NavbarHelper::link()` method:

```php
echo $this->Navbar->link('Link', $url, ['active' => true]);
```

The `active` option will always override the automatic value, even when `autoActiveLink` is `true`:

```php
$this->Navbar->setConfig('autoActiveLink', true);
echo $this->Navbar->link('Link', $url);
// '<li class="active">...</li>
echo $this->Navbar->link('Link', $url, ['active' => false]);
// '<li>...</li>
```

### Explanation of the deduction

The `autoActiveLink` feature rely on the [`UrlComparerTrait::compareUrls()`]() method and do not compare URL strictly, it rather
check if the first URL is the root path of the second.

Below is a list of combinations to better understand the feature:

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Link URL</th>
            <th>Normalized</th>
            <th>Current URL</th>
            <th>Normalized</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td rowspan="2"><pre class="language-php">
    <code>['controller' => 'Pages',
     'action' => 'display', 'faq']</code></pre></td>
            <td rowspan="2"><code>/pages/display/faq</code></td>
            <td><code>/pages/faq</code></td>
            <td><code>/pages/display/faq</code></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td><code>/pages/credits</code></td>
            <td><code>/pages/display/credits</code></td>
            <td><i class="fa fa-remove"></i></td>
        </tr>
        <tr>
            <td rowspan="2"><pre class="language-php">
    <code>['controller' => 'Users',
     'action' => 'edit']</code></pre></td>
            <td rowspan="2"><code>/users/edit</code></td>
            <td><code>/users/edit/1</code></td>
            <td><code>/users/edit/1</code></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td><code>/users/edit</code></td>
            <td><code>/users/edit</code></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
        <td rowspan="2"><pre class="language-php">
    <code>['controller' => 'Users',
     'action' => 'index']</code></pre></td>
            <td rowspan="2"><code>/users/index</code></td>
            <td><code>/users</code></td>
            <td><code>/users/index</code></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td><code>/users/edit</code></td>
            <td><code>/users/edit</code></td>
            <td><i class="fa fa-remove"></i></td>
        </tr>
        </tbody>
</table>

The query parameters (`?`) and the anchor (`#`) are never used for comparison.

### Customizing the comparison

When calling the `link()` method, you can pass an array as the `active` options to customize the way the comparison is made. This
array should contains which part of the URL you want to include in the left part of the comparison. Below is an example that removes
the passed parameters and the action:

```php
echo $this->Navbar->link($name, $url, [
    'active' => [
        'action' => false,
        'pass' => false
    ];
]);
```

Now:

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Link URL</th>
            <th>Normalized</th>
            <th>Current URL</th>
            <th>Normalized</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td rowspan="2"><pre class="language-php">
    <code>['controller' => 'Users',
     'action' => 'index']</code></pre></td>
            <td rowspan="2"><code>/users/index</code></td>
            <td><code>/users</code></td>
            <td><code>/users/index</code></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        <tr>
            <td><code>/users/edit</code></td>
            <td><code>/users/edit</code></td>
            <td><i class="fa fa-check"></i></td>
        </tr>
        </tbody>
</table>

Notice that the `/users` URL now matches the `/users/edit` URL. The possible keys in the array are defined by the `UrlComparerTrait::_parts`
property:

```php
public $_parts = ['plugin', 'prefix', 'controller', 'action', 'pass'];;
```
