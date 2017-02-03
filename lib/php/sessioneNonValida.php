<?php

require_once realpath(dirname(__FILE__)) . '/paths.php';

/**
 * Created by PhpStorm.
 * User: pily
 * Date: 30/01/17
 * Time: 10.21
 */
class sessioneNonValida
{
    /***
     * Genera la pagina di redirect in caso di sessione non valida
     */
    static function manageSessioneNonValida()
    {

        //Costruzione dei dati

        # Abbsolute path to the file which called this script
        $stack = debug_backtrace();
        $executionFilePath = $stack[count($stack) - 1]["file"];

        # Abbsolute path to home folder
        $absMenuPath = realpath(dirname(__FILE__)) . "../../";

        # Relative path to home folder
        $relativePathToIndex = Paths::getRelativePath($executionFilePath, $absMenuPath);

        echo Start::getHead(
            array('Titolo' => "Impostazioni profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("BandBoard", "modifica", "profilo", "impostazioni", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array("<script src='../../settings/settings.js' type='text/javascript'></script>", "<meta http-equiv='refresh' content='5;URL=../'></meta>"))
        );
        echo "<body><div class='content'><h1>Sessione non valida</h1><h2>Verrai reindirizzato alla <a href='" . $relativePathToIndex . "'>pagina principale</a> in 5 secondi</h2></div></body></html>";
    }
}