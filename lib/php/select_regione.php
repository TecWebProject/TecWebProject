<?php

$relPath = realpath(dirname(__FILE__));
require_once $relPath . '/query_server.php';

/**
*  Classe per la richiesta della regione di una provincia
*  Usare il metodo statico getRegione
*  INPUT: nome della provincia
*  OUTPUT: nome della regione
*/
class SelectRegione
{
	/*
	DESC: Ritorna un array con i nomi delle province legate alla regione in input.
	INPUT: Nome della regione abbreviato a 3 caratteri e in maiuscolo
	OUTPUT: Array di stringhe ordinato alfabeticamente;
	*/
	public static function getRegione($provincia) {
		$result = null;
		try {
			mysqli_report(MYSQLI_REPORT_STRICT);

			$query = "SELECT regione FROM Province WHERE sigla = ? GROUP BY regione";

			$_mysqli = dbConnectionData::getMysqli();

			if (!$stmt = $_mysqli->prepare($query)) {
				throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
			}

			$stmt->bind_param("s", $provincia);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
			$result = $stmt_result->fetch_all(MYSQLI_ASSOC);

			return $result[0]['regione'];

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

?>
