<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/select_provincia.php";

if (isset($_GET)) {

    if (isset($_GET['regione'])) {
        $result = SelectProvincia::getProvince($_GET['regione']);

        foreach ($result as $key => $value) {
            printf(
                "<option value='%s'>%s</option>\n",
                $value['nome'],
                $value['nome']
            );
        }
    }

}
