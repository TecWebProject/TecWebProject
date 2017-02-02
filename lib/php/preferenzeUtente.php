<?php

require_once realpath(dirname(__FILE__)) . "/query_server.php";

/**
 * Created by PhpStorm.
 * User: pily
 * Date: 01/02/17
 * Time: 17.56
 */
class PreferenzeUtente
{
    /***
     * @param $username username dell'utente di cui si vogliono conoscere le preferenze
     * @return array associativo delle preferenze
     */
    public static function getPreferenze($username)
    {
        $mysqli = dbConnectionData::getMysqli();

        $stmt = $mysqli->prepare("SELECT `genere` FROM `GeneriUtenti` WHERE `utente` = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt_result = $stmt->get_result();
        $result = $stmt_result->fetch_all(MYSQLI_ASSOC);
        $result = array_column($result, "genere");

        return $result;
    }

}