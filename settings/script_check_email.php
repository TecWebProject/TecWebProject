<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/check_email.php";

if (isset($_GET)) {

    if (isset($_GET['email'])) {
        $result = Email::checkEmail($_GET['email']);
        echo $result;
    }

}
?>
