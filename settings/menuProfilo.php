<?php

require_once realpath(dirname(__FILE__)) . '/../lib/php/paths.php';

/**
 * Created by PhpStorm.
 * User: pily
 * Date: 30/01/17
 * Time: 9.28
 */
class MenuProfilo
{
    /***
     * @param int $ind Indice della pagina aperta
     * @return string   Stringa del menu
     */
    static function getMenuProfilo($ind = -1)
    {
        //Costruzione dei dati

        # Abbsolute path to the file which called this script
        $stack = debug_backtrace();
        $executionFilePath = $stack[count($stack) - 1]["file"];

        # Abbsolute path to images folder
        $absMenuPath = realpath(dirname(__FILE__)) . "/";

        # Relative path to images folder
        $relativePathToMenuEntry = Paths::getRelativePath($executionFilePath, $absMenuPath);

        $entryMenu = array(
            array("stringa" => "Dati obbligatori", "link" => $relativePathToMenuEntry . "datiObbligatori.php"),
            array("stringa" => "Dati informativi", "link" => $relativePathToMenuEntry . "datiInformativi.php"),
            array("stringa" => "Preferenze musicali", "link" => $relativePathToMenuEntry . "generiPreferiti.php"),
            array("stringa" => "Contatti", "link" => $relativePathToMenuEntry . "contatti.php")
        );

        //Controllo input

        if (!($ind == -1 || $ind < count($entryMenu))) {
            throw new InvalidArgumentException("Input non valido");
        }

        //Inizio della produzione della stringa

        $string = "<div id='modMenuPagine'><ul>";

        for ($i = 0; $i < count($entryMenu); ++$i) {
            if ($i == $ind) {
                $string .= "<li>" . $entryMenu[$i]["stringa"] . "</li>";
            } else {
                $string .= "<li><a href='" . $entryMenu[$i]["link"] . "'>" . $entryMenu[$i]["stringa"] . "</a></li>";
            }
        }

        $string .= "</ul></div>";

        return $string;
    }
}