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
var_dump(
   Head::getHead(
      array(
         'Titolo' => "PASS TODO Nome Sito",
         'DescrizioneBreve' => "PASS TODO Descrizione breve",
         'Descrizione' => "PASS TODO Descrizione pagina",
         'Keywords' => array(
            "PASS TODO KEYWORD SITO",
            "PASS TODO KEYWORD 2",
            "PASS TODO KEYWORD 3"
         ),
         'Stylesheets' => array("style.css", "oldStyle.css"),
         'Extra' => array(
            "<link type='text/css' rel='stylesheet' href='lib/css/styleStampa.css' />",
            "<link type='text/css' rel='stylesheet' href='lib/css/styleSmartphone.css' />"
         )
      )
   )
);

class Head
{
    private static $contestoDefault = array(
      'Titolo' => "TODO Nome Sito",
      'DescrizioneBreve' => "TODO Descrizione breve",
      'Descrizione' => "TODO Descrizione pagina",
      'Keywords' => array("TODO KEYWORD SITO", "TODO KEYWORD 2", "TODO KEYWORD 3"),
      'BookmarkIcon' => 'icon.png',
      'Stylesheets' => array("wrong_path_style.css")
   );

    public static function getHead($contesto)
    {

       #DOCTYPE
       $Doctype = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>";

       #CHARSET
       $Charset = "<meta http-equiv='Content-Type' content='txt/html' charset='utf-8'>";

       #TAG TITLE
       $TagTitle = Head::getTitle($contesto);

       #META name="title"
       $MetaTitle = Head::getMetaTitle($contesto);

       #META name="description"
       $MetaName = Head::getMetaDescription($contesto);

       #META name="keywords"
       $MetaKeywords = Head::getMetaKeywords($contesto);

       #META name="viewport"
       $MetaViewport = "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";

       #ICONA BOOKMARK
       $BookmarkIcon = Head::getIcon($contesto);//"<link rel='icon' type='img/png' href='images/icon.ico' />";

       #STYLESHEETS
       $Stylesheets = Head::getStylesheets($contesto);

       #PARAMETRI AGGIUNTIVI
       #Attenzione! Stampa tutto il contenuto di $contesto['Extra'] con "\n" alla fine di ogni elemento
       $Extra = Head::getExtraTags($contesto);

        return array('Doctype' => $Doctype, 'Charset' => $Charset, 'TagTitle' => $TagTitle, 'MetaTitle' => $MetaTitle, 'MetaName' => $MetaName, 'MetaKeywords' => $MetaKeywords, 'MetaViewport' => $MetaViewport, 'BookmarkIcon' => $BookmarkIcon, 'Stylesheets' => $Stylesheets, 'Extra' => $Extra);
    }

    #Genera il tag <title>
    private function getTitle($contesto)
    {
        return isset($contesto) && isset($contesto['Titolo']) ?
            "<title>".$contesto['Titolo']."</title>" :
            Head::$contestoDefault['Titolo'];
    }

    #Genera il tag <meta name="title">
    private function getMetaTitle($contesto)
    {
        return isset($contesto) && isset($contesto['DescrizioneBreve']) ?
            "<meta name='title' content='".$contesto['DescrizioneBreve']."' />" :
            Head::$contestoDefault['DescrizioneBreve'];
    }

    #Genera il tag <meta name="description">
    private function getMetaDescription($contesto)
    {
        return isset($contesto) && isset($contesto['Descrizione']) ?
        "<meta name='description' content='".$contesto['Descrizione']."' />" :
        Head::$contestoDefault['Descrizione'];
    }

    #Genera il tag <meta name="keywords">
    private function getMetaKeywords($contesto)
    {
        $keywords = isset($contesto) && isset($contesto['Keywords']) ? $contesto['Keywords'] : Head::$contestoDefault['Keywords'];
        return "<meta name='keywords' content='".implode(", ", $keywords)."' />";
    }

    private function getIcon($contesto)
    {
        isset($contesto) && isset($contesto['BookmarkIcon']) ? $contesto['BookmarkIcon'] : Head::$contestoDefault['BookmarkIcon'];
        return "<link rel='icon' type='img/png' href='images/".$contesto['BookmarkIcon']."' />";
    }

    private function getStylesheets($contesto)
    {
        $fileNames = isset($contesto) && isset($contesto['Stylesheets']) ? $contesto['Stylesheets'] : (isset(Head::$contestoDefault) && isset(Head::$contestoDefault['Stylesheets']) ? Head::$contestoDefault['Stylesheets'] : array());

        $relStylesheetPath = realpath(dirname(__FILE__, 2))."/";

        if (!is_array($fileNames)) {
            if (!file_exists($relStylesheetPath . $fileNames)) {
                error_log("Stylesheet $fileNames not found");
                $results =  null;
            } else {
                $results = array("<link type='text/css' rel='stylesheet' href='" . $relStylesheetPath . $fileNames . "' />");
            }
        } else {
            $results = array();

            foreach ($fileNames as $key => $fileName) {
                if (!file_exists($relStylesheetPath . $fileName)) {
                    error_log("Stylesheet $fileName not found");
                } else {
                    array_push($results, "<link type='text/css' rel='stylesheet' href='" . $relStylesheetPath . $fileName . "' />");
                }
            }
        }

        return $results;
    }

    #Gerena i tag aggiuntivi passati come Extra
    private function getExtraTags($contesto)
    {
        return isset($contesto) && isset($contesto['Extra']) ? $contesto['Extra'] : null;
    }
}
