<?php

/* PAGINA REGISTRAZIONE GRUPPO */

session_start();

if (!isset($_SESSION['username']))
	header('Location: ../index.php');

include_once realpath(dirname(__FILE__, 2))."/lib/php/query_server.php";
include_once realpath(dirname(__FILE__, 2))."/lib/php/start.php";	//LIBRERIA PER CREARE HEAD
include_once realpath(dirname(__FILE__, 2))."/lib/php/header.php";	//LIBRERIA PER CREARE HEADER
include_once realpath(dirname(__FILE__, 2))."/lib/php/menu.php";	//LIBRERIA PER CREARE MENU
include_once realpath(dirname(__FILE__, 2))."/lib/php/footer.php";	//LIBRERIA PER CREARE FOOTER

function pulisciInput($value) {
	$value=trim($value);
	$value=htmlentities($value);
	$value=strip_tags($value);
	return $value;
}

function getStrumenti() {
	$connessione=dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
	$listaStrumenti="";
	try {
		if ($connessione->connect_errno) {
			throw new Exception("Connessione fallita: ".$connessione->connect_error.".");
		} else {
			$query="SELECT strumento FROM Conoscenze WHERE utente=\"".$_SESSION['username']."\";";	//CREAZIONE DELLA QUERY
			if (!$result=$connessione->query($query)) {
				echo "Query non valida: ".$connessione->error.".";
			} else {
				if ($result->num_rows>0) {
					while ($row=$result->fetch_array(MYSQLI_ASSOC)) {
						$listaStrumenti=$listaStrumenti."<option value=\"".$row['strumento']."\">".$row['strumento']."</option>";
					}
				}
				$result->free();
			}
			$connessione->close();	//CHIUSURA CONNESSIONE
		}
	} catch (Exception $e){
		echo "Errore: dati non recuperati (".$e->getMessage().").";
	}
	return $listaStrumenti;
}

$page=Start::getHead(array(
	'Titolo' => "Registrazione Gruppo - BandBoard",
	'DescrizioneBreve' => "Registrazione Gruppo - BandBoard",
	'Descrizione' => "Pagina di registrazione di un gruppo al sito BandBoard",
	'Author' => array("Derek Toninato", "Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffrè"),
	'Keywords' => array("BandBoard", "registrazione", "gruppo", "iscrizione", "bacheca", "musica", "musicisti", "gruppi"),
	'BookmarkIcon' => 'site/logo.png',
	'Stylesheets' => array("style.css"),
	'Extra' => array("<link rel=\"stylesheet\" media=\"handheld, screen and (max-width:480px), only screen and (max-device-width:480px)\" href=\"../lib/css/style_mobile.css\" type=\"text/css\" />", "<!-- MI APPOGGIO AL FILE JAVASCRIPT (province.js) CREATO DA FILIPPO BERTO -->", "<script type=\"text/javascript\" src=\"../lib/js/province.js\"></script>", "<script type=\"text/javascript\" src=\"registrazioneGruppo.js\"></script>")
));	//CREAZIONE HEAD

$page=$page."<body>".Header::getHeader(); //CREAZIONE HEADER

$logout="<div class=\"logout\">
			<form action=\"../lib/php/logout.php\" method=\"post\">
				<p><input type=\"submit\" id=\"logout\" value=\"Logout\" /></p>
			</form>
		</div>";
$page=$page.$logout;

$page=$page."<div class=\"nav\">".Menu::getMenu(array('<a href="../index.php" xml:lang="en" lang="en">Home</a>', '<a href="../profiloUtente/profiloUtente.php?username=' . $_SESSION['username'] . '">Visualizza Profilo</a>', '<a href="../settings/index.php">Modifica Profilo</a>', '<a href="../cercaUtenti/index.php">Cerca Utenti</a>', '<a href="../cercaGruppi/index.php">Cerca Gruppi</a>', '<a href="../gestioneGruppi/gestioneGruppi.php">I miei Gruppi</a>')).'</div>';	//CREAZIONE DEL MENU

