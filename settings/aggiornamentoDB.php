<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/query_server.php";

/**
 * Created by PhpStorm.
 * User: pily
 * Date: 30/01/17
 * Time: 17.39
 */
class AggiornamentoDB
{
    /***
     * Aggiorna i dati sul db
     * @param $dati         array associativo dei dati da modificare, deve contenere almeno il campo username
     * @return bool         true se l'operazione è andata a buon fine, false altrimenti
     * @throws Exception    lancia le eccezioni sollevate dalla connessione al server mysql
     */
    static function aggiornaDatiDB($dati)
    {
        try {

            if (!isset($dati) || !isset($dati['username'])) {
                throw new Exception("Invalid input");
            }

            mysqli_report(MYSQLI_REPORT_STRICT);

            $mysqli = dbConnectionData::getMysqli();
            $mysqli->begin_transaction();

            foreach ($dati as $key => $campo) {
                switch ($key) {
                    case "username":
                        continue;
                        break;
                    case "nome":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `nome` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "cognome":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `cognome` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "email":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `email` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "password":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `password` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "dataNascita":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `dataNascita` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "bio":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `descrizione` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "provincia":

                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `provincia` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "immagineProfilo":
                        $stmt = $mysqli->prepare("UPDATE `Utenti` SET `immagine` = ? WHERE `Utenti`.`username` = ?");
                        $stmt->bind_param("ss", $campo, $dati['username']);
                        $stmt->execute();

                        break;
                    case "generi":

                        $stmt = $mysqli->prepare("DELETE FROM `GeneriUtenti` WHERE `GeneriUtenti`.`utente` = ?");
                        $stmt->bind_param("s", $dati['username']);
                        $stmt->execute();

                        foreach ($campo as $key => $genere) {
                            $stmt = $mysqli->prepare("INSERT INTO `GeneriUtenti` (`utente`, `genere`) VALUES (?, ?)");
                            $stmt->bind_param("ss", $dati['username'], $genere);
                            $stmt->execute();
                        }

                        break;
                    default:
                        $mysqli->rollback();
                        throw new Exception("Invalid content");

                        break;
                }
            }

            $mysqli->commit();
            $mysqli->close();

            return true;

        } catch (Exception $e) {
            switch ($e->getMessage()) {
                case "Invalid input":
                case "Invalid content":
                    return false;
                    break;
                default:
                    throw $e;
                    break;
            }
        }
    }
}
