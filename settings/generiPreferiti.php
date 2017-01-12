<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/generiMusicali.php";

if(!isset($_SESSION)) {
    session_start();
}

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormGeneriPreferiti
{

    //TODO dati da concordare $_SESSION['username']
    public static function getFormGeneriPreferiti()
    {
        if(!isset($_SESSION['datiUtente'])) {
            $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
        }

        $string = "<fieldset><legend>Generi preferiti</legend><ul>";

        $generi = GeneriMusicali::getGeneriMusicali();

        foreach ($generi as $key => $genere) {
           $string .= "<li><input id='modGenere$genere' type='checkbox' /><label for='modGenere$genere'> $genere </label></li>";
        }

        $string .= "</ul></fieldset>";

      return $string;
    }

}
