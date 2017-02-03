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

    if (!isset($_SESSION['username'])) {
        throw new Exception("Invalid session");
    }

    $string = "";

    // Generazione head
    $string .= Start::getHead(
        array('Titolo' => "Impostazioni profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("Modifica profilo", "Impostazioni", "BandBoard", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array("<script src='settings.js' type='text/javascript'></script>"))
    );

    // Inizio body
    $string .= "<body>" . Header::getHeader() . "<div class='breadcrump'><h2>Modifica il tuo profilo</h2></div>";

    // Menu
    $string .= "<div class='nav'>" . Menu::getMenu(array('<a href="../index.php" xml:lang="en" lang="en">Home</a>',
            '<a href="../profiloUtente/profiloUtente.php?username=' . $_SESSION['username'] . '">Visualizza Profilo</a>',
            '<a href="../cercaUtenti/index.php">Cerca Utenti</a>', '<a href="../cercaGruppi/index.php">Cerca Gruppi</a>', '<a href="../gestioneGruppi/index.php">I miei Gruppi</a>')) . "</div>";

    $string .= "<div id='content'>";

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
