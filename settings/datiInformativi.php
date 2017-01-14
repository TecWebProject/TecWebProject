<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/regioni.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/province.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/select_regione.php";


/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormDatiInformativi
{

    //TODO richiede che nella sessione sia salvato $_SESSION['username'] per l'identificazione
    public static function getFormDatiInformativi()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        $string = "<fieldset><legend>Dati informativi</legend><ul>";

        // immagine profilo
        $string .= "<li><label for='modLoadImage'>Carica immagine profilo</label><input id='modLoadImage' type='file' title='Carica immagine'/><p id='errorModLoadImage'></p></li>";

        // regione di provenienza
        $string .= "<li>
            <label for='modSelectRegione'>Regione di provenienza</label>
            <select id='modSelectRegione' onchange='showProvince(this.value)'>
               <option value=''>Seleziona regione</option>";

         $regioni = Regioni::getRegioni();
         $regioneAppartenenza = SelectRegione::getRegione($_SESSION['datiUtente']['provincia']);

        foreach ($regioni as $key => $regione) {
            if($regione['nome'] == $regioneAppartenenza) {
                $string .= "<option value='".htmlentities($regione['nome'], ENT_QUOTES, "UTF-8")."' selected='selected'>".htmlentities($regione['nome'], ENT_QUOTES, "UTF-8")."</option>";
            } else {
                 $string .= "<option value='".htmlentities($regione['nome'], ENT_QUOTES, "UTF-8")."'>".htmlentities($regione['nome'], ENT_QUOTES, "UTF-8")."</option>";
            }
        }
         $string .= "</select></li>";

         // provincia di appartenenza
         $string .= "<li>
             <label for='modSelectProvincia'>Seleziona provincia</label>
             <select id='modSelectProvincia'>
                <option value=''>Seleziona provincia</option>";

          $province = Province::getProvince();

        foreach ($province as $key => $provincia) {
            if($provincia['sigla'] == $_SESSION['datiUtente']['provincia']) {
                $string .= "<option value='".htmlentities($provincia['sigla'], ENT_QUOTES, "UTF-8")."' selected='selected'>".htmlentities($provincia['nome'], ENT_QUOTES, "UTF-8")."</option>";
            } else {
                $string .= "<option value='".htmlentities($provincia['sigla'], ENT_QUOTES, "UTF-8")."'>".htmlentities($provincia['nome'], ENT_QUOTES, "UTF-8")."</option>";
            };
        }
        $string .= "</select></li>";

         // bio
         //TODO placeholder "Scrivi una breve descrizione di te..."
         $string .= "<li><label for='modTextAreaBio'>Bio</label><textarea id='modTextAreaBio' cols='40' rows='4' onblur='checkBio(this.value)'>".$_SESSION['datiUtente']['descrizione']."</textarea><span id='errorModBio' class='modErrorEntry'></span></li>";

         $string .= "</ul></fieldset>";

         return $string;
    }

}
