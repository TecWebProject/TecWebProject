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

        if(!isset($_SESSION['username'])) {
            throw new Exception("Missing username in SESSION", 1);
        }

        // Reset numero campi se non salvato
        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente']['contatti'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        if(!isset($_SESSION['campiDati'])) {
            $_SESSION['campiDati'] = $_SESSION['datiUtente'];
        }

        // Carico i contatti e i tipiContatto
        $contatti = contattiUtente::getContattiUtente($_SESSION['username']);
        $tipiContatto = TipiContatto::getTipiContatto();

        // Campi almeno quanto i contatti
        if($_SESSION['campiDati'] < count($_SESSION['datiUtente']['contatti'])) {
            $_SESSION['campiDati'] = count($_SESSION['datiUtente']['contatti']);
        }

        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        $string = "<fieldset><legend>Contatti</legend><ul>";

        for ($i=0; $i < $_SESSION['nCampi']; $i++) {
            if(isset($_SESSION['campiDati']['contatti'][$i])) {
                $value = $_SESSION['campiDati']['contatti'][$i];
            } else {
                $value = array('tipoContatto' => '', 'contatto' => '');
            }
            $string .= "<li><label for='tipoContatto".$i."'>Tipo contatto</label><select  name='tipoContatto".$i."' id='tipoContatto".$i."'>";
            foreach ($tipiContatto as $key => $tipoContatto) {
                if(isset($value['tipoContatto']) && $value['tipoContatto'] == $tipoContatto) {
                     $string .= "<option value='$tipoContatto' selected='selected'>".ucfirst(str_replace("_", " ", $tipoContatto))."</option>";
                } else {
                     $string .= "<option value='$tipoContatto'>".ucfirst(str_replace("_", " ", $tipoContatto))."</option>";
                }
            }

            $string .= "</select>";
            if(isset($contatti[$i])) {
                $string .= "<label for='campoContatto".$i."'>Contatto</label><input id='campoContatto".$i."' name='campoContatto".$i."' value='".$value['contatto']."'/><button name='rimuoviCampo".$i."' title='Rimuovi il campo contatto'>Rimuovi</button></li>";
            } else {
                $string .= "<label for='campoContatto".$i."'>Contatto</label><input id='campoContatto".$i."' name='campoContatto".$i."'/><button name='rimuoviCampo' value='".$i."' title='Rimuovi il campo contatto'>Rimuovi</button></li>";
            }
        }

        // Bottone aggiungi campo
        $string .= "<li><button name='aggiungiCampo' title='Aggiungi un campo contatti' value='true'>Aggiungi un campo</button></li>";



        $string .= "</ul></fieldset>";
        return $string;
    }
}
