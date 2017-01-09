<?php
require_once realpath(dirname(__FILE__)) . '/../lib/php/select_provincia.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/regioni.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/province.php';
require_once realpath(dirname(__FILE__)) . '/../lib/php/menu.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <!-- TODO: correggere head per il sito corretto -->
    <!-- Specifico il charset -->
    <meta http-equiv="Content-Type" content="txt/html" ; charset="utf-8">
    <!-- Titolo -->
    <!-- TODO: IMPOSTARE I NOMI CON PHP-->
    <title>Modifica profilo - NOME SITO</title>
    <!-- Titolo esteso dentro un meta -->
    <!-- TODO: IMPOSTARE I NOMI CON PHP-->
    <meta name="title" content="Modifica del profilo utente - NOME SITO" />
    <!-- Descrizione del sito -->
    <!-- TODO: INSERIRE DESCRIZIONE DELLA PAGINA-->
    <meta name="description" content="DESCRIZIONE" />
    <!-- Keyword principali del sito -->
    <!-- TODO: INSERIRE KEYWORD ADATTE-->
    <meta name="keywords" content="modifica, profilo, aggiornamento, NOME_SITO" />
    <!-- Icona del bookmark -->
    <!-- TODO: IMPOSTARE LINK ICONA-->
    <link rel="shortcut icon" href="images/icon.ico" />
    <!--miniReset-->
    <!--    <link rel="stylesheet" href="../lib/css/minireset.min.css" />-->
    <!-- link al CSS -->
    <link rel="stylesheet" href="../lib/css/style.css" />
    <script src="settings.js" type="text/javascript"></script>
</head>

<!-- TODO sostituire head con quella autogenerata -->

<body>
    <div class="header">Header standard</div>
    <div class="breadcrump">Modifica il tuo profilo</div>
    <!-- TODO sostituire menu con quello autogenerato -->
    <div class="nav">
        <?php
         Menu::getMenu(array("Home","<a href='pagina.html'>Profilo</a>","<a href='pagina.html'>Cerca</a>","<a href='pagina.html'>Band</a>"));
        ?>
    </div>
    <div class="content">
        <div id="modFotoProfilo">
            <!-- TODO: Caricare immagine dinamicamente --><img src="../images/fotoProfilo.jpg">
        </div>
        <div>
            <form action="processer.php" method="post">
                <fieldset>
                    <legend>Dati obbligatori</legend>
                    <ul>
                        <li><label for="modUsername">Username</label><input id="modUsername" placeholder="username" /></li>
                        <li><label for="modEmail">Email</label><input id="modEmail" type="email" placeholder="email" /></li>
                        <li><label for="modDataNascita">Data di nascita</label><input id="modDataNascita" type="date" placeholder="gg/mm/aaaa" /></li>
                    </ul>
                </fieldset>
                <fieldset>
                    <legend>Dati informativi</legend>
                    <ul>
                        <li>
                            <label for="modLoadImage">Carica immagine profilo</label><input type="file" title="Carica immagine">
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
                            <select id="modSelectProvincia" onload="hideProvince()">
                               <option value="">Seleziona provincia</option>
                                <?php
                                $province = Province::getProvince();
                                foreach ($province as $key => $provincia) {
                                    printf("<option value='%s'>%s</option>", $provincia['sigla'], $provincia['nome']);
                                }
                                ?>
                            </select>
                            <script> hideProvince(document); </script>
                        </li>
                        <li>
                            <label for="modTextAreaBio"> Bio</label><textarea id="modTextAreaBio" cols="40" rows="4" placeholder="Scrivi una breve descrizione di te..."></textarea>
                        </li>
                    </ul>
                </fieldset>
                <fieldset>
                    <legend>Contatti</legend>
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
            </form>
        </div>
    </div>
    <div class="footer "></div>
</body>

</html>
