<?php

session_start();

/* PAGINA HOME */

include_once realpath(dirname(__FILE__))."/lib/php/query_server.php";
include_once realpath(dirname(__FILE__))."/lib/php/start.php";	//LIBRERIA PER CREARE HEAD
include_once realpath(dirname(__FILE__))."/lib/php/header.php";	//LIBRERIA PER CREARE HEADER
include_once realpath(dirname(__FILE__))."/lib/php/menu.php";	//LIBRERIA PER CREARE MENU
include_once realpath(dirname(__FILE__))."/lib/php/footer.php";	//LIBRERIA PER CREARE FOOTER

function pulisciInput($value) {
	$value=trim($value);
	$value=htmlentities($value);
	$value=strip_tags($value);
	return $value;
}

function numUtenti() {
	$numero=0;
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT COUNT(username) AS num FROM Utenti;";	//CREAZIONE DELLA QUERY
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					$row=$result->fetch_array(MYSQLI_ASSOC);
					$numero=$row['num'];
				}
				$result->free();
			}
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	} catch (Exception $e){
		echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	return $numero;
}

function numGruppi() {
	$numero=0;
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT COUNT(idGruppo) AS num FROM Gruppi;";	//CREAZIONE DELLA QUERY
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					$row=$result->fetch_array(MYSQLI_ASSOC);
					$numero=$row['num'];
				}
				$result->free();
			}
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	} catch (Exception $e){
		echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	return $numero;
}

function getUtenti() {
	$users="<ul class=\"listaSuggeriti\">";
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT username, nome, cognome, immagine, provincia, dataIscrizione FROM Utenti WHERE username!=\"".$_SESSION['username']."\" ORDER BY dataIscrizione DESC LIMIT 10;";	//CREAZIONE DELLA QUERY
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						if ($row['immagine']==NULL) {
							$row['immagine']="defaultUser.png";		//DA CAMBIARE IN CASO DI MODIFICHE
							$users=$users."<li class=\"elementResult\"><a href=\"profiloUtente/profiloUtente.php?username=".$row['username']."&amp;page=index\"><img class=\"listImage\" src=\"images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['username']."\" /><p>".$row['username']."</p>";	//ATTENZIONE AL PATH! DA CAMBIARE IN CASO DI MODIFICHE
						} else {
							$users=$users."<li class=\"elementResult\"><a href=\"profiloUtente/profiloUtente.php?username=".$row['username']."&amp;page=index\"><img class=\"listImage\" src=\"images/users/".$row['immagine']."\" alt=\"Immagine di ".$row['username']."\" /><p>
							".$row['username'] . "</p>";	//ATTENZIONE AL PATH! DA CAMBIARE IN CASO DI MODIFICHE
						}
						if ($row['nome']!=NULL || $row['cognome']!=NULL) {
							$users=$users."<p>".$row['nome']." ".$row['cognome']."</p>";
						}
						if ($row['provincia']!=NULL) {
							$users=$users."<p>(".$row['provincia'].")</p>";
						}
						$users=$users."</a></li>";
					}
					$result->free();
				}
			}
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	} catch (Exception $e){
		echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	$users=$users."</ul>";
	return $users;
}

function getGruppi() {
	$bands="<ul class=\"listaSuggeriti\">";
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT idGruppo, nome, immagine, provincia, dataIscrizione FROM Gruppi WHERE idGruppo NOT IN (SELECT g.idGruppo FROM Gruppi g JOIN Formazioni f ON g.idGruppo=f.gruppo JOIN Conoscenze c ON f.ruolo=c.idConoscenza WHERE c.utente=\"".$_SESSION['username']."\") ORDER BY dataIscrizione DESC LIMIT 8;";	//CREAZIONE DELLA QUERY
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						if ($row['immagine']==NULL) {
							$row['immagine']="defaultBand.png";		//DA CAMBIARE IN CASO DI MODIFICHE
							$bands=$bands."<li class=\"elementResult\"><a href=\"profiloGruppo/profiloGruppo.php?idGruppo=".$row['idGruppo']."&amp;page=index\"><img class=\"listImage\" src=\"images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" /><p>".$row['nome']."</p>";	//ATTENZIONE AL PATH! DA CAMBIARE IN CASO DI MODIFICHE
						} else {
							$bands=$bands."<li class=\"elementResult\"><a href=\"profiloGruppo/profiloGruppo.php?idGruppo=".$row['idGruppo']."&amp;page=index\"><img class=\"listImage\" src=\"images/bands/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" /><p>".$row['nome']."</p>";	//ATTENZIONE AL PATH! DA CAMBIARE IN CASO DI MODIFICHE
						}
						if ($row['provincia']!=NULL) {
							$bands=$bands." (".$row['provincia'].")";
						}
						$bands=$bands."</a></li>";
					}
					$result->free();
				}
			}
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	} catch (Exception $e){
		echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	$bands=$bands."</ul>";
	return $bands;
}

echo Start::getHead(array(
	'Titolo' => "Home - BandBoard",
	'DescrizioneBreve' => "Home - BandBoard",
	'Descrizione' => "Home page del sito BandBoard",
	'Keywords' => array("BandBoard", "home", "bacheca", "musica", "musicisti", "gruppi", "chitarra", "basso", "batteria", "piano", "tastiera"),
	'Stylesheets' => array("style.css"),
	'Extra' => array("<link rel=\"stylesheet\" media=\"handheld, screen and (max-width:480px), only screen and (max-device-width:480px)\" href=\"lib/css/style_mobile.css\" type=\"text/css\" />")
));	//CREAZIONE HEAD

echo '<body>';
echo Header::getHeader(); //CREAZIONE HEADER

