// Esegue query per le province e aggiorna il selettore
function showProvince(str) {
	if (str == "") {
		// Scelto "Seleziona Provincia"
		document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
		document.getElementById("modSelectProvincia").disabled = true;
	} else {
		// Stringa valida, eseguo query
		// IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>' + this.responseText;
				document.getElementById("modSelectProvincia").disabled = false;
			}
		};
		xmlhttp.open("GET", "../lib/php/script_select_provincia.php?regione=" + str, true);
		xmlhttp.send();
		document.getElementById("modSelectProvincia").innerHTML = xmlhttp.response;
	}
}

// Nasconde le opzioni del selettore delle province e lo disabilita
function clearProvince() {
	if (document.getElementById("modSelectProvincia").selectedIndex == 0) {
		document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
		document.getElementById("modSelectProvincia").disabled = true;
	}
}
