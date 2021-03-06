<?php

require_once realpath(dirname(__FILE__)) . "/paths.php";

class Footer
{

    public static function getFooter()
    {

        # Abbsolute path to the file which called this script
        $stack = debug_backtrace();
        $executionFilePath = $stack[count($stack) - 1]["file"];

        # Abbsolute path to images folder
        $absImagesPath = realpath(dirname(__FILE__, 3)) . "/images/site";

        # Relative path to images folder
        $relativePathToImages = Paths::getRelativePath($executionFilePath, $absImagesPath);

        $footer = "<div id='footer'><p>BandBoard &egrave; stato creato per un progetto nell&#39;ambito del corso di Tecnologie Web. Nome del sito e testi sono stati puramente ideati dagli sviluppatori del progetto. Loghi, foto e immagini sono state recuperate da ricerche nel web e sono propriet&agrave; di chi di diritto.</p><p><img src='" . $relativePathToImages . "vcss.gif" . "' alt='Valid XHTML 1.0 Strict' height='31' width='88'/> <img src='" . $relativePathToImages . "valid-xhtml10.png" . "' alt='Valid CSS' height='31' width='88' /></p></div>";
        return $footer;
    }
}

?>
