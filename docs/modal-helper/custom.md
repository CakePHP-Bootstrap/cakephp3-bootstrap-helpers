### Fully customized modals

You can also fully customize the various parts of the modal.

-- TABS: modal-ex-3

<div class="btn-modal">
    <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#MyModal3">Show Modal</button>
</div>
<div id="MyModal3" tabindex="-1" role="dialog" aria-hidden="true" aria-labbeledby="MyModal3Label" class="modal fade "><div class="modal-dialog"><div class="modal-content"><div class="modal-header my-header-class"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4>My Header!</h4></div><div class="modal-body "><p>My body!</p></div><div class="modal-footer "><p>My footer... Oops, no buttons!</p></div></div></div></div>
-- TAB: PHP

```php
<?php
    echo $this->Modal->create(['id' => 'MyModal4']) ;
    echo $this->Modal->body() ; // No header
    echo $this->Modal->footer() ; // Footer with close button (default)
    echo $this->Modal->end() ;
?>
```

-- TAB: Markup

```markup
<div id="MyModal4" tabindex="-1" role="dialog" aria-hidden="true" class="modal fade ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body ">
            </div>
            <div class="modal-footer ">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
```

-- TABS

