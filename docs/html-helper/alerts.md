### Alerts

-- TABS: alerts

-- TAB: PHP

```php
echo $this->Html->alert('This is a warning alert!') ;
echo $this->Html->alert('This is a success alert!', 'success');
echo $this->Html->alert('This is a info alert with a specific id!', [
    'id' => 'alert-info',
    'type' => 'info'
]);
```

-- TAB: Markup

```markup
<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This is a warning alert!
</div>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This is a success alert!
</div>
<div id="alert-info" class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This is a info alert with a specific id!
</div>
```

-- TAB: Output

<div class="alert alert-warning">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This is a warning alert!
</div>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This is a success alert!
</div>
<div id="alert-info" class="alert alert-info">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    This is a info alert with a specific id!
</div>

-- TABS
