<?php

$relPath = realpath(dirname(__FILE__));
require_once $relPath . '/query_server.php';

/**
*  Classe per la richiesta dei tipi di contatto
*  Usare il metodo statico getTipiContatto
*  OUTPUT: Array associativo dei tipi di contatto
*/
class TipiContatto {
	/*
	DESCR: Ritorna un array con i nomi delle province
	OUTPUT: Array associativo ordinato alfabeticamente;
	*/
	public static function getTipiContatto() {
		$result = null;
		try {
			mysqli_report(MYSQLI_REPORT_STRICT);

			$query = "SELECT nome FROM TipiContatti ORDER BY nome";

			$_mysqli = dbConnectionData::getMysqli();

			if (!$stmt = $_mysqli->prepare($query)) {
				throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
			}

			$stmt->execute();
			$stmt_result = $stmt->get_result();
			$queryResult = $stmt_result->fetch_all(MYSQLI_ASSOC);

			$result = array_column($queryResult, "nome");

			return $result;
		} catch (Exception $e) {
			throw $e;
		}
	}
}

?>
