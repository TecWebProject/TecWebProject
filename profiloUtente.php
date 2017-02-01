<?php
	session_start();
	
	include_once realpath(dirname(__FILE__, 2))."/lib/php/query_server.php";
	include_once realpath(dirname(__FILE__, 2))."/lib/php/start.php";	//LIBRERIA PER CREARE HEAD
	include_once realpath(dirname(__FILE__, 2))."/lib/php/header.php";	//LIBRERIA PER CREARE HEADER
	include_once realpath(dirname(__FILE__, 2))."/lib/php/menu.php";	//LIBRERIA PER CREARE MENU
	include_once realpath(dirname(__FILE__, 2))."/lib/php/footer.php";	//LIBRERIA PER CREARE FOOTER
	
	$page="";
	$page=$page.Start::getHead(array(
	'Titolo' => "BandBoard",
	'DescrizioneBreve' => "Profilo Utente - BandBoard",
	'Descrizione' => "Pagina di visualizzazione di un utente del sito BandBoard",
	'Author' => array("Derek Toninato", "Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffre"),
	'Keywords' => array("BandBoard", "profilo utente", "gruppo", "band", "bacheca", "musica", "musicisti", "gruppi", "chitarra", "basso", "batteria", "piano", "tastiera"),
	'BookmarkIcon' => 'site/logo.png',
	'Stylesheets' => array("style.css"),
	'Extra' => array("<link rel=\"stylesheet\" media=\"handheld, screen and (max-width:480px), only screen and (max-device-width:480px)\" href=\"lib/css/style_mobile.css\" type=\"text/css\" />")
));	//CREAZIONE HEAD
	$page=$page.Header::getHeader();
	if (isset($_SESSION['username'])) {	//UTENTE LOGGATO
		$logout="<div class=\"logout\">
	                <form action=\"../lib/php/logout.php\" method=\"post\">
    	                <p><input type=\"submit\" id=\"logout\" value=\"Logout\" /></p>
	                </form>
                </div>";
		$page=$page.$logout;
		$page=$page.Menu::getMenu(array("<a href='../index.php'>Home</a>", "<a href='modificaProfilo/modificaProfilo.php'>Modifica Profilo</a>", "<a href='cercaUtenti/cercaUtenti.php'>Cerca Utenti</a>", "<a href='cercaGruppi/cercaGruppi.php'>Cerca Gruppi</a>", "<a href='gestioneBand/gestioneBand.php'>I Miei Gruppi</a>"));	//CREAZIONE DEL MENU PER UTENTE LOGGATO
	} else {
		session_unset();
		session_destroy();
		$page=$page.Menu::getMenu(array("<a href='../index.php'>Home</a>", "<a href='../cercaUtenti/cercaUtenti.php'>Cerca Utenti</a>", "<a href='../cercaGruppi/cercaGruppi.php'>Cerca Gruppi</a>"));	//CREAZIONE DEL MENU PER UTENTE NON LOGGATO
	}
	$page=$page.file_get_contents(realpath(dirname(__FILE__))."/profiloUtente.txt");
	
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT username, nome, cognome, dataNascita, immagine, descrizione, provincia, dataIscrizione, email FROM Utenti WHERE username=\"".$_REQUEST["username"]."\";";
			//restituisce le informazioni riguardanti l'utente
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						if ($row['immagine']==NULL) {
							$row['immagine']="defaultUtente.png";
							$img="<img id=\"fotoprofilo\"' src=\"../images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" />";
						} else {
							$img="<img id=\"fotoprofilo\"' src=\"../images/bands/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" />";
						}
						$page=str_replace("<email />", $row['email'], $page);
						$page=str_replace("<nickname />", $row['username'], $page);
						$page=str_replace("<immagineProfilo />", $img, $page);
						$page=str_replace("<nome />", $row['nome'], $page);
						$page=str_replace("<cognome />", $row['cognome'], $page);
						$page=str_replace("<dataIscrizione />", substr($row['dataIscrizione'], 0, 10), $page);
						$page=str_replace("<dataNascita />", $row['dataNascita'], $page);
						$page=str_replace("<provincia />", $row['provincia'], $page);
						$page=str_replace("<descrizione />", $row['descrizione'], $page);
					}
					$result->free();
				}
			}
			//trova i generi apprezzati dall'utente
			$query="SELECT GM.nome FROM Utenti U JOIN GeneriUtenti GU ON U.username=GU.utente JOIN GeneriMusicali GM ON GU.genere=GM.nome WHERE U.username=\"".$_REQUEST["username"]."\";";
			$gen="";
			if (!$result=$connessione->query($query)) {
					echo "Query non valida: ".$connessione->error.".";
			} else {
					if ($result->num_rows>0) {
						$gen="<ul>";
							while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
							$gen=$gen."<li>".$row['nome']."</li>";
							}
							$result->free();
							$gen=$gen."</ul>"; //crea la lista dei generi
					}
			}
			$page=str_replace("<generiApprezzati />", $gen, $page); //stampa la lista dei generi in $page
			
			//trova gli strumenti suonati dall'utente
			$query="SELECT C.strumento FROM Utenti U JOIN Conoscenze C ON U.username=C.utente JOIN Strumenti S ON S.nome=C.strumento WHERE U.username=\"".$_REQUEST["username"]."\";";
			
			$strumenti="";
			if (!$result=$connessione->query($query)) {
					echo "Query non valida: ".$connessione->error.".";
			} else {
					if ($result->num_rows>0) {
	
						$strumenti="<ul>";
						while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						$strumenti=$strumenti."<li>".$row['strumento']."</li>";
						}
						$result->free();
						$strumenti=$strumenti."</ul>";
					}
			}
			$page=str_replace("<strumentiSuonati />", $strumenti, $page);
			
			//trova i gruppi ai quali appartiene l'utente
			$query="SELECT G.nome, G.immagine FROM Conoscenze C JOIN Formazioni F ON C.idConoscenza=F.ruolo JOIN Gruppi G ON F.gruppo=G.idGruppo WHERE C.utente=\"".$_REQUEST["username"]."\";";
			$gruppi="";
			if (!$result=$connessione->query($query)) {
					echo "Query non valida: ".$connessione->error.".";
			} else {
					if ($result->num_rows>0) { //se appartiene a 1 o + gruppi stampa immagine e gruppo
						$gruppi="<ul>";
							while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
								if ($row['immagine']==NULL) {
										$row['immagine']="defaultBand.png";
										$img="<img id=\"fotoprofilo\"' src=\"../images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" />";
								} else {
										$img="<img id=\"fotoprofilo\"' src=\"../images/bands/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" />";
								}						
								$gruppi=$gruppi."<li>".$row['nome']."</li>";
							}
							$result->free();
							$gruppi=$gruppi."</ul>";
							$page=str_replace("<immaginiGruppi />", $img, $page);
					}
					else { //altrimenti stampa
						$gruppi=$gruppi."<p>Non è membro di nessuna band.</p>";}
			}
			$page=str_replace("<nomeGruppi />", $gruppi, $page);
			
			//cerca e stampa i contatti utente (è impossibile che <ul> sia vuoto perchè il campo mail è NOT NULL)
			$query="SELECT utente, tipoContatto, contatto FROM ContattiUtenti WHERE utente=\"".$_REQUEST["username"]."\";";
			if (!$result=$connessione->query($query)) {
					echo "Query non valida: ".$connessione->error.".";
			} else {
					$contacts="";
					if ($result->num_rows>0) {
							while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
									$contacts=$contacts."<li><a href=\"".$row['contatto']."\">".$row['tipoContatto']."</a></li>";
							}
							$result->free();
					}
			}
			$page=str_replace("<contatti />", $contacts, $page);
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	}catch (Exception $e){
			echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	if ($_REQUEST['page']=="index") {
			$precPage="<p class=\"paginaPrec\"><a href=\"../index.php\" id=\"torna\">Torna alla Home</a></p>";
	} else {
			if ($_REQUEST['page']=="ricerca") {
					$precPage="<p class=\"paginaPrec\"><a href=\"../cercaUtenti/index.php?num=\"".$_REQUEST['num']."\" id=\"torna\">Torna alla Ricerca</a></p>";
			}
	}
	$page=str_replace("<pagPrec />", $precPage, $page);
	$page=str_replace("<footer />", Footer::getFooter(), $page);
	echo $page;
?>
