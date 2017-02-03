<?php

/**
 * Classe per la generazione automatica del menu laterale
 * Basta passare un array di stringhe al metodo Menu::getMenu()
 */
class Menu {
	// getMenu($links)
	// $links è l'array di elementi del menu
	public static function getMenu($links) {
		// Check array links valido
		if (!isset($links)) {
			throw new Exception("Missing links in menu", 1);
		}

		// Aggiunge <li> e </li>
		for ($i = 0; $i < count($links); ++$i) {
			$links[$i] = "<li>" . $links[$i] . "</li>";
		}

		// Stringa finale
		return "<input type='checkbox' id='menu_check' name='menu_check' />\n<div class='nav'>\n<label id='menu_button' for='menu_check'><a title='Mostra menu laterale'>&#9776;</a></label>\n<ul>\n".implode("\n", $links)."\n</ul>\n</div>\n";
	}
}
