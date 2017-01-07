<?php

/*
	Ritorna l'array delle stringhe dell'head delle pagine in base
	al contesto passato come parametro. Contesto è un array
	asociativo con i seguenti parametri:
		Titolo               -  Testo nel tag title
		DescrizioneBreve     -  Testo nel tag meta title
		Descrizione          -  Testo nel tag meta description
		Author               -	Array di stringhe (o singola stringa) degli autori nel tag meta keywords
		Keywords             -  Array di stringhe (o singola stringa) delle keyword nel tag meta keywords
		BookmarkIcon         -  Nome del file dell'icona
		Stylesheets          -  Array di stringhe (o singola stringa) dei file stylesheet del documento
	Se non viene passato uno degli argomenti, il relativo risultato sarà NULL
*/

// ESEMPIO
//var_dump(getArray(array('Titolo' => "PASS TODO Nome Sito", 'DescrizioneBreve' => "PASS TODO Descrizione breve", 'Descrizione' => "PASS TODO Descrizione pagina", 'Author' => array("Derek Toninato","Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffrè"), 'Keywords' => array("PASS TODO KEYWORD 1","PASS TODO KEYWORD 2","PASS TODO KEYWORD 3"), 'BookmarkIcon' => 'icon.png', 'Stylesheets' => array("style.css"), 'Extra' => array( "<link type='text/css' rel='stylesheet' href='lib/css/styleStampa.css' />", "<link type='text/css' rel='stylesheet' href='lib/css/styleSmartphone.css' />" ))));

class Start
{
	private static $contestoDefault = array(
		'Titolo' => "TODO Nome Sito",
		'DescrizioneBreve' => "TODO Descrizione breve",
		'Descrizione' => "TODO Descrizione pagina",
		'Author' => array("Derek Toninato", "Filippo Berto", "Francesco Pezzuto", "Giorgio Giuffrè"),
		'Keywords' => array("TODO KEYWORD SITO", "TODO KEYWORD 2", "TODO KEYWORD 3"),
		'BookmarkIcon' => 'missing_icon.png',
		'Stylesheets' => array("wrong_path_style.css")
	);

	# fornisce il Doctype
	public static function getDoctype() {
		return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
	}

	# fornisce l'intestazione
	public static function getHead($contesto) {
		$string = '';
		$array = Start::getArray($contesto);
		foreach ($array as $value) {
			if (!is_array($val)) {
				$string .= $val;
			} else {
				foreach ($val as $el) {
					$string .= $el;
				}
			}
		}
		return $string;
	}

	# fornisce un array associativo con Doctype + head
	public static function getArray($contesto) {

		# DOCTYPE
		$Doctype = Start::getDoctype();

		# CHARSET
		$Charset = "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";

		# TITLE
		$TagTitle = Start::getTitle($contesto);

		# META name="title"
		$MetaTitle = Start::getMetaTitle($contesto);

		# META name="description"
		$MetaDescription = Start::getMetaDescription($contesto);

		# META name="author"
		$MetaAuthor = Start::getMetaAuthor($contesto);

		# META name="keywords"
		$MetaKeywords = Start::getMetaKeywords($contesto);

		# META name="viewport"
		$MetaViewport = "<meta name='viewport' content='width=device-width, initial-scale=1.0' />";

		# ICONA BOOKMARK
		$BookmarkIcon = Start::getIcon($contesto);

		# STYLESHEETS
		$Stylesheets = Start::getStylesheets($contesto);

		# PARAMETRI AGGIUNTIVI
		# Attenzione! Stampa tutto il contenuto di $contesto['Extra'] con "\n" alla fine di ogni elemento
		$Extra = Start::getExtraTags($contesto);

		# RETURN RESULTS
		return array('Doctype' => $Doctype, 'Charset' => $Charset, 'TagTitle' => $TagTitle, 'MetaTitle' => $MetaTitle, 'MetaDescription' => $MetaDescription, 'MetaAuthor' => $MetaAuthor, 'MetaKeywords' => $MetaKeywords, 'MetaViewport' => $MetaViewport, 'BookmarkIcon' => $BookmarkIcon, 'Stylesheets' => $Stylesheets, 'Extra' => $Extra);
	}

	# Genera il tag <title>
	private static function getTitle($contesto) {
		$title = isset($contesto) && isset($contesto['Titolo']) ? $contesto['Titolo'] : (isset($contestoDefault) && isset($contestoDefault['Titolo']) ? $contestoDefault['Titolo'] : null);

		 if($title == null){
			 error_log("Missing Titolo");
			 return $title;
		 }

		 return "<title>".$title."</title>";
	}

	# Genera il tag <meta name="title">
	private static function getMetaTitle($contesto) {

		$title = isset($contesto) && isset($contesto['DescrizioneBreve']) ? $contesto['DescrizioneBreve'] : (isset($contestoDefault) && isset($contestoDefault['DescrizioneBreve']) ? $contestoDefault['DescrizioneBreve'] : null);

		 if($title == null){
			 error_log("Missing DescrizioneBreve");
			 return $title;
		 }

		 return "<meta name='title' content='".$title."' />";
	}

	# Genera il tag <meta name="description">
	private static function getMetaDescription($contesto) {

		$description = isset($contesto) && isset($contesto['Descrizione']) ? $contesto['Descrizione'] : (isset($contestoDefault) && isset($contestoDefault['Descrizione']) ? $contestoDefault['Descrizione'] : null);

		 if($description == null){
			 error_log("Missing Descrizione");
			 return $description;
		 }

		 return "<meta name='description' content='".$description."' />";
	}

	# Genera il tag <meta name="author">
	private static function getMetaAuthor($contesto) {

		# Get authors
		$authors = isset($contesto) && isset($contesto['Author']) ? $contesto['Author'] : (isset($contestoDefault) && isset($contestoDefault['Author']) ? $contestoDefault['Author'] : null);

		if (!is_array($authors)) {
			# String
			return "<meta name='author' content='".$authors."' />";
		} elseif (count($authors) > 0) {
			# Array of strings
			return "<meta name='author' content='".implode(", ", $authors)."' />";
		} else {
			# None
			error_log("Missing Author");
			return null;
		}
	}

	# Genera il tag <meta name="keywords">
	private static function getMetaKeywords($contesto) {
		$keywords = isset($contesto) && isset($contesto['Keywords']) ? $contesto['Keywords'] : (isset($contestoDefault) && isset($contestoDefault['Keywords']) ? $contestoDefault['Keywords'] : null);
		if (!is_array($keywords)) {
			# String
			return "<meta name='keywords' content='".$keywords."' />";
		} elseif (count($keywords) > 0) {
			# Array of strings
			return "<meta name='keywords' content='".implode(", ", $keywords)."' />";
		} else {
			# None
			error_log("Missing Keywords");
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
		$fileNames = isset($contesto) && isset($contesto['Stylesheets']) ? $contesto['Stylesheets'] : (isset($contestoDefault) && isset($contestoDefault['Stylesheets']) ? $contestoDefault['Stylesheets'] : array());

		# Get realpath
		$relStylesheetPath = realpath(dirname(__FILE__, 2)) . "/";

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

					# File found
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
