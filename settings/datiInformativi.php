<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/regioni.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/province.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/select_regione.php";



if(!isset($_SESSION)) {
    session_start();
}
/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormDatiInformativi
{

    //TODO dati da concordare $_SESSION['username']
    public static function getFormDatiInformativi()
    {
        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        $string = "<fieldset><legend>Dati informativi</legend><ul>";

        // immagine profilo
        $string .= "<li><label for='modLoadImage'>Carica immagine profilo</label><input id='modLoadImage' type='file' title='Carica immagine'><p id='errorModLoadImage'></p></li>";

        // regione di provenienza
        $string .= "<li>
            <label for='modSelectRegione'>Regione di provenienza</label>
            <select id='modSelectRegione' onchange='showProvince(this.value)'>
               <option value=''>Seleziona regione</option>";

         $regioni = Regioni::getRegioni();
         $regioneAppartenenza = SelectRegione::getRegione($_SESSION['datiUtente']['provincia']);

        foreach ($regioni as $key => $regione) {
           if($regione['nome'] == $regioneAppartenenza){
             $string .= "<option value='".htmlentities($regione['nome'], ENT_QUOTES, "UTF-8")."' selected='selected'>".$regione['nome']."</option>";
          } else {
             $string .= "<option value='".htmlentities($regione['nome'], ENT_QUOTES, "UTF-8")."'>".$regione['nome']."</option>";
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
                   $string .= "<option value='".$provincia['sigla']."' selected='selected'>".$provincia['nome']."</option>";
               } else {
                    $string .= "<option value='".$provincia['sigla']."'>".$provincia['nome']."</option>";
               };
           }

         $string .= "</select><script type='text/javascript'>clearProvince();</script></li>";

         // bio
         $string .= "<li><label for='modTextAreaBio'>Bio</label><textarea id='modTextAreaBio' cols='40' rows='4' placeholder='Scrivi una breve descrizione di te...' onblur='checkBio(this.value)'>".$_SESSION['datiUtente']['descrizione']."</textarea><ul id='errorModBio' class='modErrorEntry'></ul></li>";

         $string .= "</ul></fieldset>";

         return $string;
    }

}
