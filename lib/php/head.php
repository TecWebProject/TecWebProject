<?php

/*
	Ritorna l'array delle stringhe dell'head delle pagine in base
	al contesto passato come parametro. Contesto è un array
	asociativo con i seguenti parametri:
		Titolo               -  Testo nel tag title
		DescrizioneBreve     -  Testo nel tag meta title
		Descrizione          -  Testo nel tag meta description
		Author					-	Array di stringhe (o singola stringa) degli autori nel tag meta keywords
		Keywords             -  Array di stringhe (o singola stringa) delle keyword nel tag meta keywords
		BookmarkIcon         -  Nome del file dell'icona
		Stylesheets          -  Array di stringhe (o singola stringa) dei file stylesheet del documento
	Se non viene passato uno degli argomenti, il relativo risultato sarà NULL
*/

// ESEMPIO
//var_dump(Head::getHead(array('Titolo' => "PASS TODO Nome Sito", 'DescrizioneBreve' => "PASS TODO Descrizione breve", 'Descrizione' => "PASS TODO Descrizione pagina", 'Author' => array("Derek Toninato","Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffre"), 'Keywords' => array("PASS TODO KEYWORD 1","PASS TODO KEYWORD 2","PASS TODO KEYWORD 3"), 'BookmarkIcon' => 'icon.png', 'Stylesheets' => array("style.css"), 'Extra' => array( "<link type='text/css' rel='stylesheet' href='lib/css/styleStampa.css' />", "<link type='text/css' rel='stylesheet' href='lib/css/styleSmartphone.css' />" ))));

class Head
{
	private static $contestoDefault = array(
		'Titolo' => "TODO Nome Sito",
		'DescrizioneBreve' => "TODO Descrizione breve",
		'Descrizione' => "TODO Descrizione pagina",
		'Author' => array("Derek Toninato","Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffre"),
		'Keywords' => array("TODO KEYWORD SITO", "TODO KEYWORD 2", "TODO KEYWORD 3"),
		'BookmarkIcon' => 'missing_icon.png',
		'Stylesheets' => array("wrong_path_style.css")
	);

