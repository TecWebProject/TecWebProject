<?php

   $relPath = realpath(dirname(__FILE__));

   require_once $relPath . '/query_server.php';

   /**
   *  Classe per la richiesta dei dati di un utente
   *  Usare il metodo statico getDatiUtente
   */
class Utenti
{
    /*
    DESC: Ritorna un array con i dati dell'utente in
    INPUT: username dell'utente
    OUTPUT: Array di dati associativi;
    */
    public static function getDatiUtente($username)
    {
        $result = null;
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);

            $query1 = "SELECT * FROM Utenti WHERE username = ?";

            $_mysqli = dbConnectionData::getMysqli();

            if (!$stmt = $_mysqli->prepare($query1)) {
                throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt_result = $stmt->get_result();
            $result = $stmt_result->fetch_all(MYSQLI_ASSOC);

            $query2 = "SELECT tipoContatto, contatto FROM ContattiUtenti WHERE utente = ?";

            $_mysqli = dbConnectionData::getMysqli();

            if (!$stmt = $_mysqli->prepare($query2)) {
                throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt_result = $stmt->get_result();
            $result[0]['contatti'] = ($stmt_result->fetch_all(MYSQLI_ASSOC))[0];

            return $result[0];
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

Utenti::getDatiUtente("giorgio");
