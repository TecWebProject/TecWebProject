<?php

$relPath = realpath(dirname(__FILE__));
require_once $relPath . '/query_server.php';

/**
*  Classe per la richiesta delle province di una regione
*  Usare il metodo statico getProvince
*  INPUT: stringa contentente le prime tre lettere del nome della regione in maiuscolo.
*  OUTPUT: Array associativo delle province di tale regione
*/
class SelectProvincia {
	/*
	DESC: Ritorna un array con i nomi delle province legate alla regione in input.
	INPUT: Nome della regione abbreviato a 3 caratteri e in maiuscolo
	OUTPUT: Array di stringhe ordinato alfabeticamente;
	*/
	public static function getProvince($regione) {
		$result = null;
		try {
			mysqli_report(MYSQLI_REPORT_STRICT);

			$query = "SELECT sigla,nome FROM Province WHERE regione = ? ORDER BY nome";

			$_mysqli = dbConnectionData::getMysqli();

			if (!$stmt = $_mysqli->prepare($query)) {
				throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
			}

			$stmt->bind_param("s", $regione);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
			$result = $stmt_result->fetch_all(MYSQLI_ASSOC);

			return $result;
		} catch (Exception $e) {
			switch ($e->getMessage()) {
			case 'Input non valido':
				return null;
				break;

			default:
				throw $e;
				break;
			}
		}
	}
}