<?php

/* SIMPLE WRAPPER FOR MYSQL QUERY */

/*TODO: get query from POST*/

/* FUNZIONE DI PROVA PER ESEMPIO*/
//TEST();
function TEST()
{
    $query = 'SELECT * FROM Regione';
    $mysqli = dbConnectionData::getMysqli();
    $stmt = $mysqli->query($query);
    var_dump($stmt->fetch_all(MYSQLI_ASSOC));
}

/* CLASSE DATI CONNESSIONE */
class dbConnectionData
{
    protected static $_data = array(
      'hostname' => 'localhost',
      'username' => 'root',
      'password' => 'root',
      'dbName' => 'database_artisti',
      'port' => 3306);

    public static function getData()
    {
        return dbConnectionData::$_data;
    }

    public static function getMysqli()
    {
        mysqli_report(MYSQLI_REPORT_STRICT);

        $data = dbConnectionData::getData();

        try {
            $mysqli = new mysqli(
               $data['hostname'],
               $data['username'],
               $data['password'],
               $data['dbName'],
               $data['port']);
        } catch (mysqli_sql_exception $e) {
            echo "Service unavailable\n";
            error_log("ERROR CONNECTION: " . $e->getMessage());
            exit;
        }

        return $mysqli;
    }
}
