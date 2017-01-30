<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";

session_start();

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormDatiObbligatori
{
    /**
     * @return string stringa della form
     * @throws Exception lancia un eccezione se la sessione non è valida
     */
    public static function getFormDatiObbligatori()
    {

        // Controllo sessione attiva
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Controllo sessione valida
        if (!isset($_SESSION['username'])) {
            session_unset();
            session_destroy();
            throw new Exception("Invalid session");
        }

        //Gestione input con post
        try {

            if (!empty($_POST)) {

                // Test input
                $errori = array();
                $dati = array();

                // Nome
                try {

                    if (!isset($_POST['nome'])) {
                        throw new Exception("Missing name");
                    }

                    if (!preg_match("/^[A-Za-zàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\s]+/ui", "")) {
                        throw new Exception("Invalid name");
                    }

                    $dati["nome"] = $_POST['nome'];

                } catch (Exception $e) {
                    switch ($e->getMessage()) {
                        case "Missing name":
                            array_push($errori, "Nome vuoto.");
                            break;
                        case "Invalid name":
                            array_push($errori, "Nome non valido. Può contenere solo lettere e spazi.");
                            break;
                        default:
                            throw $e;
                            break;
                    }
                }

                // Cognome
                try {

                    if (!isset($_POST['cognome'])) {
                        throw new Exception("Missing surname");
                    }

                    if (!preg_match("/^[A-Za-zàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\s]+/ui", "")) {
                        throw new Exception("Invalid surname");
                    }

                    $dati['cognome'] = $_POST['cognome'];

                } catch (Exception $e) {
                    switch ($e->getMessage()) {
                        case "Missing surname":
                            array_push($errori, "Cognome vuoto.");
                            break;
                        case "Invalid surname":
                            array_push($errori, "Cognome non valido. Può contenere solo lettere e spazi.");
                            break;
                        default:
                            throw $e;
                            break;
                    }
                }

                // Email
                try {

                    if (!isset($_POST['email'])) {
                        throw new Exception("Missing email");
                    }

                    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                        throw new Exception("Invalid email");
                    }


                } catch (Exception $e) {
                    switch ($e->getMessage()) {
                        case "Missing email":
                            array_push($errori, "Email vuota.");
                            break;
                        case "Missing email":
                            array_push($errori, "Email non valida.");
                            break;
                        default:
                            throw $e;
                            break;
                    }
                }

                // Password

                try {
                    if (!isset($_POST['password']) xor !isset($_POST['passwordCheck'])) {
                        throw new Exception("One password missing");
                    }

                    if ($_POST['password'] == null xor $_POST['passwordCheck'] == null) {
                        throw new Exception("One password not compiled");
                    }

                    if (strlen($_POST['password']) < 8) {
                        throw new Exception("Password too short");
                    }

                    if()

                } catch (Exception $e) {
                    switch ($e->getMessage()) {
                        case "One password missing":
                            array_push($errori, "Per cambiare la password devono essere compilate entrambi i campi password.");
                            break;
                        case "One password not compiled":
                            array_push($errori, "Per cambiare la password devono essere compilate entrambi i campi password.");
                            break;
                        default:
                            throw $e;
                            break;
                    }
                }

                // Update DB

            }

        } catch (Exception $e) {

        }

        //Lettura dati dal DB
        $dati = Utenti::getDatiUtente($_SESSION['username']);

        if ($dati == null) {
            session_unset();
            session_destroy();
            throw new Exception("Invalid session");
        }

        //Costruzione contenuto pagina
        $string = "<form action='datiObbligatori.php' method='post'><fieldset><legend>Dati obbligatori</legend><ul>";

        // username
        $string .= "<li>Username: " . $dati['username'] . "</li>";

        // nome
        //TODO placeholder='Nome'
        $string .= "<li><label for='modNome'>Nome</label><input id='modNome' name='nome' value='";
        $string .= $dati['nome'];
        $string .= "' onblur='checkNome(this.value)' onkeypress='clearError(\"nome\")'/><span id='errorModNome' class='modErrorEntry'></span></li>";

        // cognome
        //TODO placeholder='Cognome'
        $string .= "<li><label for='modCognome'>Cognome</label><input id='modCognome' name='cognome' value='";
        $string .= $dati['cognome'];
        $string .= "' onblur='checkCognome(this.value)' onkeypress='clearError(\"cognome\")'/><span id='errorModCognome' class='modErrorEntry'></span></li>";

        // email
        //TODO placeholder='email'
        $string .= "<li><label for='modEmail'>Email</label><input id='modEmail' name='email' size='25' value='";
        $string .= $dati['email'];
        $string .= "' onblur='checkEmail(this.value)' onkeypress='clearError(\"email\")'/><span id='errorModEmail' class='modErrorEntry'></span></li>";

        // password
        //TODO placeholder='password'
        $string .= "<li>
        <label for='modPassword'>Password</label><input type='password' id='modPassword' name='password' onblur='checkPassword(this.value)' onkeypress='clearError(\"password\")'/><span id='errorModPassword' class='modErrorEntry'></span>
        </li>";

        // password check
        //TODO placeholder='password check'
        $string .= "<li>
        <label for='modPassword'>Reinserisci password</label><input type='password' id='modPasswordCheck' name='passwordCheck' onblur='checkPasswordCheck(this.value)' onkeypress='clearError(\"passwordCheck\")'/><span id='errorModPasswordCheck' class='modErrorEntry'></span>
        </li>";

        // data di nascita
        //TODO placeholder='gg' placeholder='mm' placeholder='aaaa'
        $string .= "<li><div>Data di nascita:</div><label for='modDataNascitaGiorno'>Giorno</label><input id='modDataNascitaGiorno' name='bDayGiorno' type='text' size='2' maxlength='2'   value='";
        $string .= date("d", strtotime($dati['dataNascita']));
        $string .= "' onkeypress='clearError(\"data\")' onblur='checkBDay()'/><label for='modDataNascitaMese'>Mese</label><input id='modDataNascitaMese' name='bDayMese' type='text' size='2' maxlength='2' value='";
        $string .= date("m", strtotime($dati['dataNascita']));
        $string .= "' onkeypress='clearError(\"data\")' onblur='checkBDay()'/><label for='modDataNascitaAnno'>Anno</label><input id='modDataNascitaAnno' name='bDayAnno' type='text' size='4' maxlength='4' value='";
        $string .= date("Y", strtotime($dati['dataNascita']));
        $string .= "' onkeypress='clearError(\"data\")' onblur='checkBDay()'/><span id='errorModDataNascita' class='modErrorEntry'></span></li>";

        $string .= "</ul><button type='submit'>Salva</button></fieldset></form>";

        return $string;
    }

}
