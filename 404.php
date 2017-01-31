<?php

	# scrivi intestazione
	require_once 'lib/php/start.php';
	echo Start::getHead(
		array(
			'Titolo' => 'Page not found - BandBoard',
			'DescrizioneBreve' => '404, page not found - BandBoard',
			'Descrizione' => '404, page not found - BandBoard',
			'Keywords' => array('BandBoard'),
			'Stylesheets' => array('lib/css/style.css'),
			'Extra' => array('<link rel="stylesheet" type="text/css" href="lib/css/style.css" />')
		)
	);



	# scrivi header
	require_once 'lib/php/header.php';
	$header = Header::getHeader();
	$header = str_replace('BandBoard', 'B<span class="notFound">4</span>ndB<span class="notFound">04</span>rd', $header);
	echo $header;



	# informa l'utente
	echo '<div id="content" class="notFound">' .
		'<h1>Pagina inesistente</h1>' . # ... o q.cosa di più simpatico!
		'<p>Puoi tornare alla <a href="index.php" xml:lang="en" lang="en">home</a>, cercare un <a href="cercaUtenti/index.php">utente</a> oppure cercare un <a href="cercaGruppi/index.php">gruppo</a> </p>' .
		'</div>';
?>
