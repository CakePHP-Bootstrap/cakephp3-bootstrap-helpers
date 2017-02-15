### Create a Bootstrap panel

The `Bootstrap.PanelHelper` works in a similar way to the standard modal helper, you first need to load the helper:

```php
public $helpers = [
    'Panel' => [
        'className' => 'Bootstrap.PanelHelper'
    ]
];
```

Below is a basic example:

-- TABS: panel-basics

-- TAB: php

```php
echo $this->Panel->create('My Panel Heading');
?>
<p>Here I can write my panel content... </p>
<?php
echo $this->Panel->end();
```

-- TAB: Markup

```markup
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">My Panel Heading</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>

```

-- TAB: Output

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">My Panel Heading</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>

-- TABS

### Creating fancy panels

You can customize your panel easily by specifying the `type` option and using [easy icons](http://localhost:8000/html-helper/icons/) in
your headings:

-- TABS: panel-fancy

-- TAB: php

```php
echo $this->Panel->create('i:home Home');
echo '<p>Here I can write my panel content... </p>';
echo $this->Panel->end();
echo $this->Panel->create('i:cloud My Cloud', ['type' => 'primary']);
echo '<p>Here I can write my panel content... </p>';
echo $this->Panel->end();
echo $this->Panel->create('i:book My Books', ['type' => 'success']);
echo '<p>Here I can write my panel content... </p>';
echo $this->Panel->end();
echo $this->Panel->create('i:user My Profile', ['type' => 'danger']);
echo '<p>Here I can write my panel content... </p>';
echo $this->Panel->end();
```

-- TAB: Markup

```markup
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-home"></i> Home</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-cloud"></i> My Cloud</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-book"></i> My Books</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-danger">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-user"></i> My Profile</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
```

-- TAB: Output

<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-home"></i> Home</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-cloud"></i> My Cloud</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-success">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-book"></i> My Books</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-danger">
    <div class="panel-heading">
        <h4 class="panel-title"><i aria-hidden="true" class="glyphicon glyphicon-user"></i> My Profile</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>

-- TABS

### Playing with the panel parts

A panel is made of three part: a header, a body and a footer. If you specify a title to the `create()` method, a header is
automatically created and a body opened (except if you specify the `'body' => false` option). The `Bootstrap.PanelHelper` will
automatically close any part when you create or open a new one, or when you end the panel.

-- TABS: panel-basics-2

-- TAB: php

```php
echo $this->Panel->create(); // No heading...
echo $this->Panel->body(); // ...so you need to open the next part manually.
echo '<p>Here I can write my panel content... </p>';
echo $this->Panel->end();

echo $this->Panel->create(); // No heading...
echo $this->Panel->header(); // ...but you can still have one!
echo '<h4>My Title</h4>';
echo $this->Panel->body(); // The previous part is automatically close!
echo '<p>Here I can write my panel content... </p>';
echo $this->Panel->footer(); // And you can add a footer!
echo '<p>Some footer content... </p>';
echo $this->Panel->end();
```

-- TAB: Markup

```markup
<div class="panel panel-default">
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4>My Title</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
    <div class="panel-footer">
        <p>Some footer content... </p>
    </div>
</div>
```

-- TAB: Output

<div class="panel panel-default">
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h4>My Title</h4>
    </div>
    <div class="panel-body">
        <p>Here I can write my panel content... </p>
    </div>
    <div class="panel-footer">
        <p>Some footer content... </p>
    </div>
</div>

-- TABS