if (count($_REQUEST)==0) {	//APPENA ARRIVATO DA GESTIONE GRUPPI
	$page=$page.file_get_contents("registrazioneGruppo.txt");	//CREAZIONE PAGINA DI REGISTRAZIONE GRUPPO
	require_once "../lib/php/regioni.php";	//INSERIMENTO REGIONI SU SELECT
	$regioni="";
	$arr_regioni=Regioni::getRegioni();
	foreach ($arr_regioni as $el) {
		$regioni.='<option value="'.$el['nome'].'"';
		if (isset($_REQUEST['regione']) && $el['nome']==$_REQUEST['regione'])
			$regioni.=' selected="selected"';
		$regioni.='>'.$el['nome'].'</option>';
	}
	$page=str_replace('<regioni />', $regioni, $page);
	require_once "../lib/php/province.php";	//INSERIMENTO PROVINCE SU SELECT
	$province="";
	$arr_province=Province::getProvinceByRegione();
	foreach ($arr_province as $key => $reg) {
		$province.='<optgroup label="'.$key.'">';
		foreach ($reg as $prov) {
			$province.='<option value="'.$prov['sigla'].'"';
			if (isset($_GET['provincia']) && $prov['sigla']==$_REQUEST['provincia'])
				$province.=' selected="selected"';
			$province.='>'.$prov['nome'].'</option>';
		}
		$province.='</optgroup>';
	}
	$page=str_replace('<province />', $province, $page);
	$page=str_replace('<strumenti />', getStrumenti(), $page);
} else {	//E' STATO PREMUTO IL SUBMIT DEL FORM DI REGISTRAZIONE
	$page=$page."<div class=\"report\">";	//APERTURA DIV CONTENTENTE NOTIZIE SULLE OPERAZIONI DELLO SCRIPT
	$registrazione=false;
	try {
		if (file_exists(realpath(dirname(__FILE__, 2))."/lib/php/gruppo.php")) {	//INCLUSIONE DEL FILE gruppo.php
			require_once(realpath(dirname(__FILE__, 2))."/lib/php/gruppo.php");
		} else {
			throw new Exception("File necessario per l'esecuzione mancante.");
		}
		if ((isset($_REQUEST['nome'])) && (isset($_REQUEST['provincia'])) && (isset($_REQUEST['strumento']))  && ($_REQUEST['strumento']!="")) {	//CONTROLLO CAMPI COMPILATI
			foreach ($_REQUEST as $chiave => &$valore) {
				$valore=pulisciInput($valore);
			}
			$gruppo=new Gruppo($_REQUEST['nome'], "", "",  $_REQUEST['provincia']);	//CREAZIONE GRUPPO
			if ($gruppo=="") {
				$registrazione=$gruppo->save($_SESSION['username'], $_REQUEST['strumento']);	//INSERIMENTO NEL DATABASE
				$page=$page."<p class=\"okRep\">Registrazione avvenuta correttamente.</p>";
			} else {
				$page=$page."<p class=\"errRep\">I dati inseriti non sono corretti:</p>".$gruppo;
			}
		} else {
			$page=$page."<p class=\"errRep\">Compilare i campi obbligatori.</p>";
		}
	} catch(Exception $e) {
		$page=$page."Il sistema è momentaneamente inutilizzabile. Riprova più tardi: (".$e->getMessage().").";
	}
	$page=$page."</div>";	//CHIUSURA DIV

	if ($registrazione==false) {	//REGISTRAZIONE NON ANDATA A BUON FINE -> CREAZIONE DELLA PAGINA DI REGISTRAZIONE
		$page=$page.file_get_contents("registrazioneGruppo.txt");
		require_once "../lib/php/regioni.php";	//INSERIMENTO REGIONI SU SELECT
		$regioni="";
		$arr_regioni=Regioni::getRegioni();
		foreach ($arr_regioni as $el) {
			$regioni.='<option value="'.$el['nome'].'"';
			if (isset($_REQUEST['regione']) && $el['nome']==$_REQUEST['regione'])
				$regioni.=' selected="selected"';
			$regioni.='>'.$el['nome'].'</option>';
		}
		$page=str_replace('<regioni />', $regioni, $page);
		require_once "../lib/php/province.php";	//INSERIMENTO PROVINCE SU SELECT (CODICE DI GIUFFRE' GIORGIO)
		$province="";
		$arr_province=Province::getProvinceByRegione();
		foreach ($arr_province as $key => $reg) {
			$province.='<optgroup label="'.$key.'">';
			foreach ($reg as $prov) {
				$province.='<option value="'.$prov['sigla'].'"';
				if (isset($_REQUEST['provincia']) && $prov['sigla']==$_REQUEST['provincia'])
					$province.=' selected="selected"';
				$province.='>'.$prov['nome'].'</option>';
			}
			$province.='</optgroup>';
		}
		$page=str_replace('<province />', $province, $page);
		$page=str_replace('<strumenti />', getStrumenti(), $page);
	} else {	//REGISTRAZIONE ANDATA A BUON FINE -> CHIAMATA A PAGINA DI MODIFICA DEL GRUPPO
		//header("Location: ../modificaGruppo/modificaGruppo.php");	//CHIAMATA A PAGINA DI MODIFICA DEL GRUPPO
	}
}

$page=$page.Footer::getFooter()."</body></html>";	//CREAZIONE DEL FOOTER

echo $page;

?>
