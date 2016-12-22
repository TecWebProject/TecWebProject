<?php

/*
    Stampa l'head delle pagine in base al contesto passato come parametro.
    Contesto è un array asociativo con i seguenti parametri:
        Titolo              -   Nel tag title
        DescrizioneBreve    -   Nel meta title
        Descrizione         -   Nel meta description
        Keyowrds            -   Nel meta keywords
*/
// ESEMPIO
//Head::getHead(array('Titolo' => "PASS TODO Nome Sito", 'DescrizioneBreve' => "PASS TODO Descrizione breve", 'Descrizione' => "PASS TODO Descrizione pagina", 'Keywords' => array("PASS TODO KEYWORD SITO", "PASS TODO KEYWORD 2", "PASS TODO KEYWORD 3"), 'Extra' => array("<link rel='stylesheet' href='/lib/css/styleStampa.css' />", "<link rel='stylesheet' href='/lib/css/styleSmartphone.css' />")));

class Head
{
    private static $contestoDefault = array(
      'Titolo' => "TODO Nome Sito",
      'DescrizioneBreve' => "TODO Descrizione breve",
      'Descrizione' => "TODO Descrizione pagina",
      'Keywords' => array("TODO KEYWORD SITO", "TODO KEYWORD 2", "TODO KEYWORD 3")
   );

    public static function getHead($contesto)
    {

       #DOCTYPE
       printf("%s\n", "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>");

       #INIZIO HEAD
       printf("%s\n", "<head>");

       #CHARSET
       printf("%s\n", "<meta http-equiv='Content-Type' content='txt/html' charset='utf-8'>");

       #TAG TITLE
       Head::getTitle($contesto);

       #META name="title"
       Head::getMetaTitle($contesto);

       #META name="description"
       Head::getMetaDescription($contesto);

       #META name="keywords"
       Head::getMetaKeywords($contesto);

       #META name="viewport"
       printf("%s\n", "<meta name='viewport' content='width=device-width, initial-scale=1.0'>");


       #ICONA BOOKMARK
       printf("%s\n", "<link rel='shortcut icon' href='images/icon.ico' />");

       #STYLESHEETS
       printf("%s\n", "<link rel='stylesheet' href='/lib/css/style.css' />");

       #PARAMETRI AGGIUNTIVI
       #Attenzione! Stampa tutto il contenuto di $contesto['Extra'] con "\n" alla fine di ogni elemento
       Head::getExtraTags($contesto);

       #FINE HEAD
       printf("%s\n", "</head>");
    }

    #Genera il tag <title>
    private function getTitle($contesto)
    {
        printf("<title>%s</title>\n", isset($contesto) && isset($contesto['Titolo'])?$contesto['Titolo']:Head::$contestoDefault['Titolo']);
    }

    #Genera il tag <meta name="title">
    private function getMetaTitle($contesto)
    {
        printf("<meta name='title' content='%s' />\n", isset($contesto) && isset($contesto['DescrizioneBreve']) ? $contesto['DescrizioneBreve'] : Head::$contestoDefault['DescrizioneBreve']);
    }

    #Genera il tag <meta name="description">
    private function getMetaDescription($contesto)
    {
        printf("<meta name='description' content='%s' />\n", isset($contesto) && isset($contesto['Descrizione']) ? $contesto['Descrizi../lib/cssone'] : Head::$contestoDefault['Descrizione']);
    }

    #Genera il tag <meta name="keywords">
    private function getMetaKeywords($contesto)
    {
        $keywords = isset($contesto) && isset($contesto['Keywords']) ? $contesto['Keywords'] : Head::$contestoDefault['Keywords'];
        $string = implode(", ", $keywords);
        printf("<meta name='keywords' content='%s' />\n", $string);
    }

    #Gerena i tag aggiuntivi passati come Extra
    private function getExtraTags($contesto)
    {
        printf(isset($contesto) && isset($contesto['Extra']) ? implode("\n", $contesto['Extra'])."\n" : "");
    }
}
