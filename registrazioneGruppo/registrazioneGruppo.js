/* SCRIPT JAVASCRIPT PER PAGINA DI REGISTRAZIONE DI UN GRUPPO */
function checkForm() {
	delErrName();
	delErrProvincia();
	delErrStrumento();
	var n=checkName();
	var p=checkProvincia();
	var s=checkStrumento();
	return (n && p && s);
}

function checkName() {
	if (document.getElementById("nome").value=="") {
		var node=document.createElement("P");
		var textnode=document.createTextNode("Inserisci il campo Nome");
		node.appendChild(textnode);
		document.getElementById("nome").parentNode.appendChild(node);
		return false;
	}
	return true;
}

function checkProvincia() {
	if (document.getElementById("modSelectProvincia").value=="") {
		var node=document.createElement("P");
		var textnode=document.createTextNode("Inserisci il campo Provincia");
		node.appendChild(textnode);
		document.getElementById("modSelectProvincia").parentNode.appendChild(node);
		return false;
	}
	return true;
}

function checkStrumento() {
	if (document.getElementById("strumento").value=="") {
		var node=document.createElement("P");
		var textnode=document.createTextNode("Inserisci il campo Strumento");
		node.appendChild(textnode);
		document.getElementById("strumento").parentNode.appendChild(node);
		return false;
	}
	return true;
}

function delErrName() {
	var x=document.getElementById("nome");
	while (x.parentNode.childNodes.length>=6) {
		x.parentNode.removeChild(x.parentNode.lastChild);
	}
}

function delErrProvincia() {
	var x=document.getElementById("modSelectProvincia");
	while (x.parentNode.childNodes.length>=6) {
		x.parentNode.removeChild(x.parentNode.lastChild);
	}
}

function delErrStrumento() {
	var x=document.getElementById("strumento");
	while (x.parentNode.childNodes.length>=6) {
		x.parentNode.removeChild(x.parentNode.lastChild);
	}
}
