<?php

$username = '<username />';

# leggi template
$file = file_get_contents("../lib/struttura/user_page.html");

# costruisci intestazione
require_once '../lib/php/start.php';
require_once '../lib/php/query_server.php';
$conn = dbConnectionData::getMysqli();
$risultati = '';
$stmt = $conn->prepare("SELECT nome, cognome, dataNascita, immagine, descrizione, dataIscrizione, provincia FROM Utenti WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->bind_result($res_nome, $res_cognome, $res_nascita, $res_immagine, $res_descrizione, $res_iscrizione, $res_provincia);
$res = $stmt->execute();
$stmt_result = $stmt->get_result();
$result = $stmt_result->fetch_all(MYSQLI_ASSOC);
if (!$result)
	echo 'Errore con il database: riprova più tardi.';
$stmt->close();
$conn->close();

$start = Start::getHead(
	array(
		'Titolo' => $username . ' - BandBoard',
		'DescrizioneBreve' => 'Profilo di ' . $username . ' - BandBoard',
		'Descrizione' => 'Profilo di ' . $username . ' in BandBoard',
		'Keywords' => array('BandBoard', $username, 'utente'),
		'Stylesheets' => array('style.css', 'mobile.css')
	)
);
$file = str_replace('<html>', $start, $file);

# costruisci header
require_once '../lib/php/header.php';
$header = Header::getHeader();
$file = str_replace('<header />', $header, $file);

# costruisci menù
require_once '../lib/php/menu.php';
$menu = Menu::getMenu(
	array(
		'<a href="index.html" xml:lang="en">Home</a>',
		'<a href="profilo.php">Profilo</a>',
		'<a href="../cercaUtenti/index.php">Cerca Utenti</a>',
		'<a href="../cercaGruppi/index.php">Cerca Gruppi</a>',
		'<a href="imieigruppi.php">I miei Gruppi</a>'
	)
);
$file = str_replace('<menu />', $menu, $file);

$pic = '';
if (file_exists('../../images/' . $username . '/pic.png')) {
	$pic = '<img id="profPic" src="../../users/' . $username . '/pic.png" alt="Foto profilo dell\'utente ' . $username . '" />';
}
$banner = '<div id="userBanner">' .
		'<h1>' .
			$username .
		'</h1>' .
		$pic .
	'</div>';
$file = str_replace('<banner />', $banner, $file);

// altro... TODO

echo $file;

?>
