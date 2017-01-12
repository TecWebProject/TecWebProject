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

//TODO dati temporanei, ho bisogno di un array di dati in session (o almeno dell'username)

if(!isset($_SESSION)) {
    session_start();
}

//TODO temporaneo, mi da un utente di default
$_SESSION['username'] = "giorgio";

// Carico dati utente se non già caricati
$_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);

// Reset numero campi se non salvato
if(!isset($_SESSION['campiDati'])) {
    $_SESSION['campiDati'] = count($_SESSION['datiUtente']['contatti']);
}

echo "<!--";
var_dump($_GET);
echo "-->";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
<?php
   echo Start::getHead(
       array('Titolo' => "Modifica profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("Modifica profilo","Impostazioni","BandBoard", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array("<script src='settings.js' type='text/javascript'></script>"))
   );
    ?>
</head>

<!-- TODO sostituire head con quella autogenerata -->

<body>
    <div class="header">Header standard</div>
    <div class="breadcrump"><h1>Modifica il tuo profilo</h1></div>
    <!-- TODO sostituire menu con quello autogenerato -->
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
