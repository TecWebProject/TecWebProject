<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/generiMusicali.php";

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormGeneriPreferiti
{
    public static function getFormGeneriPreferiti()
    {

      if (session_status() == PHP_SESSION_NONE) {
           session_start();
      }

      if(!isset($_SESSION['datiUtente'])) {
           $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
      }

        $string = "<fieldset><legend>Generi preferiti</legend><ul>";

        $generi = GeneriMusicali::getGeneriMusicali();

        foreach ($generi as $key => $genere) {
            $string .= "<li><input id='modGenere".htmlentities(preg_replace("/\s|\&/","_",$genere), ENT_QUOTES, "UTF-8")."' name='genere".htmlentities(preg_replace("/\s|\&/","_",$genere), ENT_QUOTES, "UTF-8")."' type='checkbox'/><label for='modGenere".htmlentities(preg_replace("/\s|\&/","_",$genere))."'>".htmlentities($genere, ENT_QUOTES, "UTF-8")."</label></li>";
        }

        $string .= "</ul></fieldset>";

        return $string;
    }

}
