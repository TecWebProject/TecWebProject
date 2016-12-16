<?php

/* SIMPLE WRAPPER FOR MYSQL QUERY */

/*TODO: get query from POST*/

/* FUNZIONE DI PROVA PER ESEMPIO*/
//TEST();
function TEST()
{
    $query = 'SELECT * FROM Regione';
    $connectionData = new dbConnectionData(
      array('hostname' => 'localhost',
      'username' => 'root',
      'password' => 'temp_pwd',
      'dbName' => 'database_artisti',
      'port' => 3360, )
   );
    var_dump(getResults($query, $connectionData));
}

/* FUNZIONE DI RICERCA */
function getResults($query, $connectionData, $resulttype = MYSQLI_ASSOC)
{
    /* Recover connection data */
   $data = $connectionData->getData();
   /* Istantiate mysqli object using connection data */
   $mysqli = new mysqli($data['hostname'], $data['username'], $data['password'], $data['dbName'], $data['port']);
   /* Check for errors*/
   if ($mysqli->connect_errno) {
       printf("<p>Connection error: (%u) %s</p>", $mysqli->connect_errno, $mysqli->connect_error);
   }
   /* Escape query */
   $escapedQuery = $mysqli->real_escape_string($query);
   /* Query server */
   $res = $mysqli->query($escapedQuery);
   /* Check for errors */
   if ($mysqli->error) {
       printf("<p>Error: %s</p>", $mysqli->error);
       return null;
   }
   /* Fetch results */
   $result = $res->fetch_all(MYSQLI_ASSOC);
   /* Free resources and close the connection */
   $res->free();
    $mysqli->close();
   /* Return the results*/
   return $result;
}

/* CLASSE DATI CONNESSIONE */
class dbConnectionData
{
    protected $_data = array();

    public function __construct($data)
    {
        if (is_array($data)) {
            if (isset($data['hostname']) && isset($data['username']) && isset($data['password']) && isset($data['dbName'])) {
                $this->_data = array(
               'hostname' => $data['hostname'],
               'username' => $data['username'],
               'password' => $data['password'],
               'dbName' => $data['dbName'],
               'port' => 3306,
            );
                if (isset($data['port'])) {
                    $this->data['port'] = $data['port'];
                }
            } elseif (isset($data[0]) && isset($data[1]) && isset($data[2]) && isset($data[3])) {
                $this->_data = array(
               'hostname' => $data[0],
               'username' => $data[1],
               'password' => $data[2],
               'dbName' => $data[3],
               'port' => 3306,
            );
                if (isset($data[4])) {
                    $this->data['port'] = $data[4];
                }
            } else {
                echo "<p> ERROR: malformed input </p>\n";
            }
        }
    }

    public function getData()
    {
        return $this->_data;
    }
}
