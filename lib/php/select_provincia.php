<?php
   /* Funzione per trovare la lista di province in base alla regione passata */;

   $relPath = realpath(dirname(__FILE__));

   require_once($relPath . '/query_server.php');

   /**
   *
   */
   class SelectProvincia
   {
       //TODO sostituire con oggetto dalla nuova libreria
      private static $_mysqli;

      /*
         DESC: Ritorna un array con i nomi delle province legate alla regione in input.
         INPUT: Nome della regione abbreviato a 3 caratteri e in maiuscolo
         OUTPUT: Array di stringhe ordinato alfabeticamente;
      */
       public static function getProvince($regione)
       {
           $result = null;

           if (SelectProvincia::$_mysqli == null) {
               $_mysqli = new mysqli('localhost', 'root', 'root', 'database_artisti');
           }

           //Escape dell'input
           $abbRegione = preg_replace('/[^A-Z]+/', "", strtoupper(substr($regione, 0, 3)));

           //Check lunghezza stringa
           if (strlen($abbRegione) != 3) {
               //TODO finire
               throw new LogicException([$message, $code, $previous]);
           }



           //TODO debug
           var_dump($abbRegione);


           return $result;
       }
   }

   var_dump(SelectProvincia::getProvince("ve3"));
