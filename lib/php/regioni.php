<?php

   $relPath = realpath(dirname(__FILE__));

   require_once($relPath . '/query_server.php');

   /**
   *  Classe per la richiesta delle province di una regione
   *  Usare il metodo statico getProvince
   *  INPUT: stringa contentente le prime tre lettere del nome della regione in maiuscolo.
   *  OUTPUT: Array associativo delle province di tale regione
   */
   class Regioni
   {
       /*
         DESC: Ritorna un array con i nomi delle province legate alla regione in input.
         INPUT: Nome della regione abbreviato a 3 caratteri e in maiuscolo
         OUTPUT: Array di stringhe ordinato alfabeticamente;
      */
       public static function getRegioni()
       {
           $result = null;
           try {
               mysqli_report(MYSQLI_REPORT_STRICT);

               $query = "SELECT * FROM Regione ORDER BY Nome";

               $_mysqli = dbConnectionData::getMysqli();

               if (!$stmt = $_mysqli->prepare($query)) {
                   throw new Exception("ERROR: [$_mysqli->errno] $_mysqli->error\n", 1);
               }
               $stmt->execute();
               $stmt_result = $stmt->get_result();
               $result = $stmt_result->fetch_all(MYSQLI_ASSOC);

               return $result;
           } catch (Exception $e) {
               throw $e;
           }
       }
   }
