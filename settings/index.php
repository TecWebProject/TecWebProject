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
require_once realpath(dirname(__FILE__)) . '/gestionePost.php';

//TODO dati temporanei, ho bisogno di un array di dati in session (o almeno dell'username)

//TODO temporaneo, mi da un utente di default
$_SESSION['username'] = "giorgio";

echo Start::getHead(
    array('Titolo' => "Modifica profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("Modifica profilo","Impostazioni","BandBoard", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array("<script src='settings.js' type='text/javascript'></script>"))
);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

    ?>

<body onload="clearProvince();">
    <div class="header">Header standard</div>
    <div class="breadcrump"><h1>Modifica il tuo profilo</h1></div>
    <div class="nav">
        <?php
         Menu::getMenu(array("Home","<a href='pagina.html'>Profilo</a>","<a href='pagina.html'>Cerca</a>","<a href='pagina.html'>Band</a>"));
        ?>
    </div>
    <div class="content">
        <div id="modFotoProfilo">
            <!-- TODO: Caricare immagine dinamicamente -->
            <label for="modLoadImage">
               <img src="../images/fotoProfilo.jpg">
            </label>
        </div>
        <div id="mod">
            <form action="." method="post">
               <fieldset>
                    <?php
                     echo FormDatiObbligatori::getFormDatiObbligatori();
                     echo FormDatiInformativi::getFormDatiInformativi();
                     echo FormGeneriPreferiti::getFormGeneriPreferiti();
                     echo FormContatti::getFormContatti();
                    ?>
                <input id="modSaveButton" type="submit" title='Salva le modifiche al tuo profilo' value="Salva modifiche" />
             </fieldset>
            </form>
        </div>
    </div>
    <div class="footer "></div>
</body>

</html>
