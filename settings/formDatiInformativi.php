<?php

require_once realpath(dirname(__FILE__)) . "/../lib/php/datiUtente.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/utente.php";
require_once realpath(dirname(__FILE__)) . "/aggiornamentoDB.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/regioni.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/province.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/select_regione.php";
require_once realpath(dirname(__FILE__)) . "/../lib/php/paths.php";

session_start();

/**
 * Classe per generare i dati obbligatori della form di modifica profilo
 */
class FormDatiInformativi
{

    /**
     * @return string stringa della form
     * @throws Exception lancia un eccezione se la sessione non è valida
     */
    public static function getFormDatiInformativi()
    {

        $errori = array();

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

        if (!empty($_POST)) {

            // Test input
            $dati = array();

            $dati['username'] = $_SESSION['username'];

            // Carica immagine profilo
            try {

                if (empty($_FILES)) {
                    throw new Exception("No file passed");
                }

                if ($_FILES['profilePic']['name'] == "") {
                    throw new Exception("Image empty");
                }

                $img = $_FILES['profilePic']['tmp_name'];

                if (($imageInfo = getimagesize($img)) === false) {
                    throw new Exception("Not an image");
                }

                $uploadDirectory = realpath(dirname(__FILE__)) . "/../images/users";

                $relativePathUploadDirectory = Paths::getRelativePath(
                    realpath(dirname(__FILE__)),
                    $uploadDirectory
                );
                $fileTarget = $relativePathUploadDirectory . $dati['username'];

                $imageWidth = $imageInfo[0];
                $imageHeight = $imageInfo[1];

                switch ($imageInfo[2]) {
                    case IMAGETYPE_GIF  :
                        $src = imagecreatefromgif($img);
                        break;
                    case IMAGETYPE_JPEG :
                        $src = imagecreatefromjpeg($img);
                        break;
                    case IMAGETYPE_PNG  :
                        $src = imagecreatefrompng($img);
                        break;
                    default:
                        throw new Exception("Format not supported");
                        break;
                }

                if ($imageWidth > $imageHeight) {
                    $y = 0;
                    $x = ($imageWidth - $imageHeight) / 2;
                    $smallestSide = $imageHeight;
                } else {
                    $x = 0;
                    $y = ($imageHeight - $imageWidth) / 2;
                    $smallestSide = $imageWidth;
                }

                $thumbSize = 400;

                $tmp = imagecreatetruecolor($thumbSize, $thumbSize);
                imagecopyresampled($tmp, $src, 0, 0, $x, $y, $thumbSize, $thumbSize, $smallestSide, $smallestSide);
                imagejpeg($tmp, $fileTarget . ".jpg", 90);


                chmod($fileTarget . ".jpg", 0664);

                $dati['immagineProfilo'] = $dati['username'] . ".jpg";

            } catch (Exception $e) {
                switch ($e->getMessage()) {
                    case "No file passed":
                        error_log("No file passed");
                        break;
                    case "Image empty":
                        break;
                    case "Not an image":
                        array_push($errori, htmlentities("Il file caricato non è un'immagine", ENT_QUOTES, "UTF-8"));
                    default:
                        throw $e;
                        break;
                }
            }

            // Bottone elimina immagine
            try {
                if (!isset($_POST['eliminaImmagine'])) {
                    throw new Exception("No delete image");
                }

                if ($_POST['eliminaImmagine'] == "true") {
                    $dati['immagineProfilo'] = null;
                }

            } catch (Exception $e) {
                switch ($e->getMessage()) {
                    case "No delete image":
                        //DO NOTHING
                        break;
                    default:
                        throw $e;
                        break;

                }
            }
            
            // Regione e provincia
            try {
                if (!isset($_POST['selectProvincia']) && $_POST['selectRegione'] == "") {
                    throw new Exception("Nothing passed");
                }

                if ($_POST['selectRegione'] != "" && isset($_POST['selectProvincia']) && $_POST['selectProvincia'] == "") {
                    throw new Exception("Only one parameter passed");
                }

                $regioneDiProvinciaPassata = SelectRegione::getRegione($_POST['selectProvincia']);

                if ($regioneDiProvinciaPassata != $_POST['selectRegione']) {
                    throw new Exception("Unlegal input");
                }

                $dati['provincia'] = $_POST['selectProvincia'];

            } catch (Exception $e) {
                switch ($e->getMessage()) {
                    case "Nothing passed";
                        $dati['provincia'] = null;
                        break;
                    case "Only one parameter passed":
                        array_push($errori, "Entrambi i campi regione e povincia vanno compilati.");
                        break;
                    case "Unlegal input":
                        array_push($errori, "I campi regione e provincia non sono validi.");
                        break;
                    default:
                        throw $e;
                        break;
                }
            }

            // Bio
            try {

                if (!isset($_POST['bio'])) {
                    throw new Exception("Nothing to do");
                }

                if ($_POST['bio'] == "") {
                    $dati['bio'] = "";
                }

                $dati['bio'] = strip_tags($_POST['bio']);


            } catch (Exception $e) {
                switch ($e->getMessage()) {
                    case "Nothing to do":
                        //DO NOTHING
                        break;
                    default :
                        throw $e;
                        break;
                }
            }

            // Update DB
            if (empty($errori)) {
                try {
                    if (AggiornamentoDB::aggiornaDatiDB($dati)) {
//                        echo "Aggiornamento riuscito";
                    } else {
//                        echo "Aggiornamento fallito";
                        throw new Exception("Failed update");
                    }
                } catch (Exception $e) {
                    switch ($e->getMessage()) {
                        case "Failed update":
                            array_push($errori, "Qualcosa non ha funzionato. Riprova più tardi");
                            break;
                        default:
                            throw $e;
                            break;
                    }
                }
            }


            //Lettura dati dal DB

        }

        $dati = Utenti::getDatiUtente($_SESSION['username']);

        if ($dati == null) {
            session_unset();
            session_destroy();
            throw new Exception("Invalid session");
        }

        $string = "";

        if (!empty($errori)) {
            $string .= "<div id='modErroritrovati'><ul>";

            foreach ($errori as $errore) {
                $string .= "<li>" . $errore . "</li>";
            }

            $string .= "</ul></div>";
        }

        //Costruzione contenuto pagina
        $string .= "<form action='datiInformativi.php' method='post' enctype='multipart/form-data'><fieldset><legend>Dati informativi</legend><ul>";

        // Carica immagine profilo
        $string .= "<li><label for='modLoadImage'>Carica immagine profilo</label><input id='modLoadImage' name='profilePic' type='file' title='Carica immagine'/><p id='errorModLoadImage'></p></li>";
        // Elimina immagine profilo
        $string .= "<li><label for='modLoadImage'>Elimina immagine profilo</label><button id='modEliminaImmagine' name='eliminaImmagine' value='true'>Elimina immagine</button></li>";

        // regione di provenienza
        $string .= "<li><label for='modSelectRegione'>Regione di provenienza</label><select id='modSelectRegione' name='selectRegione' onchange='clearError(\"provenienza\");showProvince(this.value)';checkProvenienza();><option value=''>Seleziona regione</option>";

        $regioni = Regioni::getRegioni();
        $regioneAppartenenza = SelectRegione::getRegione($dati['provincia']);

        foreach ($regioni as $key => $regione) {
            if ($regione['nome'] == $regioneAppartenenza) {
                $string .= "<option value='" . htmlentities($regione['nome'], ENT_QUOTES, "UTF-8") . "' selected='selected'>" . htmlentities($regione['nome'], ENT_QUOTES, "UTF-8") . "</option>";
            } else {
                $string .= "<option value='" . htmlentities($regione['nome'], ENT_QUOTES, "UTF-8") . "'>" . htmlentities($regione['nome'], ENT_QUOTES, "UTF-8") . "</option>";
            }
        }
        $string .= "</select></li>";

        // provincia di appartenenza
        $string .= "<li><label for='modSelectProvincia'>Provincia di provenienza</label><select id='modSelectProvincia' name='selectProvincia' onchange='clearError(\"provenienza\")';checkProvenienza();><option value=''>Seleziona provincia</option>";

        $province = Province::getProvince();

        foreach ($province as $key => $provincia) {
            if ($provincia['sigla'] == $dati['provincia']) {
                $string .= "<option value='" . htmlentities($provincia['sigla'], ENT_QUOTES, "UTF-8") . "' selected='selected'>" . htmlentities($provincia['nome'], ENT_QUOTES, "UTF-8") . "</option>";
            } else {
                $string .= "<option value='" . htmlentities($provincia['sigla'], ENT_QUOTES, "UTF-8") . "'>" . htmlentities($provincia['nome'], ENT_QUOTES, "UTF-8") . "</option>";
            };
        }
        $string .= "</select><span id='errorModProvenienza' class='modErrorEntry'></span></li>";

        // bio
        //TODO placeholder "Scrivi una breve descrizione di te..."
        $string .= "<li><label for='modTextAreaBio'>Bio</label><textarea id='modTextAreaBio' name='bio' cols='40' rows='4' onblur='checkBio(this.value)'>" . $dati['descrizione'] . "</textarea><span id='errorModBio' class='modErrorEntry'></span></li>";

        $string .= "</ul><button type='submit'>Salva</button></fieldset></form>";

        return $string;
    }

}
