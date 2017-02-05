### Basic usage

The Bootstrap 3 modal helper allow you to easily create modal without having to write bunch of html code. The basic way of using this helper this
by creating a modal with a title, then adding the body using standard PHP/HTML and then closing the modals with or without buttons.

-- TABS: modal-ex-1

<div class="btn-modal">
    <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#MyModal1">Show Modal</button>
</div>
<div id="MyModal1" tabindex="-1" role="dialog" aria-hidden="true" aria-labbeledby="MyModal1Label" class="modal fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header "><h4 class="modal-title" id="MyModal1Label">Example 1 - Simple header &amp; footer, custom body</h4></div><div class="modal-body "><p>Here I write the body of my modal !</p></div><div class="modal-footer "><button class="btn btn-primary btn-primary" type="submit">Submit</button><button data-dismiss="modal" class="btn btn-default" type="submit">Close</button></div></div></div></div>

-- TAB: PHP

```php
<?php
    // Start a modal with a title, default value for 'close' is true
    echo $this->Modal->create("My Modal Form", ['id' => 'MyModal1', 'close' => false]) ; 
?>
<p>Here I write the body of my modal !</p>
<?php
    // Close the modal, output a footer with a 'close' button
    echo $this->Modal->end() ;
    // It is possible to specify custom buttons:
    echo $this->Modal->end([
        $this->Form->button('Submit', ['bootstrap-type' => 'primary']),   
        $this->Form->button('Close', ['data-dismiss' => 'modal']) 
    ]);
?>
```

-- TAB: Markup

```markup
<div id="MyModal1" tabindex="-1" role="dialog" aria-hidden="true" aria-labbeledby="MyModal1Label" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header ">
                <!-- With 'close' => true, or without specifying:
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button> -->
                <h4 class="modal-title" id="MyModal1Label">Example 1 - Simple header & footer, custom body</h4>
            </div>
            <div class="modal-body ">    
                <p>Here I write the body of my modal !</p>
            </div>
            <div class="modal-footer ">
                <button class="btn btn-primary btn-primary" type="submit">Submit</button>
                <button data-dismiss="modal" class="btn btn-default" type="submit">Close</button>
            </div>
        </div>
    </div>
</div>
```

-- TABS

