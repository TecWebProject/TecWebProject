<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";

session_start();
/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormDatiObbligatori
{
    public static function getFormDatiObbligatori()
    {
        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }
        $string = "<fieldset><legend>Dati obbligatori</legend><ul>";

        // username
        $string .= "<li>Username: ".$_SESSION['datiUtente']['username']."</li>";

        // nome
        $string .= "<li><label for='modNome'>Nome</label><input id='modNome' placeholder='Nome' value='";
        $string .= $_SESSION['datiUtente']['nome'];
        $string .= "' onblur='checkNome(this.value)' onkeypress='clearError(\"nome\")'/><span id='errorModNome' class='modErrorEntry'></span></li>";

        // cognome
        $string .= "<li><label for='modCognome'>Cognome</label><input id='modCognome' placeholder='Cognome' value='";
        $string .= $_SESSION['datiUtente']['cognome'];
        $string .= "' onblur='checkCognome(this.value)' onkeypress='clearError(\"cognome\")'/><span id='errorModCognome' class='modErrorEntry'></span></li>";

        // email
        $string.= "<li><label for='modEmail'>Email</label><input id='modEmail' placeholder='email' value='";
        $string .= $_SESSION['datiUtente']['email'];
        $string .= "' onblur='checkEmail(this.value)' onkeypress='clearError(\"email\")'/><span id='errorModEmail' class='modErrorEntry'></span></li>";

        // data nascita
        //   $string .= "<li><label for='modDataNascitaGiorno'>Data di nascita</label><input type='text' id='modDataNascitaGiorno' length='2' value='";
        //   $string .= date("d", strtotime($_SESSION['datiUtente']['dataNascita']));
        //   $string .= "' onkeypress='clearError(\"data\")' onblur='checkBDay()' /><label for='modDataNascita'>Data di nascita</label><input type='text' length='2' value='<p id='errorModDataNascita'></p></li>";

        $string .= "<li><div>Data di nascita:</div><label for='modDataNascitaGiorno'>Giorno</label><input id='modDataNascitaGiorno' type='text' size='2' maxlength='2'  placeholder='gg' value='";
         $string .= date("d", strtotime($_SESSION['datiUtente']['dataNascita']));
         $string .= "' onkeypress='clearError(\"data\")' onblur='checkBDay()'/><label for='modDataNascitaMese'>Mese</label><input id='modDataNascitaMese' type='text' size='2' maxlength='2'  placeholder='mm' value='";
         $string .= date("m", strtotime($_SESSION['datiUtente']['dataNascita']));
         $string .= "' onkeypress='clearError(\"data\")' onblur='checkBDay()'/><label for='modDataNascitaAnno'>Anno</label><input id='modDataNascitaAnno' type='text' size='4' maxlength='4'  placeholder='aaaa' value='";
         $string .= date("Y", strtotime($_SESSION['datiUtente']['dataNascita']));
         $string .= "' onkeypress='clearError(\"data\")'onblur='checkBDay()'/><span id='errorModDataNascita' class='modErrorEntry'></span></li>";

         $string .= "</ul></fieldset>";

         return $string;
    }

}
