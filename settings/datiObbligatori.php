<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";

session_start();
/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormDatiObbligatori
{

    //TODO dati da concordare $_SESSION['username']
    public static function getFormDatiObbligatori()
    {
        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }
        $string = "<fieldset><legend>Dati obbligatori</legend><ul>";

        // username
        $string .= "<li><p>Username: ".$_SESSION['datiUtente']['username']."</p></li>";

        // nome
        $string .= "<li><label for='modNome'>Nome</label><input id='modNome' placeholder='Nome' value='";
        $string .= $_SESSION['datiUtente']['nome'];
        $string .= "' onblur='checkNome(this.value)' onkeypress='clearError(\"nome\")'/><p id='errorModNome'></p></li>";

        // cognome
        $string .= "<li><label for='modCognome'>Cognome</label><input id='modCognome' placeholder='Cognome' value='";
        $string .= $_SESSION['datiUtente']['cognome'];
        $string .= "' onblur='checkCognome(this.value)' onkeypress='clearError(\"cognome\")'/><p id='errorModCognome'></p></li>";

        // email
        $string.= "<li><label for='modEmail'>Email</label><input id='modEmail' type='email' placeholder='email' value='";
        $string .= $_SESSION['datiUtente']['email'];
        $string .= "' onblur='checkEmail(this.value)' onkeypress='clearError(\"email\")'/><p id='errorModEmail'></p></li>";

        // data nascita
        $string .= "<li><label for='modDataNascita'>Data di nascita</label><input id='modDataNascita' placeholder='gg/mm/aaaa' value='";
        $string .= date("d/m/Y", strtotime($_SESSION['datiUtente']['dataNascita']));
        $string .= "' onchange='clearError(\"data\")' onblur='checkBDay(this.value)' /><p id='errorModDataNascita'></p></li>";

        $string .= "</ul></fieldset>";

        return $string;
    }

}
