<?php

$nomeSito = 'BandBoard';

# Classe per la generazione dell'header
class Header {

	# ritorna una stringa con l'header
	public static function getHeader() {
		global $nomeSito;
		return	'<div id="header">' .
				'<h1 xml:lang="en" lang="en">' . $nomeSito . '</h1>' .
#					'<img src="/images/logo.png" />'
				'</div>';
	}
}

?>
