<?php

/* pagina della gestione dei gruppi */

session_start();

if (!isset($_SESSION['username']))
	header('Location: ../index.php');

include_once '../lib/php/query_server.php';
include_once '../lib/php/start.php';
include_once '../lib/php/header.php';
include_once '../lib/php/menu.php';
include_once '../lib/php/footer.php';

$page = '';
$page .= Start::getHead(
	array(
		'Titolo' => 'Gestione Gruppi - BandBoard',
		'DescrizioneBreve' => 'Gestione Gruppi - BandBoard',
		'Descrizione' => 'Pagina di visualizzazione della gestione gruppi del sito BandBoard',
		'Keywords' => array('BandBoard', 'Gestione gruppi', 'gruppi','musicista', 'bacheca', 'musica', 'musicisti'),
		'Stylesheets' => array('style.css'),
		'Extra' => array('<link rel="stylesheet" media="handheld, screen and (max-width:480px), only screen and (max-device-width:480px)" href="../lib/css/style_mobile.css" type="text/css" />')
	)
);

$page .= '<body>';
$page .= Header::getHeader();

# creazione del menù ed eventuale pulsante logout per utente registrato
if (isset($_SESSION['username'])) {
	$logout = '<div class="logout">' .
		'<form action="../lib/php/logout.php" method="post">' .
			'<p><input type="submit" id="logout" value="Logout" /></p>' .
		'</form>' .
	'</div>';
	$page .= $logout;
	$page .= '<div class="nav">' . Menu::getMenu(
		array(
			'<a href="../index.php" xml:lang="en" lang="en">Home</a>',
			'<a href="../settings/index.php">Modifica Profilo</a>',
			'<a href="../cercaUtenti/index.php">Cerca Utenti</a>',
			'<a href="../cercaGruppi/index.php">Cerca Gruppi</a>',
			'I Miei Gruppi'
		)
	) . '</div>';
} else {
	session_unset();
	session_destroy();
	$page .= '<div class="nav">' . Menu::getMenu(
		array(
			'<a href="../index.php" xml:lang="en" lang="en">Home</a>',
			'<a href="../cercaUtenti/index.php">Cerca Utenti</a>',
			'<a href="../cercaGruppi/index.php">Cerca Gruppi</a>'
		)
	) . '</div>';
}

$page .= file_get_contents('gestioneGruppi.txt');

//connessione al db

$conn = dbConnectionData::getMysqli();	//CONNESSIONE AL DATABASE
try {
	if ($conn->connect_errno) {
		throw new Exception("Connessione fallita: ".$conn->connect_error.".");
	} else {
		$query = "SELECT G.nome, G.immagine, G.idGruppo
		FROM Conoscenze C JOIN Formazioni F ON C.idConoscenza = F.ruolo
		JOIN Gruppi G ON F.gruppo=G.idGruppo
		WHERE C.utente='" . $_SESSION['username'] . "'";
		$gruppi="";
		if (!$result=$conn->query($query)) {
				echo 'Query non valida: ' . $conn->error . '.';
		} else {
			if ($result->num_rows > 0) { # se appartiene a 1 o più gruppi, stampa immagine e gruppo
				$gruppi = '<ul>';
				while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
					if ($row['immagine'] == NULL) {
						$row['immagine'] = '../images/site/defaultBand.png';
						$gruppi .= '<li class="elementResult"><a href="../profiloGruppo/profiloGruppo.php?idGruppo=' . $row['idGruppo'] . '"><img class="listImage" src="../images/site/' . $row['immagine'] . '" alt="Immagine di "' . $row['nome'] . "' /> " . $row['nome'];
						# link alla pagina di modifica della band 
						$gruppi .= '<li class="updates"><a href="../modificaGruppo/modificaGruppo.php?idGruppo=' . $row['idGruppo'] . '">';
						$page = str_replace('<modifica />', $modifica, $page);
						# form per lasciare una band
						$lascia = '<div class="lascia">' .
							'<form action="../lib/php/to do.php" method="post">' .
								'<p><input type="submit" id="lascia" value="Lascia" /></p>' .
							'</form>' .
						'</div>';
						$page = str_replace('<lascia />', $lascia, $page);
					} else {
						$gruppi .= '<li class="elementResult"><a href="profiloGruppo/profiloGruppo.php?idGruppo=' . $row['idGruppo'] . '&page=index"><img class="listImage" src="images/site/' . $row['immagine'] . '" alt="Immagine di ' . $row['nome'].'" /> ' . $row['nome'];
					}						
					$gruppi .= "</a></li>";
				}
				$result->free();
				$gruppi .= '</ul>';
			}
			else { # altrimenti stampa
				$gruppi .= '<p>Al momento non sei membro di nessun gruppo.</p>';
			}
		}
		$page = str_replace('<nomeGruppi />', $gruppi, $page);
	}
} catch (Exception $e){
	echo 'Errore: dati non recuperati (' . $e->getMessage() . ').';
}

$page = str_replace('<footer />', Footer::getFooter(), $page);
echo $page;

?>
