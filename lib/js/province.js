// aggiorna il selettore
function showProvince(reg) {
	if (reg == "") {
		// scelto "Seleziona provincia"
		document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
		document.getElementById("modSelectProvincia").disabled = true;
	} else {
		var regioni = document.getElementsByTagName('optgroup');
		for (var i = 0; i < regioni.length; i++) {
			if (regioni[i].label != reg)
				regioni[i].style.display = 'none';
			else
				regioni[i].style.display = 'inline';
		}
	}
}
