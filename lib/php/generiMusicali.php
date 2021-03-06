<?php

$relPath = realpath(dirname(__FILE__));
require_once $relPath . '/query_server.php';

/**
*  Classe per la richiesta delle province di una regione
*  Usare il metodo statico getProvince
*  OUTPUT: Array associativo delle province di tale regione
*/
class GeneriMusicali {
	/*
	DESCR: Ritorna un array con i nomi delle province
	OUTPUT: Array associativo ordinato alfabeticamente;
	*/
	public static function getGeneriMusicali() {
		$result = null;
		try {
			mysqli_report(MYSQLI_REPORT_STRICT);

			$query = "SELECT nome FROM GeneriMusicali ORDER BY nome";

			$_mysqli = dbConnectionData::getMysqli();

			if (!$stmt = $_mysqli->prepare($query)) {
				throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
			}
			$stmt->execute();
			$stmt_result = $stmt->get_result();
			$result = $stmt_result->fetch_all(MYSQLI_ASSOC);

			return array_column($result, "nome");
		} catch (Exception $e) {
			throw $e;
		}
	}
}

?>
