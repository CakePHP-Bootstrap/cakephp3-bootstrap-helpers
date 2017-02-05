### Without HTML

You can directly set the content of the body of your modal using the `body` method.

-- TABS: modal-ex-2

<div class="btn-modal">
    <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#MyModal2">Show Modal</button>
</div>
<div id="MyModal2" tabindex="-1" role="dialog" aria-hidden="true" aria-labbeledby="MyModal2Label" class="modal fade "><div class="modal-dialog"><div class="modal-content"><div class="modal-header "><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title" id="MyModal2Label">Example 2 - No HTML</h4></div><div class="modal-body my-body-class"><p>My body... !</p></div><div class="modal-footer "><button class="btn btn-primary" type="submit">Submit</button><button data-dismiss="modal" class="btn btn-default" type="submit">Close</button></div></div></div></div>

-- TAB: PHP

```php
<?php
    $content = "<p>My body... !</p>";
    echo $this->Modal->create(['id' => 'MyModal2']) ;
    echo $this->Modal->header('Example 2 - No HTML', ['close' => false]) ; 
    echo $this->Modal->body($content, ['class' => 'my-body-class']) ;
    echo $this->Modal->footer([
        $this->Form->button('Submit', ['bootstrap-type' => 'primary']),   
        $this->Form->button('Close', ['data-dismiss' => 'modal']) 
    ]) ;
    echo $this->Modal->end() ;
?>
```

-- TAB: Markup

```markup
<div id="MyModal2" tabindex="-1" role="dialog" aria-hidden="true" aria-labbeledby="MyModal2Label" class="modal fade ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header ">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="MyModal2Label">Example 2 - No HTML</h4>
            </div>
            <div class="modal-body my-body-class">
                <p>My body... !</p>
            </div>
            <div class="modal-footer ">
                <button class="btn btn-primary" type="submit">Submit</button>
                <button data-dismiss="modal" class="btn btn-default" type="submit">Close</button>
            </div>
        </div>
    </div>
</div>
```

-- TABS

