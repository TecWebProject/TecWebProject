<?php

require_once realpath(dirname(__FILE__)).'/query_server.php';

/**
*  Classe per la richiesta dell'utente
*  Usare il metodo statico getUserameStatus
*  OUTPUT: true se valido, false se non valido
*/
class Username {
	/*
	DESC: Ritorna un array con i nomi delle province
	OUTPUT: Array associativo ordinato alfabeticamente;
	*/
	public static function getUsernameStatus($username) {
		try {
			mysqli_report(MYSQLI_REPORT_STRICT);

			$query = 'select COUNT(username) from Utenti WHERE username=?';

			$_mysqli = dbConnectionData::getMysqli();


			if (!$stmt = $_mysqli->prepare($query)) {
				throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
			}

			$stmt->bind_param("s", $username);

			$stmt->execute();
			$stmt_result = $stmt->get_result();
			$result = $stmt_result->fetch_all(MYSQLI_ASSOC);

			$notAlreadyUsed = $result[0]["COUNT(username)"] == 0;
			$valid = preg_match("/^[a-zA-Z0-9_]+$/",$username); # ho aggiunto l'underscore, che mancava (non solo caratteri alfanumerici)

			if ($notAlreadyUsed && $valid){
			   return 0;
			} elseif (!$valid){
			   return -1;
			} else {
			   return 1;
			}

		} catch (Exception $e) {
			throw $e;
		}
	}
}
