<?php

/* PAGINA REGISTRAZIONE */

include_once realpath(dirname(__FILE__, 2))."/lib/php/query_server.php";
include_once realpath(dirname(__FILE__, 2))."/lib/php/start.php";	//LIBRERIA PER CREARE HEAD

function pulisciInput($value) {
	$value=trim($value);
	$value=htmlentities($value);
	$value=strip_tags($value);
	return $value;
}

echo Start::getHead(array(
	'Titolo' => "Registrazione BandBoard",
	'DescrizioneBreve' => "Registrazione - BandBoard",
	'Descrizione' => "Pagina di registrazione del sito BandBoard",
	'Author' => array("Derek Toninato", "Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffre"),
	'Keywords' => array("BandBoard", "registrazione", "iscrizione", "bacheca", "musica", "musicisti", "gruppi", "chitarra", "basso", "batteria", "piano", "tastiera"),
	'BookmarkIcon' => 'site/logo.png',
	'Stylesheets' => array("style.css"),
	'Extra' => array("<link rel=\"stylesheet\" media=\"handheld, screen and (max-width:480px), only screen and (max-device-width:480px)\" href=\"../lib/css/style_mobile.css\" type=\"text/css\" />", "<script type=\"text/javascript\" src=\"registrazione.js\"></script>")
));	//CREAZIONE HEAD

echo file_get_contents(realpath(dirname(__FILE__, 2))."/lib/testiStruttura/header.txt");	//CREAZIONE HEADER

if (count($_REQUEST)==0) {	//APPENA ARRIVATO DA HOME
	echo file_get_contents("registrazione_form.txt");	//CREAZIONE PAGINA DI REGISTRAZIONE
} else {	//E' STATO PREMUTO IL SUBMIT DEL FORM DI REGISTRAZIONE
	echo "<div class=\"report\">";	//APERTURA DIV CONTENTENTE NOTIZIE SULLE OPERAZIONI DELLO SCRIPT
	$registrazione=false;
	try {
		if (file_exists(realpath(dirname(__FILE__, 2))."/lib/php/utente.php")) {	//INCLUSIONE DEL FILE utente.php
			require_once(realpath(dirname(__FILE__, 2))."/lib/php/utente.php");
		} else {
			throw new Exception("File necessario per l'esecuzione mancante.");
		}
		if ((isset($_REQUEST['username'])) && (isset($_REQUEST['password'])) && (isset($_REQUEST['confermaPassword'])) && (isset($_REQUEST['email'])) && (isset($_REQUEST['dataNascita']))) {//CONTROLLO CAMPI COMPILATI
			foreach ($_REQUEST as $chiave => &$valore) {
				$valore=pulisciInput($valore);
			}
			$utente=new Utente($_REQUEST['username'], $_REQUEST['password'], $_REQUEST['confermaPassword'], $_REQUEST['email'], "", "", $_REQUEST['dataNascita'], "", "", "");	//CREAZIONE UTENTE
			if ($utente=="") {
				$utente->save();	//INSERIMENTO NEL DATABASE
				$registrazione=true;
				print "<p class=\"okRep\">Registrazione avvenuta correttamente.</p>";
			} else {
				print "<p class=\"errRep\">I dati inseriti non sono corretti: ".$utente."</p>";
			}
		} else {
			print "<p class=\"errRep\">Compilare tutti i campi.</p>";
		}
	} catch(Exception $e) {
		print "Il sistema è momentaneamente inutilizzabile. Riprova più tardi: (".$e->getMessage().").";
	}
	echo "</div>";	//CHIUSURA DIV

	if ($registrazione==false) {	//REGISTRAZIONE NON ANDATA A BUON FINE -> CREAZIONE DELLA PAGINA DI REGISTRAZIONE
		echo file_get_contents("registrazione_form.txt");
	} else {	//REGISTRAZIONE ANDATA A BUON FINE -> CHIAMATA A PAGINA DI MODIFICA DEL PROFILO
		session_start();	//INIZIO DI UNA SESSIONE
		$_SESSION['started']=1;
		$_SESSION['username']=$utente->getUsername();
		$_SESSION['password']=$utente->getPassword();
		//header("Location: ../modificaProfilo/modificaProfilo.php");	//CHIAMATA A PAGINA DI MODIFICA DEL PROFILO
	}
}
echo file_get_contents(realpath(dirname(__FILE__, 2))."/lib/testiStruttura/footer.txt");	//CREAZIONE FOOTER

?>

