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

        // Campi almeno quanto i contatti
        if($_SESSION['campiDati'] < count($_SESSION['datiUtente']['contatti'])) {
            $_SESSION['campiDati'] = count($_SESSION['datiUtente']['contatti']);
        }

        unset($_SESSION['datiUtente']);

        if(!isset($_SESSION['datiUtente'])) {

            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        $string = "<fieldset><legend>Contatti</legend><ul>";

        // Contatti
        $contatti = contattiUtente::getContattiUtente($_SESSION['username']);
        $tipiContatto = TipiContatto::getTipiContatto();

        for ($i=0; $i < $_SESSION['campiDati']; $i++) {
           $string .= "<li><label for='tipoContatto".$i."'>Tipo contatto</label><select id='tipoContatto".$i."'>";
           foreach ($tipiContatto as $key => $tipoContatto) {
               if(isset($contatti[$i]) && isset($contatti[$i]['tipoContatto']) && $contatti[$i]['tipoContatto'] == $tipoContatto){
                  echo "<!-- TRUE -->";
                  $string .= "<option value='$tipoContatto' selected='selected'>".ucfirst(str_replace("_", " ", $tipoContatto))."</option>";
               } else {
                  $string .= "<option value='$tipoContatto'>".ucfirst(str_replace("_", " ", $tipoContatto))."</option>";
               }
           }
             $string .= "</select>";
           if(isset($contatti[$i])){
              $string .= "<label for='campoContatto".$i."'>Contatto</label><input id='campoContatto".$i."' value='".$contatti[$i]['contatto']."'/></li>";
          } else {
              $string .= "<label for='campoContatto".$i."'>Contatto</label><input id='campoContatto".$i."'/></li>";
          }
        }

        // Bottone aggiungi campo
        $string .= "<li>
          <input id='modAggiungiCampoContatti' title='Aggiungi un campo contatti' type='submit' value='Aggiungi campo'/></li>";
        $string .= "</ul></fieldset>";
        return $string;
    }
}
