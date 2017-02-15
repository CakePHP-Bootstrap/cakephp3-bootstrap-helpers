### Custom file inputs

Boostrap (until v3) does not customize the file inputs, so you can choose to customize them with the `Bootstrap.FormHelper`. To do
so, enable the feature in the helper configuration:

```php
public $helpers = [
    'Form' => [
        'className' => 'Bootstrap.Form',
        'useCustomFileInput' => true
    ]
];
```

Your file inputs will now look much better:

-- TABS: form-file-input-1

-- TAB: PHP

```php
echo $this->Form->file('file');
```

-- TAB: Markup

```markup
<input type="file" name="file"  id="file" style="display: none;" onchange="document.getElementById('file-input').value = this.files[0].name;">
<div class="input-group">
    <div class="input-group-btn">
        <button type="button" onclick="document.getElementById('file').click();" class="btn btn-default">Choose File</button>
    </div>
    <input type="text" name="file" class="form-control "  id="file-input" readonly="readonly" onclick="document.getElementById('file').click();" />
</div>
```

-- TAB: Output

<input type="file" name="file"  id="file" style="display: none;" onchange="document.getElementById('file-input').value = this.files[0].name;">
<div class="input-group">
    <div class="input-group-btn">
        <button type="button" onclick="document.getElementById('file').click();" class="btn btn-default">Choose File</button>
    </div>
    <input type="text" name="file" class="form-control "  id="file-input" readonly="readonly" onclick="document.getElementById('file').click();" />
</div>

-- TABS

### Customizing the custom file input

Not satisfied with the output? You can customize the custom file input by using the `_button` and `_input` options:

```php
$this->Form->file('file', [
    '_button' => ['class' => 'my-custom-button-class'],
    '_input' => ['data-attr' => 'my-data-attr', 'class' => 'my-input-class']
]);
```

**Note:** Some attributes cannot be overriden:

- For `_button`, the `type` and `onclick` attributes.
- For `_input`, the `readonly`, `onclick` and `id` attributes.

The classes specified in the `_button` and `_input` options will be added to the list of default classes for the custom input.
