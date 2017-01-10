<?php
require_once realpath(dirname(__FILE__)) . '/../lib/php/select_provincia.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/regioni.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/province.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/menu.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/start.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/datiUtente.php';

//TODO dati temporanei, ho bisogno di un array di dati in session (o almeno dell'username)
session_start();

$_SESSION['username'] = "giorgio";

if(!isset($_SESSION['datiUtente'])) {
    $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);
}

if(!isset($_SESSION['campiDati'])){
   $_SESSION['campiDati'] = count($_SESSION['datiUtente']['contatti']);
}

// var_dump($_SESSION['datiUtente']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>

   <?php
      var_dump(Start::getHead(
         array(
            'Titolo' => "Modifica profilo - BandBoard", 'DescrizioneBreve' => "Pannello di modifica delle informazioni personali", 'Descrizione' => "Pagina per la modifica delle informazioni personali, dei contatti e della biografia del proprio profilo", 'Keywords' => array("Modifica profilo","Impostazioni","BandBoard", "band", "musica"), 'Stylesheets' => array("style.css"), 'Extra' => array(
               "<script src='settings.js' type='text/javascript'></script>",
            ))));
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
            <form action="processer.php" method="post">
               <fieldset>
                <fieldset>
                    <legend>Dati obbligatori</legend>
                    <ul>
                        <li><p>
                           Username: <?php echo $_SESSION['datiUtente']['username']?>
                        </p></li>
                        <!-- TODO Nome -->
                        <li><label for="modNome">Nome</label><input id="modNome" placeholder="Nome" onblur="checkNome(this.value)" onkeypress="clearError('nome')"/><p id="errorModNome"></p></li>
                        <!-- TODO Cognome -->
                        <li><label for="modCognome">Cognome</label><input id="modCognome" placeholder="Cognome" onblur="checkCognome(this.value)" onkeypress="clearError('cognome')"/><p id="errorModCognome"></p></li>
                        <li><label for="modEmail">Email</label><input id="modEmail" type="email" placeholder="email" onblur="checkEmail(this.value)" onkeypress="clearError('email')"/><p id="errorModEmail"></p></li>
                        <li><label for="modDataNascita">Data di nascita</label><input id="modDataNascita" placeholder="gg/mm/aaaa" onchange="clearError('data')" onblur="checkBDay(this.value)" /><p id="errorModDataNascita"></p></li>
                    </ul>
                </fieldset>
                <fieldset>
                    <legend>Dati informativi</legend>
                    <ul>
                        <li>
                            <label for="modLoadImage">Carica immagine profilo</label><input id="modLoadImage" type="file" title="Carica immagine"><p id="errorModLoadImage"></p>
                        </li>
                        <li>
                            <label for="modSelectRegione">Regione di provenienza</label>
                            <select id="modSelectRegione" onchange="showProvince(this.value)">
                               <option value="">Seleziona regione</option>
                                <?php
                                $regioni = Regioni::getRegioni();

                                foreach ($regioni as $key => $regione) {
                                    printf("<option value='%s'>%s</option>", $regione['nome'], $regione['nome']);
                                }
                                ?>
                            </select>
                        </li>
                        <li>
                            <label for="modSelectProvincia">Seleziona provincia</label>
                            <select id="modSelectProvincia">
                               <option value="">Seleziona provincia</option>
                                <?php
                                $province = Province::getProvince();
                                foreach ($province as $key => $provincia) {
                                    printf("<option value='%s'>%s</option>", $provincia['sigla'], $provincia['nome']);
                                }
                                ?>
                            </select>
                            <script type="text/javascript">clearProvince();</script>
                        </li>
                        <li>
                            <label for="modTextAreaBio">Bio</label><textarea id="modTextAreaBio" cols="40" rows="4" placeholder="Scrivi una breve descrizione di te..."></textarea>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <legend>Contatti</legend>
                  <ul>
                     <li>
                        <input type="button" value="Aggiungi campo" />
                    </li>
                    <li>
                        <label for="contatto1tipo">Tipo contatto</label>
                        <select id="contatto1tipo">
                            <option>Telefono (cellulare)</option>
                            <option>Telefono (casa)</option>
                            <option>Facebook</option>
                            <option>Telegram</option>
                            <option>Skype</option>
                        </select>
                    </li>
                    <li>
                        <label for="contatto2campo">Valore</label>
                        <input id="contatto2campo" />
                    </li>
                    <li>
                        <label for="contatto2tipo">Tipo contatto</label>
                        <select id="contatto2tipo">
                            <option>Telefono (cellulare)</option>
                            <option>Telefono (casa)</option>
                            <option>Facebook</option>
                            <option>Telegram</option>
                            <option>Skype</option>
                        </select>
                    </li>
                    <li>
                        <label for="contatto1campo">Valore</label>
                        <input id="contatto1campo" /> </fieldset>
                </li>
                  </ul>
                <fieldset>
                    <legend>Generi preferiti</legend>
                    <label for="modGenereRock"> Rock </label>
                    <input id="modGenereRock" type="checkbox" />
                    <label for="modGenerePop"> Pop </label>
                    <input id="modGenerePop" type="checkbox" />
                    <label for="modGenereAcustica"> Acustica </label>
                    <input id="modGenereAcustica" type="checkbox" />
                    <label for="modGenereBluse"> Blues </label>
                    <input id="modGenereBluse" type="checkbox" />
                    <label for="modGenereMetal"> Metal </label>
                    <input id="modGenereMetal" type="checkbox" />
                    <label for="modGenerePunk"> Punk </label>
                    <input id="modGenerePunk" type="checkbox" />
                    <!-- La lista può essere estesa dinamicamente -->
                </fieldset>
                <input id="modSaveButton" type="submit" value="Salva modifiche" />
             </fieldset>
            </form>
        </div>
    </div>
    <div class="footer "></div>
</body>

</html>
