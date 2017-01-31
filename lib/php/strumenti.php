<?php

$relPath = realpath(dirname(__FILE__));
require_once $relPath . '/query_server.php';

/**
*  Classe per la richiesta degli strumenti musicali
*  Usare il metodo statico getStrumenti()
*  OUTPUT: Array degli strumenti musicali
*/
class Strumenti {
	public static function getStrumenti() {
		$result = null;
		try {
			mysqli_report(MYSQLI_REPORT_STRICT);

			$query = "SELECT nome FROM Strumenti ORDER BY nome";

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
