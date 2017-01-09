<?php

require_once realpath(dirname(__FILE__))."/../lib/php/check_username.php";

if(isset($_GET)) {

    if(isset($_GET['username'])) {
        $result = Username::getUsernameStatus($_GET['username']);
        echo $result;
    }

}
?>
