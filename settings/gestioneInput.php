<?php


/**
 *
 */
class GestioneInput
{
    //TODO richiede che nella sessione sia salvato $_SESSION['username'] per l'identificazione
    public static function doGestioneInput()
    {

        // Fà partire la sessione se non è già partita
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // check username
        if (!isset($_SESSION['username'])) {
            throw new Exception("Missing username in SESSION", 1);
        }


        // Post è vuoto
        if (empty($_POST)) {
            // Svuoto l'array
            unset($_SESSION['campiDati']);

            // Prendo i dati per i  campi dal DB
            $_SESSION['campiDati'] = Utenti::getDatiUtente($_SESSION['username']);
            $_SESSION['nCampi'] = count($_SESSION['campiDati']['contatti']);
            // Tolgo password cifrata dai dati
            unset($_SESSION['campiDati']['password']);

        }

        if (!empty($_POST)) {
            // Prendo i dati per i campi da post
            unset($_SESSION['campiDati']);

            $dati = array();

            // Username
            $dati['username'] = $_SESSION['username'];

            // Password
            if (isset($_POST['password']) || $_POST['passwordCheck']) {
                $dati['password'] = isset($_POST['password']) ? $_POST['password'] : "";
                $dati['passwordCheck'] = isset($_POST['passwordCheck']) ? $_POST['passwordCheck'] : "";
            }

            // Nome
            if (isset($_POST['nome'])) {
                $dati['nome'] = $_POST['nome'];
            }

            // Cognome
            if (isset($_POST['cognome'])) {
                $dati['cognome'] = $_POST['cognome'];
            }

            // Email
            if (isset($_POST['email'])) {
                $dati['email'] = $_POST['email'];
            }

            // bDay
            $dati['dataNascita'] = (isset($_POST['bDayAnno']) ? $_POST['bDayAnno'] : "") . "/" . (isset($_POST['bDayMese']) ? $_POST['bDayMese'] : "") . "/" . (isset($_POST['bDayGiorno']) ? $_POST['bDayGiorno'] : "");

            // image
            if (isset($_POST['image'])) {
                $dati['image'] = $_POST['image'];
            }

            // Regione e provincia
            if (isset($_POST['selectRegione']) && isset($_POST['selectProvincia'])) {
                $dati['selectRegione'] = $_POST['selectRegione'];
                $dati['selectProvincia'] = $_POST['selectProvincia'];
            }

            // Bio
            if (isset($_POST['bio'])) {
                $dati['bio'] = $_POST['bio'];
            }

            // Tipicontatto e Contatti
            $arrayTipiContatto = array_values(
                array_filter(
                    $_POST, function ($a) {
                    return preg_match("/^tipoContatto[0-9]+?/", $a);
                }, ARRAY_FILTER_USE_KEY
                )
            );
            $arrayCampiContatto = array_values(
                array_filter(
                    $_POST, function ($a) {
                    return preg_match("/^campoContatto[0-9]+?/", $a);
                }, ARRAY_FILTER_USE_KEY
                )
            );

            // Controllo lunghezza uguale
            if (count($arrayTipiContatto) != count($arrayCampiContatto)) {
                throw new Exception("Lunghezze degli array di contatti non uguali", 1);
            }

            $dati['contatti'] = array_fill(0, count($arrayTipiContatto), array('tipoContatto' => "", 'contatto' => ""));
            for ($i = 0; $i < count($arrayTipiContatto); $i++) {
                $dati['contatti'][$i]['tipoContatto'] = $arrayTipiContatto[$i];
                $dati['contatti'][$i]['contatto'] = $arrayCampiContatto[$i];
            }

            // Rimozione campo
            if (isset($_POST['rimuoviCampo'])) {
                unset($dati['contatti'][(int)$_POST['rimuoviCampo']]);
                $dati['contatti'] = array_values($dati['contatti']);
            }

            //TODO
            // Controlli

            // Rimozione di password dai dati
            unset($dati['password']);
            unset($dati['passwordCheck']);

            $_SESSION['nCampi'] = count($dati['contatti']);
            $_SESSION['campiDati'] = $dati;

        }

        $_SESSION['nCampi'] += (isset($_POST['aggiungiCampo']) ? 1 : 0) + (isset($_POST['rimuoviCampo']) && isset($dati['contatti']) && $_POST['rimuoviCampo'] > count($dati['contatti']) ? -1 : 0);
    }
}
