<?php
   /* Funzione per trovare la lista di province in base alla regione passata */;

   $relPath = realpath(dirname(__FILE__));

   require_once($relPath . '/mysqliConnection.php');

   /*
      Restituisce le province della regione data,
      se la regione è null restituisce tutte le province,
      la regione va inserita usando solo i primi 3 caratteri e
      in maiuscolo ES: VEN per Veneto
   */
   function getProvince($regione = null)
   {
       $query = "SELECT Nome FROM Provincia WHERE Regione = ";

       $mysqli = new mysqli("localhost", "root", "root", "database_artisti");

       if (!is_null($regione)) {
           $regione = substr(strtoupper($regione), 0, 3);

           //TODO debug
           echo "$regione\n";

           $where = $regione;
       } else {
           $where = "*";
       }

       $query = $query . $where;

       if (!$result = $mysqli->query($query)) {
           // Oh no! The query failed.
          echo "Sorry, the website is experiencing problems.";

          // Again, do not do this on a public site, but we'll show you how
          // to get the error information
          echo "Error: Our query failed to execute and here is why: \n";
           echo "Query: " . $sql . "\n";
           echo "Errno: " . $mysqli->errno . "\n";
           echo "Error: " . $mysqli->error . "\n";
           exit;
       }

       $result =

           //TODO debug
           var_dump($result);

       $stmt->close();
   }

   getProvince($_GET['q']);
