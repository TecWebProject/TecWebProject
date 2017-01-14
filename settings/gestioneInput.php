<?php


    /**
     *
     */
class GestioneInput
{
    //TODO richiede che nella sessione sia salvato $_SESSION['username'] per l'identificazione
    public static function doGestioneInput()
    {
        if(!isset($_SESSION['username'])) {
               throw new Exception("Missing username record", 1);
        }

        echo "<!-- \nSTATO INIZIALE \n";
        var_dump("GET",$_GET,"POST",$_POST,"campiDati",$_SESSION['campiDati'],"nCampi",$_SESSION['nCampi']);
        echo "-->\n\n";

        // Fà partire la sessione se non è già partita
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        # $_SESSION['username'] è fondamentale per ottenere i dati dell'utente
        if(!isset($_SESSION['username'])) {
            throw new Exception("Missing username in SESSION", 1);
        }

        // Aggiorna i dati utente dal server
        //   $_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);

        // Post è vuoto
        if(empty($_POST)) {
            $_SESSION['campiDati'] = Utenti::getDatiUtente($_SESSION['username']);

            echo "<!-- Aggiornati campi dati da DB\n";
            var_dump($_SESSION['campiDati']);
            echo "-->";
        }

        if(!empty($_POST)) {
            // Get tipoContatto e campoContatto in associative array
            //unset($_SESSION['campiDati']);

            //TODO MODIFICA PASSWORD
            //FIX temporaneo
            $datiDB = Utenti::getDatiUtente($_SESSION['username']);

            $campiDati = array(
               'username' => $_SESSION['username'],
               'password' => $datiDB['password'],
               'email' => (isset($_POST['']))
            );
        }

        // Get numero campi da inserire
       /* $_SESSION['nCampi'] = count($_SESSION['campiDati']['contatti']);

        if(!empty($_POST)) {
            // Check if new campoDati is needed
            if(isset($_POST['aggiungiCampo']) && $_POST['aggiungiCampo'] == "true") {
                $_SESSION['nCampi'] += 1;
            }

            if(isset($_POST['rimuoviCampo'])) {

                echo "Rimuovo campo ".$_POST['rimuoviCampo'];

                $key = $_POST['rimuoviCampo'];
                unset($_SESSION['campiDati'][$key]);
                $_SESSION['campiDati'] = array_values($_SESSION['campiDati']);
                $_SESSION['nCampi'] -= 1;
            }

        }*/

        $campiDati = array();
    }
}






    ?>
