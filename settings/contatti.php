<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/contattiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/tipiContatto.php";

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormContatti
{
    public static function getFormContatti()
    {

        // Faccio partire la sessione se non è già partita
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['username'])) {
            throw new Exception("Missing username in SESSION", 1);
        }

        if (!isset($_SESSION['campiDati'])) {
            throw new Exception("Missing campiDati in SESSION", 1);
        }

        if (!isset($_SESSION['nCampi'])) {
            throw new Exception("Missing nCampi in SESSION", 1);
        }

        // Carico i contatti e i tipiContatto
        $tipiContatto = TipiContatto::getTipiContatto();

        $string = "<fieldset><legend>Contatti</legend><ul>";

        for ($i = 0; $i < $_SESSION['nCampi']; $i++) {

            if (isset($_SESSION['campiDati']['contatti'][$i])) {
                $entryContatto = $_SESSION['campiDati']['contatti'][$i];
            } else {
                $entryContatto = array('tipoContatto' => '', 'contatto' => '');
            }

            $string .= "<li><label for='tipoContatto" . $i . "'>Tipo contatto</label><select name='tipoContatto" . $i . "' id='tipoContatto" . $i . "'>";
            foreach ($tipiContatto as $key => $tipoContatto) {
                if (isset($entryContatto['tipoContatto']) && $entryContatto['tipoContatto'] == $tipoContatto) {
                    $string .= "<option value='$tipoContatto' selected='selected'>" . ucfirst(str_replace("_", " ", $tipoContatto)) . "</option>";
                } else {
                    $string .= "<option value='$tipoContatto'>" . ucfirst(str_replace("_", " ", $tipoContatto)) . "</option>";
                }
            }
            $string .= "</select>";

            if (isset($_SESSION['campiDati']['contatti'][$i])) {
                $string .= "<label for='campoContatto" . $i . "'>Contatto</label><input id='campoContatto" . $i . "' name='campoContatto" . $i . "' value='" . $entryContatto['contatto'] . "'/><button name='rimuoviCampo' title='Rimuovi il campo contatto' value='" . $i . "'>Rimuovi</button></li>";
            } else {
                $string .= "<label for='campoContatto" . $i . "'>Contatto</label><input id='campoContatto" . $i . "' name='campoContatto" . $i . " value='" . $entryContatto['contatto'] . "'/><button name='rimuoviCampo' value='" . $i . "' title='Rimuovi il campo contatto'>Rimuovi</button></li>";
            }
        }

        // Bottone aggiungi campo
        $string .= "<li><button name='aggiungiCampo' title='Aggiungi un campo contatti' value='true'>Aggiungi un campo</button></li>";


        $string .= "</ul></fieldset>";
        return $string;
    }
}
