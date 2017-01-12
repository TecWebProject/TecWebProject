<?php

   $relPath = realpath(dirname(__FILE__));

   require_once $relPath . '/query_server.php';

   /**
   *  Classe per la richiesta dei contatti di un utente
   *  Usare il metodo statico getContattiUtente
   *  INPUT: username
   *  OUTPUT: array di contatti
   */
class ContattiUtente
{
    /*
    DESC: Ritorna un array dei contatti di un utente.
    INPUT: username dell'utente
    OUTPUT: Array di contatti
    */
    public static function getContattiUtente($username)
    {
        $result = null;
        try {
            mysqli_report(MYSQLI_REPORT_STRICT);

            $query = "SELECT tipoContatto, contatto FROM ContattiUtenti WHERE utente = ?";

            $_mysqli = dbConnectionData::getMysqli();

            if (!$stmt = $_mysqli->prepare($query)) {
                throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
            }

            $stmt->bind_param("s", $username);
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
