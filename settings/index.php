<?php

/*Pagina di selezione delle impostazioni*/

require_once realpath(dirname(__FILE__)) . '/../lib/php/menu.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/start.php';
require_once realpath(dirname(__FILE__)) . '/menuProfilo.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/sessioneNonValida.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/header.php';

try {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //TODO temporaneo, mi da un utente di default
    $_SESSION['username'] = "giorgio";

    if (!isset($_SESSION['username'])) {
        throw new Exception("Invalid session");
    }

    $string = "";

    // Generazione head
    $string .= Start::getHead(
        array('Titolo' => "Impostazioni profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("Modifica profilo", "Impostazioni", "BandBoard", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array("<script src='settings.js' type='text/javascript'></script>", /*TODO temp*/
            "<meta http-equiv='refresh' content='20'>"))
    );

    // Inizio body
    //TODO header standard
    $string .= "<body>" . Header::getHeader() . "<div class='breadcrump'><h2>Modifica il tuo profilo</h2></div>";

    // Menu
    $string .= "<div class='nav'>" . Menu::getMenu(array("Home", "<a href='pagina.html'>Profilo</a>", "<a href='pagina.html'>Cerca</a>", "<a href='pagina.html'>Band</a>")) . "</div>";

    $string .= "<div class='content'>";

    $string .= MenuProfilo::getMenuProfilo();

    $string .= "<div id='modImmagineSfondoMenu'></div>";

    $string .= "</div>";

    $string .= "</body></html>";

    echo $string;

} catch (Exception $e) {
    switch ($e->getMessage()) {
        case "Invalid session":
            sessioneNonValida::manageSessioneNonValida();
            break;
        default:
            echo $e->getMessage();
            break;
    }
}
