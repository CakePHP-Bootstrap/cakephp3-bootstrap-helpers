<?php
    $helper = new \Bootstrap\View\Helper\BootstrapHtmlHelper ($this) ;
    echo $helper->alert (h($message), 'success', $params) ; 
?>
