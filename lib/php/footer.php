<?php

class Footer {
	
	public static function getFooter() {
		$footer = '<div class=footer>' .
			'<div class="footerContent">' .
				'<p>BandBoard è stato creato per un progetto nell\'ambito del corso di Tecnologie Web. Nome del sito e testi sono stati puramente ideati dagli sviluppatori del progetto. Logo, foto e immagini sono state recuperate da ricerche nel web.</p>' .
				'<p>' .
					'<img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" />' .
					'<img style="border:0;width:88px;height:31px" src="http://jigsaw.w3.org/css-validator/images/vcss" alt="Valid CSS!" />' .
				'</p>' .
			'</div>' .
		'</div>';
		return $footer;
	}
}

?>
