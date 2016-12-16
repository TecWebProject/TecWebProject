<?php
   /* Funzione per trovare la lista di province in base alla regione passata */;

   $relPath = realpath(dirname(__FILE__));

   require_once($relPath . '/query_server.php');

   /*
      Restituisce le province della regione data,
      se la regione è null restituisce tutte le province,
      la regione va inserita usando solo i primi 3 caratteri e
      in maiuscolo ES: VEN per Veneto
   */
   function getProvince($regione = null)
   {
       $query = "SELECT Nome FROM Provincia";

       if (!is_null($regione)) {
           $regione = substr(strtoupper($regione), 0, 3);

           $where = " WHERE Regione = " . $regione;
       } else {
           $where = "";
       }

       $query = $query . $where;
       $connection = new dbConnectionData(array('localhost', 'root', 'root', 'database_artisti'));
       $queryResults = getResults($query, $connection);

       $results = array_fill(0, count($queryResults), "");

       for ($i=0; $i < count($queryResults); ++$i) {
           $results[$i] = $queryResults[$i]['Nome'];
       }

       return $results;
   }


   var_dump(getProvince(isset($_GET['q'])?$_GET['q']:null));
