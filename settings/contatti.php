<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/contattiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/tipiContatto.php";

if(!isset($_SESSION)) {
    session_start();
}
/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormContatti
{
    public static function getFormContatti()
    {

        // Carico i contatti e i tipiContatto
        $contatti = contattiUtente::getContattiUtente($_SESSION['username']);
        $tipiContatto = TipiContatto::getTipiContatto();

        // Aggiungi un campo dato se sengalato in post
        if(isset($_POST['aggiungiCampo'])) {
            $_SESSION['campiDati'] += 1;
        }

        // Campi almeno quanto i contatti
        if($_SESSION['campiDati'] < count($_SESSION['datiUtente']['contatti'])) {
            $_SESSION['campiDati'] = count($_SESSION['datiUtente']['contatti']);
        }

        unset($_SESSION['datiUtente']);

        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        $string = "<fieldset><legend>Contatti</legend><ul>";

        for ($i=0; $i < $_SESSION['campiDati']; $i++) {
            $string .= "<li><label for='tipoContatto".$i."'>Tipo contatto</label><select  name='tipoContatto".$i."' id='tipoContatto".$i."'>";
            foreach ($tipiContatto as $key => $tipoContatto) {
                if(isset($contatti[$i]) && isset($contatti[$i]['tipoContatto']) && $contatti[$i]['tipoContatto'] == $tipoContatto) {
                    $string .= "<option value='$tipoContatto' selected='selected'>".ucfirst(str_replace("_", " ", $tipoContatto))."</option>";
                } else {
                    $string .= "<option value='$tipoContatto'>".ucfirst(str_replace("_", " ", $tipoContatto))."</option>";
                }
            }
             $string .= "</select>";
            if(isset($contatti[$i])) {
                $string .= "<label for='campoContatto".$i."'>Contatto</label><input name='campoContatto".$i."' value='".$contatti[$i]['contatto']."'/><button name='rimuoviCampo".$i."' title='Rimuovi il campo contatto'>Rimuovi</button></li>";
            } else {
                $string .= "<label for='campoContatto".$i."'>Contatto</label><input name='campoContatto".$i."'/><button name='rimuoviCampo' value='".$i."' title='Rimuovi il campo contatto'>Rimuovi</button></li>";
            }
        }

        // Bottone aggiungi campo
        $string .= "<li><button name='aggiungiCampo' title='Aggiungi un campo contatti' value='Aggiungi campo'>Aggiungi un campo</button></li>";
        $string .= "</ul></fieldset>";
        return $string;
    }
}