if (!isset($_SESSION['started'])) {	//APPENA ARRIVATO IN HOME
	$_SESSION['started']=1;
	$page=file_get_contents(realpath(dirname(__FILE__))."/home/homeNLog.txt");	//CREAZIONE PAGINA HOME PER UTENTE NON LOGGATO
	$page=str_replace('<scriptNumUtenti />', numUtenti(), $page);
	$page=str_replace('<scriptNumGruppi />', numGruppi(), $page);
	echo $page;
} else {
	if(count($_REQUEST)!=0 && !isset($_SESSION['username'])) {	//INVIATI DEI DATI E TENTATIVO DI EFFETTUARE IL LOGIN
		echo "<div class=\"report\">";	//APERTURA DIV CONTENTENTE NOTIZIE SULLE OPERAZIONI DELLO SCRIPT
		$login=false;
		try {
			if (file_exists(realpath(dirname(__FILE__))."/lib/php/utente.php")) {	//INCLUSIONE DEL FILE utente.php
				require_once(realpath(dirname(__FILE__))."/lib/php/utente.php");
			} else {
				throw new Exception("File necessario per l'esecuzione mancante.");
			}
			if ((isset($_REQUEST['username'])) && (isset($_REQUEST['password']))) {	//CONTROLLO CAMPI COMPILATI
				foreach ($_REQUEST as $chiave => &$valore) {
					$valore=pulisciInput($valore);
				}
				$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
				try {
					if ($connessione->connect_errno) {
						throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
					} else {
						$password=Utente::cript($_REQUEST['password']);
						$query="SELECT * FROM Utenti WHERE Utenti.username=\"".$_REQUEST['username']."\" AND Utenti.password=\"".$password."\";";	//CREAZIONE DELLA QUERY
						if (!$result=$connessione->query($query)) {
							echo "Query non valida: ".$connessione->error.".";
						} else {
							if ($result->num_rows>0) {	//UTENTE TROVATO
								$login=true;
								$row=$result->fetch_array(MYSQLI_ASSOC);
								$_SESSION['username']=$row['username'];
								$_SESSION['password']=$row['password'];
								$result->free();
							}
						}
						$connessione->close();	//CHIUSURA CONNESSIONE
					}
				} catch (Exception $e){
					echo "Errore: login fallito (".$e->getMessage().").";
				}
				if ($login==false) {	//LOGIN NON EFFETTUATO CON SUCCESSO
					print "<p class=\"errRep\">I dati inseriti non sono corretti.</p>";
				}
			} else {
				print "<p class=\"errRep\">I dati inseriti non sono corretti.</p>";
			}
		} catch(Exception $e) {
			print "Il sistema è momentaneamente inutilizzabile. Riprova più tardi: (".$e->getMessage().").";
		}
		echo "</div>";	//CHIUSURA DIV

		if ($login==false) {	//LOGIN NON ANDATO A BUON FINE -> CREAZIONE DELLA PAGINA DI HOME PER UTENTE NON LOGGATO
			$page=file_get_contents(realpath(dirname(__FILE__))."/home/homeNLog.txt");
			$page=str_replace('<scriptNumUtenti />', numUtenti(), $page);
			$page=str_replace('<scriptNumGruppi />', numGruppi(), $page);
			echo $page;
		} else {	//LOGIN ANDATO A BUON FINE -> CREAZIONE DELLA PAGINA DI HOME PER UTENTE LOGGATO
			$page="";
			$page=$page."<div class=\"nav\">".Menu::getMenu(array("Home", "<a href='settings/index.php'>Modifica Profilo</a>", "<a href='cercaUtenti/index.php'>Cerca Utenti</a>", "<a href='cercaGruppi/index.php'>Cerca Gruppi</a>", "<a href='gestioneGruppi/gestioneGruppi.php'>I Miei Gruppi</a>", "<a href='registrazioneGruppo/registrazioneGruppo.php'>Nuovo Gruppo</a>"))."</div>";//!!!!!!!!!!!!!!!!!!!!!DA TOGLIERE LINK A REGISTRAZIONE GRUPPO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!	//CREAZIONE DEL MENU
			$page=$page.file_get_contents(realpath(dirname(__FILE__))."/home/homeLog.txt");
			$page=str_replace('<utentiSuggeriti />', getUtenti(), $page);
			$page=str_replace('<gruppiSuggeriti />', getGruppi(), $page);
			echo $page;
		}
	} else {
		if (isset($_SESSION['username'])) {
			$page="";
			$page=$page."<div class=\"nav\">".Menu::getMenu(array("Home", "<a href='settings/index.php'>Modifica Profilo</a>", "<a href='cercaUtenti/index.php'>Cerca Utenti</a>", "<a href='cercaGruppi/index.php'>Cerca Gruppi</a>", "<a href='gestioneGruppi/gestioneGruppi.php'>I Miei Gruppi</a>", "<a href='registrazioneGruppo/registrazioneGruppo.php'>Nuovo Gruppo</a>"))."</div>";//!!!!!!!!!!!!!!!!!!!!!DA TOGLIERE LINK A REGISTRAZIONE GRUPPO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!	//CREAZIONE DEL MENU
			$page=$page.file_get_contents(realpath(dirname(__FILE__))."/home/homeLog.txt");
			$page=str_replace('<utentiSuggeriti />', getUtenti(), $page);
			$page=str_replace('<gruppiSuggeriti />', getGruppi(), $page);
			echo $page;
		} else {
			$page=file_get_contents(realpath(dirname(__FILE__))."/home/homeNLog.txt");
			$page=str_replace('<scriptNumUtenti />', numUtenti(), $page);
			$page=str_replace('<scriptNumGruppi />', numGruppi(), $page);
			echo $page;
		}
	}
}

echo Footer::getFooter();	//CREAZIONE DEL FOOTER
echo '</body>';
echo '</html>';

?>