	public static function getString($contesto) {
		$string = '';
		$array = getHead($contesto);
		foreach ($array as $value) {
			if (!is_array($val)) {
				$string .= $val;
			} else {
				foreach ($val as $el) {
					$string .= $el;
			}
		}
		return $string;
	}

	public static function getHead($contesto) {

		# DOCTYPE
		$Doctype = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";

		# CHARSET
		$Charset = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";

		# TAG TITLE
		$TagTitle = Head::getTitle($contesto);

		# META name="title"
		$MetaTitle = Head::getMetaTitle($contesto);

		# META name="description"
		$MetaDescription = Head::getMetaDescription($contesto);

		# META name="author"
		$MetaAuthor = Head::getMetaAuthor($contesto);

		# META name="keywords"
		$MetaKeywords = Head::getMetaKeywords($contesto);

		# META name="viewport"
		$MetaViewport = "<meta name='viewport' content='width=device-width, initial-scale=1.0' />";

		# ICONA BOOKMARK
		$BookmarkIcon = Head::getIcon($contesto);

		# STYLESHEETS
		$Stylesheets = Head::getStylesheets($contesto);

		# PARAMETRI AGGIUNTIVI
		# Attenzione! Stampa tutto il contenuto di $contesto['Extra'] con "\n" alla fine di ogni elemento
		$Extra = Head::getExtraTags($contesto);

		return array('Doctype' => $Doctype, 'Charset' => $Charset, 'TagTitle' => $TagTitle, 'MetaTitle' => $MetaTitle, 'MetaDescription' => $MetaDescription, 'MetaAuthor' => $MetaAuthor, 'MetaKeywords' => $MetaKeywords, 'MetaViewport' => $MetaViewport, 'BookmarkIcon' => $BookmarkIcon, 'Stylesheets' => $Stylesheets, 'Extra' => $Extra);
	}

	# Genera il tag <title>
	private static function getTitle($contesto) {
		return isset($contesto) && isset($contesto['Titolo']) ?
			"<title>".$contesto['Titolo']."</title>" :
			Head::$contestoDefault['Titolo'];
	}

	# Genera il tag <meta name="title">
	private static function getMetaTitle($contesto) {
		return isset($contesto) && isset($contesto['DescrizioneBreve']) ?
			"<meta name='title' content='".$contesto['DescrizioneBreve']."' />" :
			Head::$contestoDefault['DescrizioneBreve'];
	}

	# Genera il tag <meta name="description">
	private static function getMetaDescription($contesto) {
		return isset($contesto) && isset($contesto['Descrizione']) ?
			"<meta name='description' content='".$contesto['Descrizione']."' />" :
			Head::$contestoDefault['Descrizione'];
	}

	# Genera il tag <meta name="author">
	private static function getMetaAuthor($contesto) {

		# Get authors
		$authors = isset($contesto) && isset($contesto['Author']) ? $contesto['Author'] : (isset(Head::$contestoDefault) && isset(Head::$contestoDefault['Author']) ? Head::$contestoDefault['Author'] : null);

		if (!is_array($authors)) {
			# String
			return "<meta name='author' content='".$authors."' />";
		} elseif (count($authors) > 0) {
			# Array of strings
			return "<meta name='author' content='".implode(", ", $authors)."' />";
		} else {
			# None
			return null;
		}
	}

	# Genera il tag <meta name="keywords">
	private static function getMetaKeywords($contesto) {
		$keywords = isset($contesto) && isset($contesto['Keywords']) ? $contesto['Keywords'] : Head::$contestoDefault['Keywords'];
		if (!is_array($keywords)) {
			# String
			return "<meta name='keywords' content='".$keywords."' />";
		} elseif (count($keywords) > 0) {
			# Array of strings
			return "<meta name='keywords' content='".implode(", ", $keywords)."' />";
		} else {
			# None
			return null;
		}
	}

	# Genera il tag link per la feedicon
	private static function getIcon($contesto) {
		# Get icon from contesto
		if (isset($contesto) && isset($contesto['BookmarkIcon'])) {
			$iconName = $contesto['BookmarkIcon'];
		} elseif (isset($contestoDefault) && isset($contestoDefault['BookmarkIcon'])) {
			$iconName = $contestoDefault['BookmarkIcon'];
		} else {
			$iconName = null;
		}

		# Abbsolute path to images folder
		$relImagesPath = realpath(dirname(__FILE__, 3))."/images/";

		# Find icon type
		switch (pathinfo($relImagesPath.$iconName, PATHINFO_EXTENSION)) {
			case 'png':
				$iconType = "img/png";
				break;
			case 'jpg':
			case 'jpeg':
				$iconType = "img/jpg";
				break;
			case 'gif':
				$iconType = "img/gif";
				break;
			default:
				$iconType = null;
				break;
		}

		# If all necessary data is found generates the string, else report error
		if (isset($iconName) && isset($iconType)) {
			if (!file_exists($relImagesPath.$iconName)) {
				error_log("Icon $iconName missing");
			} else {
				return "<link rel='icon' type='".$iconType."' href='".$relImagesPath.$iconName."' />";
			}
		} else {
			return null;
		}
	}

	# Genera i fogli di stile
	private static function getStylesheets($contesto) {
		# Get all file names
		$fileNames = isset($contesto) && isset($contesto['Stylesheets']) ? $contesto['Stylesheets'] : (isset(Head::$contestoDefault) && isset(Head::$contestoDefault['Stylesheets']) ? Head::$contestoDefault['Stylesheets'] : array());

		# Get realpath
		$relStylesheetPath = realpath(dirname(__FILE__, 2))."/";

		# Check if array or single string
		if (!is_array($fileNames)) {

			# String
			if (!file_exists($relStylesheetPath . $fileNames)) {
				# File missing
				error_log("Stylesheet $fileNames not found");
				$results =  null;
			} else {
				# File found
				$results = array("<link type='text/css' rel='stylesheet' href='" . $relStylesheetPath . $fileNames . "' />");
			}
		} else {

			# Array
			$results = array();

			foreach ($fileNames as $key => $fileName) {

				# For each file
				if (!file_exists($relStylesheetPath . $fileName)) {

					# File missing
					error_log("Stylesheet $fileName not found");
				} else {

					# File founds
					array_push($results, "<link type='text/css' rel='stylesheet' href='" . $relStylesheetPath . $fileName . "' />");
				}
			}
		}

		# Return all strings generated
		return $results;
	}

	# Genera i tag aggiuntivi passati come Extra
	private static function getExtraTags($contesto) {
		# Returns all extra tags found
		return isset($contesto) && isset($contesto['Extra']) ? $contesto['Extra'] : null;
	}
}
