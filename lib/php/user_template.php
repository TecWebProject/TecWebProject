<?php

# leggi template
$file = file_get_contents("../lib/struttura/user_page.html");

# costruisci intestazione
require_once 'start.php';
$start = Start::getHead(
	array(
		'Titolo' => $this->username . ' - BandBoard',
		'DescrizioneBreve' => 'Profilo di ' . $this->username . ' - BandBoard',
		'Descrizione' => 'Profilo di ' . $this->username . ' in BandBoard',
		'Keywords' => array('BandBoard', $this->username, 'utente'),
		'Stylesheets' => array('style.css', 'mobile.css')
	)
);
$file = str_replace('<html>', $start, $file);

# costruisci header
require_once 'header.php';
$header = Header::getHeader();
$file = str_replace('<header />', $header, $file);

# costruisci menù
require_once 'menu.php';
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
if (file_exists('../../users/' . $this->username . '/pic.png')) {
	$pic = '<img id="profPic" src="../../users/' . $this->username . '/pic.png" alt="Foto profilo dell\'utente ' . $this->username . '" />';
}
$banner = '<div id="userBanner">' .
		'<h1>' .
			$this->username .
		'</h1>' .
		$pic .
	'</div>';
$file = str_replace('<banner />', $banner, $file);

// altro... TODO

echo $file;

?>
