<?php

/**
 * Classe per la generazione automatica del menu laterale
 * Basta passare un array di stringhe al metodo Menu::getMenu()
 */
class Menu
{
    /* getMenu($link,[$corrente=-1])
      $link è l'array di elementi del menu
    */
   public static function getMenu($links)
   {
       // Check array links valido
       if (!isset($links)) {
           throw new Exception("Impossibile generare il menu", 1);
       }


       // Aggiunge <li> e </li>
       for ($i=0; $i < count($links); ++$i) {
           $links[$i] = "<li>$links[$i]</li>";
       }

       // Stampa finale
       printf("<input type='checkbox' id='menu_check' name='menu_check' />\n<div class='nav'>\n<label id='menu_button' for='menu_check'><a title='Mostra menù laterale'>&#9776;</a></label>\n<ul>\n%s\n</ul>\n</div>\n", implode("\n", $links));
   }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="it" lang="it">

<head>
    <!-- TODO: correggere head per il sito corretto -->
    <!-- Specifico il charset -->
    <meta http-equiv="Content-Type" content="txt/html" ; charset="utf-8">
    <!-- Titolo -->
    <!-- TODO: IMPOSTARE I NOMI CON PHP-->
    <title>Modifica profilo - NOME SITO</title>
    <!-- Titolo esteso dentro un meta -->
    <!-- TODO: IMPOSTARE I NOMI CON PHP-->
    <meta name="title" content="Modifica del profilo utente - NOME SITO" />
    <!-- Descrizione del sito -->
    <!-- TODO: INSERIRE DESCRIZIONE DELLA PAGINA-->
    <meta name="description" content="DESCRIZIONE" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Keyword principali del sito -->
    <!-- TODO: INSERIRE KEYWORD ADATTE-->
    <meta name="keywords" content="modifica, profilo, aggiornamento, NOME_SITO" />
    <!-- Icona del bookmark -->
    <!-- TODO: IMPOSTARE LINK ICONA-->
    <link rel="shortcut icon" href="images/icon.ico" />
    <!--miniReset-->
    <!--    <link rel="stylesheet" href="../lib/css/minireset.min.css" />-->
    <!-- link al CSS -->
    <link rel="stylesheet" href="/lib/css/style.css" />
    <!--  TODO inserire media query per cellulari  -->
<!--    <link rel="stylesheet" href="../lib/css/style_mobile.css" />-->
</head>
<body>
<?php
//TODO TEST
Menu::getMenu(array("Home","<a href='pagina.html'>Profilo</a>","<a href='pagina.html'>Cerca</a>","<a href='pagina.html'>Band</a>"));
?>
</body>
