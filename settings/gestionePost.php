<?php

echo "<!--";
echo "\nGET\n";
var_dump($_GET);
echo "\nPOST\n";
var_dump($_POST);
echo "\nSESSION['campiDati']\n";
var_dump($_SESSION['campiDati']);
echo "\nSESSION['nCampi']\n";
var_dump($_SESSION['nCampi']);
echo "-->";

// Fà partire la sessione se non è già partita
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Aggiorna i dati utente dal server
$_SESSION['datiUtente'] = Utenti::getDatiUtente($_SESSION['username']);

// Post è vuoto
if(empty($_POST)) {
    $_SESSION['campiDati'] = $_SESSION['datiUtente'];
}

if(!empty($_POST)) {
    // Get tipoContatto e campoContatto in associative array
    unset($_SESSION['campiDati']['contatti']);
    $i = 0;
    foreach($_POST as $key => $value){
        if(preg_match("/^tipoContatto[0-9]+$/", $key)) {
            $_SESSION['campiDati']['contatti'][$i/2]['tipoContatto'] = $value;
        } else if(preg_match("/^campoContatto[0-9]+$/", $key)) {
            $_SESSION['campiDati']['contatti'][$i/2]['contatto'] = $value;
        }
        $i++;
    }
}

echo "<!-- Dopo get da post\n";
var_dump($_SESSION['campiDati']);
echo "-->";

// Get numero campi da inserire
$_SESSION['nCampi'] = count($_SESSION['campiDati']['contatti']);

if(!empty($_POST)){
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

}

$campiDati = array()


    ?>
