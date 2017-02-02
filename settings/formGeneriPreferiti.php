<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/preferenzeUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/generiMusicali.php";
require_once realpath(dirname(__FILE__)) . "/aggiornamentoDB.php";

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormGeneriPreferiti
{
    public static function getFormGeneriPreferiti()
    {

        $generi = GeneriMusicali::getGeneriMusicali();

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

            $dati = array();
            $dati['generi'] = array();

            $dati['username'] = $_SESSION['username'];

            try {

                //key tipo:     "generePsychedelic"
                //Value tipo:   "on"

                foreach ($_POST as $KEY => $_POSTElement) {
                    $target = preg_replace("/^genere/", "", $KEY);      //Tolgo genere dall'inizio della stringa
                    $target = preg_replace("/^R_B$/", "R&B", $target);  //Sistemo caso di R&B
                    $target = preg_replace("/_/", " ", $target);        //Trasformo _ in spazi (Hard_Rock -> Hard Rock)

                    if (in_array($target, $generi)) {
                        array_push($dati['generi'], $target);
                    } else {
                        throw new Exception("Invalid input");
                    }
                }

            } catch (Exception $e) {
                switch ($e) {
                    case "Invalid input":
                        error_log("Genere musicale non riconosciuto");
                        array_push($errori, "Non tutti i generi musicali scelti sono validi");
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

        //Lettura dati dal DB
        $dati = Utenti::getDatiUtente($_SESSION['username']);

        if ($dati == null) {
            session_unset();
            session_destroy();
            throw new Exception("Invalid session");
        }

        $preferenze = PreferenzeUtente::getPreferenze($_SESSION['username']);

        $string = "";

        if (!empty($errori)) {
            $string .= "<div id='modErroritrovati'><ul>";

            foreach ($errori as $errore) {
                $string .= "<li>" . $errore . "</li>";
            }

            $string .= "</ul></div>";
        }

        //Costruzione contenuto pagina
        $string .= "<form action='generiPreferiti.php' method='post'><fieldset><legend>Generi preferiti</legend><p>Lista dei generi musicali che ti piacciono.</p><ul>";

        foreach ($generi as $key => $genere) {
            $string .= "<li><input id='modGenere" . htmlentities(preg_replace("/\s|\&/", "_", $genere), ENT_QUOTES, "UTF-8") . "' title='Seleziona se ti piace il genere " . htmlentities($genere, ENT_QUOTES, "UTF-8") . "' name='genere" . htmlentities(preg_replace("/\s|\&/", "_", $genere), ENT_QUOTES, "UTF-8") . "' type='checkbox'" . (in_array($genere, $preferenze) ? " checked='checked'" : "") . "/><label for='modGenere" . htmlentities(preg_replace("/\s|\&/", "_", $genere)) . "'>" . htmlentities($genere, ENT_QUOTES, "UTF-8") . "</label></li>";
        }

        $string .= "</ul><button type='submit'>Salva</button></fieldset></form>";

        "</fieldset>";

        return $string;
    }
}
