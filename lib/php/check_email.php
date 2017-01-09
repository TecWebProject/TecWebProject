<?php
   /**
    * Classe per al gestione delle email
    */
class Email
{
    // Controlla se un email passata per $email è valida
    // Ritorna 0 se valida, 1 se non lo è
    public static function checkEmail($email)
    {
        $valid = filter_var($email, FILTER_VALIDATE_EMAIL);
        return $valid ? 0 : 1;
    }

}

?>
