<?php
	session_start();
	
	include_once realpath(dirname(__FILE__, 2))."/lib/php/query_server.php"; //libreria di connessione + esempi
	include_once realpath(dirname(__FILE__, 2))."/lib/php/start.php";	//LIBRERIA PER CREARE HEAD
	include_once realpath(dirname(__FILE__, 2))."/lib/php/header.php";	//LIBRERIA PER CREARE HEADER
	include_once realpath(dirname(__FILE__, 2))."/lib/php/menu.php";	//LIBRERIA PER CREARE MENU
	include_once realpath(dirname(__FILE__, 2))."/lib/php/footer.php";	//LIBRERIA PER CREARE FOOTER
	
	$page="";
	$page=$page.Start::getHead(array(
	'Titolo' => "BandBoard",
	'DescrizioneBreve' => "Gestione Band - BandBoard",
	'Descrizione' => "Pagina di visualizzazione della gestione delle band del sito BandBoard",
	'Author' => array("Derek Toninato", "Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffre"),
	'Keywords' => array("BandBoard", "gestione band", "gruppo", "band", "bacheca", "musica", "musicisti", "gruppi", "chitarra", "basso", "batteria", "piano", "tastiera"),
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
	$page=$page.file_get_contents(realpath(dirname(__FILE__))."/gestioneBand.txt");
	
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			//trova i gruppi ai quali appartiene l'utente, e i link alle pagine profilo delle band da costruire
			$query="SELECT G.nome, G.immagine, G.idGruppo FROM Conoscenze C JOIN Formazioni F ON C.idConoscenza=F.ruolo JOIN Gruppi G ON F.gruppo=G.idGruppo WHERE C.utente=\"".$_REQUEST["username"]."\";";
			$gruppi="";
			if (!$result=$connessione->query($query)) {
					echo "Query non valida: ".$connessione->error.".";
			} else {
					if ($result->num_rows>0) { //se appartiene a 1 o + gruppi stampa immagine e gruppo
						$gruppi="<ul>";
							while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
								if ($row['immagine']==NULL) {
										$row['immagine']="defaultBand.png";
										$gruppi=$gruppi."<li class=\"elementResult\"><a href=\"profiloBand/profiloBand.php?idGruppo=".$row['idGruppo']."&page=index\"><img class=\"listImage\" src=\"images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" /> ".$row['nome'];
								} else {
										$gruppi=$gruppi."<li class=\"elementResult\"><a href=\"profiloBand/profiloBand.php?idGruppo=".$row['idGruppo']."&page=index\"><img class=\"listImage\" src=\"images/site/".$row['immagine']."\" alt=\"Immagine di ".$row['nome']."\" /> ".$row['nome'];
								}						
								$gruppi=$gruppi."</a></li>";
							}
							$result->free();
							$gruppi=$gruppi."</ul>";
					}
					else { //altrimenti stampa
						$gruppi=$gruppi."<p>Al momento non sei membro di nessuna band.</p>";}
			}
			$page=str_replace("<nomeGruppi />", $gruppi, $page);
				
				//form per lasciare una band
				$lascia="<div class=\"lascia\">
	                <form action=\"../lib/php/to do.php\" method=\"post\">
    	                <p><input type=\"submit\" id=\"lascia\" value=\"Lascia\" /></p>
	                </form>
                </div>";
				$page=str_replace("<lascia />", $lascia, $page);
		}
		}catch (Exception $e){
			echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	if ($_REQUEST['page']=="index") { //se il parametro page contiene index, sono arrivato dalla home
			$precPage="<p class=\"paginaPrec\"><a href=\"../index.php\" id=\"torna\">Torna alla Home</a></p>";
	} else { //sono arrivato dalla pagina di ricerca
			
			if ($_REQUEST['page']=="ricerca") {
					$precPage="<p class=\"paginaPrec\"><a href=\"../cercaUtenti/index.php?num=\"".$_REQUEST['num']."\" id=\"torna\">Torna alla Ricerca</a></p>";
			}
	}
	$page=str_replace("<pagPrec />", $precPage, $page);
	$page=str_replace("<footer />", Footer::getFooter(), $page);
	echo $page;
?>