<?php

/* PAGINA VISUALIZZAZIONE DI UN GRUPPO */
	session_start();
	
	include_once realpath(dirname(__FILE__, 2))."/lib/php/query_server.php";
	include_once realpath(dirname(__FILE__, 2))."/lib/php/start.php";	//LIBRERIA PER CREARE HEAD
	include_once realpath(dirname(__FILE__, 2))."/lib/php/header.php";	//LIBRERIA PER CREARE HEADER
	include_once realpath(dirname(__FILE__, 2))."/lib/php/menu.php";	//LIBRERIA PER CREARE MENU
	include_once realpath(dirname(__FILE__, 2))."/lib/php/footer.php";	//LIBRERIA PER CREARE FOOTER
	
	$page="";
	$page=$page.Start::getHead(array(
	'Titolo' => "BandBoard",
	'DescrizioneBreve' => "Profilo Gruppo - BandBoard",
	'Descrizione' => "Pagina di visualizzazione di un gruppo del sito BandBoard",
	'Author' => array("Derek Toninato", "Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffre"),
	'Keywords' => array("BandBoard", "profilo gruppo", "gruppo", "band", "bacheca", "musica", "musicisti", "gruppi", "chitarra", "basso", "batteria", "piano", "tastiera"),
	'BookmarkIcon' => 'site/logo.png',
	'Stylesheets' => array("style.css"),
	'Extra' => array("<link rel=\"stylesheet\" media=\"handheld, screen and (max-width:480px), only screen and (max-device-width:480px)\" href=\"lib/css/style_mobile.css\" type=\"text/css\" />")
));	//CREAZIONE HEAD
    $page=$page."<body>";
	$page=$page.Header::getHeader();
	if (isset($_SESSION['username'])) {	//UTENTE LOGGATO
		$logout="<div class=\"logout\">
	                <form action=\"../lib/php/logout.php\" method=\"post\">
    	                <p><input type=\"submit\" id=\"logout\" value=\"Logout\" /></p>
	                </form>
                </div>";
		$page=$page.$logout;
		$page=$page."<div class=\"nav\">".Menu::getMenu(array("<a href='../index.php'>Home</a>", "<a href='../settings/index.php'>Modifica Profilo</a>", "<a href='../cercaUtenti/index.php'>Cerca Utenti</a>", "<a href='../cercaGruppi/index.php'>Cerca Gruppi</a>", "<a href='../gestioneBand/gestioneBand.php'>I Miei Gruppi</a>"))."</div>";	//CREAZIONE DEL MENU PER UTENTE LOGGATO
	} else {
		session_unset();
		session_destroy();
		$page=$page."<div class=\"nav\">".Menu::getMenu(array("<a href='../index.php'>Home</a>", "<a href='../cercaUtenti/index.php'>Cerca Utenti</a>", "<a href='../cercaGruppi/index.php'>Cerca Gruppi</a>"))."</div>";	//CREAZIONE DEL MENU PER UTENTE NON LOGGATO
	}
	$page=$page.file_get_contents(realpath(dirname(__FILE__))."/profiloGruppo.txt");
	
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT idGruppo, nome, immagine, descrizione, provincia, dataIscrizione FROM Gruppi WHERE idGruppo=\"".$_REQUEST["idGruppo"]."\";";	//CREAZIONE DELLA QUERY PER PRIMI DATI
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						if ($row['immagine']==NULL) {
							$row['immagine']="defaultBand.png";
							$img="<img id=\"fotoprofilo\" src=\"../images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" />";
						} else {
							$img="<img id=\"fotoprofilo\" src=\"../images/bands/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" />";
						}
						$page=str_replace("<immagineProfilo />", $img, $page);
						$page=str_replace("<nome />", $row['nome'], $page);
						$page=str_replace("<provincia />", $row['provincia'], $page);
						$page=str_replace("<dataIscrizione />", substr($row['dataIscrizione'], 0, 10), $page);
						$page=str_replace("<descrizione />", $row['descrizione'], $page);
					}
					$result->free();
				}
			}
			$query="SELECT gruppo, genere FROM GeneriGruppi WHERE gruppo=\"".$_REQUEST["idGruppo"]."\";";	//CREAZIONE DELLA QUERY PER I GENERI
			$gen="";
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
				    $gen="<ul>";
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						$gen=$gen."<li>".$row['genere']."</li>";
					}
					$result->free();
					$gen=$gen."</ul>";
				}
			}
			$page=str_replace("<generi />", $gen, $page);
			$query="SELECT gruppo, utente, strumento FROM Formazioni JOIN Conoscenze ON ruolo=idConoscenza WHERE gruppo=\"".$_REQUEST["idGruppo"]."\";";	//CREAZIONE DELLA QUERY PER I MEMBRI
			$members="";
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
				    $members="<ul>";
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						$members=$members."<li>".$row['utente']." - ".$row['strumento']."</li>";
					}
					$result->free();
					$members=$members."</ul>";
				}
			}
			$page=str_replace("<membri />", $members, $page);
			$query="SELECT gruppo, tipoContatto, contatto FROM ContattiGruppi WHERE gruppo=\"".$_REQUEST["idGruppo"]."\" ORDER BY tipoContatto;";	//CREAZIONE DELLA QUERY PER I CONTATTI
			$contacts="";
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
				    $contacts="<ul>";
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						$contacts=$contacts."<li><a href=\"".$row['contatto']."\">".$row['tipoContatto']."</a></li>";
					}
					$result->free();
					$contacts=$contacts."</ul>";
				}
			}
			$page=str_replace("<contatti />", $contacts, $page);
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	} catch (Exception $e){
		echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	if ($_REQUEST['page']=="index") {
		$precPage="<p class=\"paginaPrec\"><a href=\"../index.php\" id=\"torna\">Torna alla Home</a></p>";
	} else {
		if ($_REQUEST['page']=="ricerca") {
			$precPage="<p class=\"paginaPrec\"><a href=\"../cercaGruppi/index.php?num=\"".$_REQUEST['num']."\" id=\"torna\">Torna alla Ricerca</a></p>";
		}
	}
	$page=str_replace("<pagPrec />", $precPage, $page);
	$page=str_replace("<footer />", Footer::getFooter(), $page);
	$page=$page."</body></html>";
	echo $page;
?>

