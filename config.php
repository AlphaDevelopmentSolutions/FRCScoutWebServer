<?php

function validKey()
{
    $API_KEY = 'AZv4J7t2JpMe2UzQyQqtcNAwjSrmPTcZJhT5ZXVz';

    if($_POST['key'] != $API_KEY)
    {
        die('Invalid key.');
    }
}

?>