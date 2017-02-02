<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/utente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/contattiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/tipiContatto.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/check_email.php";
require_once realpath(dirname(__FILE__)) . "/aggiornamentoDB.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/regioni.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/province.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/select_regione.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/paths.php";

session_start();

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormContatti
{

    /**
     * @return string stringa della form
     * @throws Exception lancia un eccezione se la sessione non è valida
     */
    public static function getFormContatti()
    {

        $errori = array();

        // Controllo sessione attiva
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Controllo sessione valida
        if (!isset($_SESSION['username'])) {
            session_unset();
            session_destroy();
            throw new Exception("Invalid session");
        }

        //Gestione input con post

        if (!empty($_POST)) {

            // Test input
            $dati = array();

            $dati['username'] = $_SESSION['username'];

            $contatti = ContattiUtente::getContattiUtente($_SESSION['username']);

            // Aggiungi contatto
            try {
                if (!isset($_POST['aggiungiContatto'])) {
                    throw new Exception("Nothing to do");
                }

                if ($_POST['aggiungiContatto'] == "true") {

                    if (isset($_SESSION['numeroContatti'])) {
                        $_SESSION['numeroContatti'] += 1;
                    } else {
                        $_SESSION['numeroContatti'] = count($contatti) + 1;
                    }

                }

            } catch (Exception $e) {
                switch ($e->getMessage()) {
                    case "Nothing to do":
                        //DO NOTHING
                        break;
                    default:
                        throw $e;
                        break;
                }
            }

            // Elimina contatto
            try {

                if (!isset($_POST['eliminaContatto'])) {
                    throw new Exception("Nothing to do");
                }

                if (
                    (isset($_SESSION['numeroContatti']) && $_POST['eliminaContatto'] > $_SESSION['numeroContatti']) ||
                    (!isset($_SESSION['numeroContatti']) && $_POST['eliminaContatto'] > count($contatti)) ||
                    $_POST['eliminaContatto'] < 1
                ) {
                    throw new Exception("Invalid input");
                }

                if ($_POST['eliminaContatto'] <= count($contatti)) {
                    $dati['contatti']['rimuovi'] = $contatti[$_POST['eliminaContatto'] - 1];

                    $_SESSION['numeroContatti'] = count(ContattiUtente::getContattiUtente($dati['username'])) - 1;
                } else {
                    $_SESSION['numeroContatti'] -= 1;
                }


            } catch (Exception $e) {
                switch ($e->getMessage()) {
                    case "Nothing to do":
                        //DO NOTHING
                        break;
                    case "Invalid input":
                        array_push($errori, "Indice di elimina contatto impossibile.");
                        break;
                    default:
                        throw $e;
                        break;
                }
            }

            // Salva
            try {

                if (!isset($_POST['salva']) && !isset($_POST['aggiungiContatto'])) {
                    throw new Exception("Nothing to do");
                }

                if (
                    (isset($_POST['salva']) && $_POST['salva'] == "true") ||
                    (isset($_POST['aggiungiContatto']) && $_POST['aggiungiContatto'] == "true")
                ) {

                    $indiciContattiValidi = array_filter(
                        array_unique(
                            preg_replace(
                                "/^(contatto)/",
                                "",
                                array_keys(
                                    array_filter(
                                        $_POST,
                                        function ($var) {
                                            return preg_match("/^(contatto)[\d]+$/", $var);
                                        }, ARRAY_FILTER_USE_KEY)
                                )
                            )
                        ),
                        function ($var) {
                            return (isset($_POST["contatto$var"]) && $_POST["contatto$var"] != "");
                        }
                    );

                    $dati['contatti']['inserisci'] = array();

                    foreach ($indiciContattiValidi as $indice) {

                        $tipoContatto = (isset($_POST["tipoContatto$indice"])) ? $_POST["tipoContatto$indice"] : null;

                        $contatto = (isset($_POST["contatto$indice"])) ? $_POST["contatto$indice"] : null;

                        $contatto = trim($contatto);

                        if ($tipoContatto != "" && $contatto != "") {
                            switch ($tipoContatto) {
                                case "email_pubblica":
                                    if (Email::checkEmail($contatto) == 0) {
                                        array_push($dati['contatti']['inserisci'], array("tipoContatto" => $tipoContatto, "contatto" => $contatto));
                                    } else {
                                        throw new Exception("Invalid mail");
                                    }
                                    break;
                                default:
                                    array_push($dati['contatti']['inserisci'], array("tipoContatto" => $tipoContatto, "contatto" => $contatto));
                                    break;
                            }
                        }
                    }

                    $dati['contatti']['inserisci'] = array_unique($dati['contatti']['inserisci'], SORT_REGULAR);

                } else {
                    throw new Exception("Invalid input");
                }

            } catch
            (Exception $e) {
                switch ($e->getMessage()) {
                    case "Nothing to do":
                        //DO NOTHING
                        break;
                    case "Invalid input":
                        array_push($errori, "Gli indici inseriti non sono validi.");
                        break;
                    case "Invalid mail":
                        array_push($errori, "Una mail inserita non è valida.");
                        break;
                    default:
                        throw $e;
                        break;
                }
            }

            // Update DB
            if (empty($errori)) {
                try {
                    if (AggiornamentoDB::aggiornaDatiDB($dati)) {
//                        echo "Aggiornamento riuscito";
                    } else {
//                        echo "Aggiornamento fallito";
                        throw new Exception("Failed update");
                    }
                } catch (Exception $e) {
                    switch ($e->getMessage()) {
                        case "Failed update":
                            array_push($errori, "Qualcosa non ha funzionato. Riprova più tardi");
                            break;
                        default:
                            throw $e;
                            break;
                    }
                }
            }
        }

        $dati = Utenti::getDatiUtente($_SESSION['username']);

        if ($dati == null) {
            session_unset();
            session_destroy();
            throw new Exception("Invalid session");
        }

        $string = "";

        if (!empty($errori)) {
            $string .= "<div id='modErroritrovati'><ul>";

            foreach ($errori as $errore) {
                $string .= "<li>" . $errore . "</li>";
            }

            $string .= "</ul></div>";
        }

        //Lettura dati dal DB
        $contatti = ContattiUtente::getContattiUtente($_SESSION['username']);
        $tipiContatti = TipiContatto::getTipiContatto();
        $numeroContatti = (isset($_SESSION['numeroContatti'])) ? $_SESSION['numeroContatti'] : count($contatti);


        $contatti = array_merge($contatti, array_fill(count($contatti), $numeroContatti - count($contatti), array("tipoContatto" => "", "contatto" => "")));

        //Costruzione contenuto pagina
        $string .= "<form action='contatti.php' method='post' onsubmit='return checkForm()'><fieldset><legend>Contatti</legend><ul>";

        foreach ($contatti as $key => $contatto) {

            $string .= "<li><label for='modContatto" . ($key + 1) . "'>Contatto " . ($key + 1) . ":</label><input id='modContatto" . ($key + 1) . "' name='contatto" . ($key + 1) . "' value='" . $contatto['contatto'] . "' title='Campo di testo del contatto " . ($key + 1) . "'/><label for='modTipoContatto" . ($key + 1) . "'> - tipologia:</label> <select id='modTipoContatto" . ($key + 1) . "' name='tipoContatto" . ($key + 1) . "' title='Tipo del contatto " . ($key + 1) . "'>";

            foreach ($tipiContatti as $keySel => $tipoContatto) {
                $string .= "<option value='$tipoContatto'";

                if ($tipoContatto == $contatto['tipoContatto']) {
                    $string .= " selected";
                }

                $string .= ">" . ucfirst(preg_replace("/_/", " ", $tipoContatto)) . "</option>";
            }

            $string .= "</select>";

            $string .= "<button id='modEliminaContatto" . ($key + 1) . "' name='eliminaContatto' value='" . ($key + 1) . "' title='Elimina il contatto " . ($key + 1) . "'>Elimina contatto</button>";

            $string .= "</li>";

        }

        $string .= "<li><button id='modAggiungiContato' name='aggiungiContatto' value='true'>Aggiungi contatto</button> </li>";

        $string .= "</ul><button name='salva' value='true' type='submit'>Salva</button></fieldset></form>";

        return $string;
    }

}
