<?php

require_once 'select_provincia.php';

if (isset($_GET)) {

    if (isset($_GET['regione'])) {
        $result = SelectProvincia::getProvince($_GET['regione']);

        foreach ($result as $key => $value) {
            printf(
                "<option value='%s'>%s</option>\n",
                $value['sigla'],
                $value['nome']
            );
        }
    }
}

?>
