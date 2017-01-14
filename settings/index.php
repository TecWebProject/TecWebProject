<?php

require_once realpath(dirname(__FILE__)) . '/../lib/php/select_provincia.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/regioni.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/province.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/menu.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/start.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/start.php';
require_once realpath(dirname(__FILE__)) . '/datiObbligatori.php';
require_once realpath(dirname(__FILE__)) . '/datiInformativi.php';
require_once realpath(dirname(__FILE__)) . '/contatti.php';
require_once realpath(dirname(__FILE__)) . '/generiPreferiti.php';
require_once realpath(dirname(__FILE__)) . '/gestioneInput.php';

try {

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //TODO temporaneo, mi da un utente di default
    $_SESSION['username'] = "giorgio";

    $string = "";

    // Gestione degli input
    GestioneInput::doGestioneInput();

    // Generazione head
    $string .= Start::getHead(
        array('Titolo' => "Modifica profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("Modifica profilo","Impostazioni","BandBoard", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array("<script src='settings.js' type='text/javascript'></script>"))
    );

    // Inizio body
    $string .= "<body onload='clearProvince();'><div class='header'>Header standard</div><div class='breadcrump'><h1>Modifica il tuo profilo</h1></div><div class='nav'>";

    // Menu
    $string .= Menu::getMenu(array("Home","<a href='pagina.html'>Profilo</a>","<a href='pagina.html'>Cerca</a>","<a href='pagina.html'>Band</a>"));

    // Fine Menu
    $string .= "</div>";

    // Inizio content
    $string .= "<div class='content'>";

    // Immagine profilo
    $string .= "
    <div id='modFotoProfilo'>
      <!-- TODO: Caricare immagine dinamicamente -->
      <label for='modLoadImage'><img src='../images/fotoProfilo.jpg' alt=\"Immagine profilo dell\'utente\"/></label>
    </div>";

    // Inizio form
    $string .= "
        <div id='mod'>
            <form action='.' method='post' onsubmit='checkForm();'>
               <fieldset>";

    // Dati obbligatori
    $string .= FormDatiObbligatori::getFormDatiObbligatori();

    // Dati informativi
    $string .= FormDatiInformativi::getFormDatiInformativi();

    // Generi preferiti
    $string .= FormGeneriPreferiti::getFormGeneriPreferiti();

    // Contatti
    $string .= FormContatti::getFormContatti();

    // Submit
    $string .= "<input id='modSaveButton' type='submit' title='Salva le modifiche al tuo profilo' value='Salva modifiche' />";

    // Chiusura form
    $string .= "</fieldset></form></div></div>";

    // Footer
    $string .= "<div class='footer'></div>";

    // Chiusura body e html
    $string .= "</body></html>";

    echo $string;

} catch (Exception $e) {
    echo $e->getMessage();
}

?>
