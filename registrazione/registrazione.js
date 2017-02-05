/* SCRIPT JAVASCRIPT PER PAGINA DI REGISTRAZIONE */
	function checkForm() {
		delErrUsername();
		delErrPassword();
		delErrConfPassword();
		delErrEmail();
		delErrDataNascita();
		var user=checkUsername();
		var psw=checkPassword();
		var confPsw=checkConfPassword();
		var email=checkEmail();
		var dataNascita=checkDataNascita();
		return (user && psw && confPsw && email && dataNascita);
	}

	function checkUsername() {
		if (document.getElementById("username").value=="") {
			var node=document.createElement("P");
			var textnode=document.createTextNode("Inserisci il campo Username");
			node.appendChild(textnode);
			node.className="errRep";
			document.getElementById("username").parentNode.appendChild(node);
			return false;
		}
		return true;
	}
	
	function checkPassword() {
		if (document.getElementById("password").value=="") {
			var node=document.createElement("P");
			var textnode=document.createTextNode("Inserisci il campo Password");
			node.appendChild(textnode);
			node.className="errRep";
			document.getElementById("password").parentNode.appendChild(node);
			return false;
		}
		return true;
	}
	
	function checkConfPassword() {
		if (document.getElementById("confermaPassword").value=="") {
			var node=document.createElement("P");
			var textnode=document.createTextNode("Inserisci il campo Conferma password");
			node.appendChild(textnode);
			node.className="errRep";
			document.getElementById("confermaPassword").parentNode.appendChild(node);
			return false;
		} else {
			if (document.getElementById("confermaPassword").value!=document.getElementById("password").value) {
				var node=document.createElement("P");
				var textnode=document.createTextNode("Campo Password diverso da Conferma password");
				node.appendChild(textnode);
				node.className="errRep";
				document.getElementById("confermaPassword").parentNode.appendChild(node);
				return false;
			}
		}
		return true;
	}
	
	function checkEmail() {
		if (document.getElementById("email").value=="") {
			var node=document.createElement("P");
			var textnode=document.createTextNode("Inserisci il campo e-mail");
			node.appendChild(textnode);
			node.className="errRep";
			document.getElementById("email").parentNode.appendChild(node);
			return false;
		} else {
			var elem=document.getElementById("email").value;
			var pos=elem.search(/([\w]+)\@([\w]+)\.([\w])/);
			if (pos==-1) {
				var node=document.createElement("P");
				var textnode=document.createTextNode("Campo e-mail non inserito correttamente.");
				node.appendChild(textnode);
				node.className="errRep";
				document.getElementById("email").parentNode.appendChild(node);
				return false;
			}
		}
		return true;
	}
	
	function checkDataNascita() {
		if (document.getElementById("dataNascita").value=="") {
			var node=document.createElement("P");
			var textnode=document.createTextNode("Inserisci il campo Data di nascita");
			node.appendChild(textnode);
			node.className="errRep";
			document.getElementById("dataNascita").parentNode.appendChild(node);
			return false;
		} else {
			var flag=false;
			var data=document.getElementById("dataNascita").value.split("/");
			if (data.length!=3) {
				flag=true;
			} else {
				if (data[0].length!=2 || data[0].search(/\d{2}/)==-1) {
					flag=true;
				}
				if (data[1].length!=2 || data[1].search(/\d{2}/)==-1) {
					flag=true;
				}
				if (data[2].length!=4 || data[2].search(/\d{4}/)==-1) {
					flag=true;
				}
			}
			if (flag==true) {
				var node=document.createElement("P");
				var textnode=document.createTextNode("Campo Data di nascita non inserito correttamente.");
				node.appendChild(textnode);
				node.className="errRep";
				document.getElementById("dataNascita").parentNode.appendChild(node);
				return false;
			}
		}
		return true;
	}
	
	function delErrUsername() {
		var x=document.getElementById("username");
		while (x.parentNode.childNodes.length>=6) {
			x.parentNode.removeChild(x.parentNode.lastChild);
		}
	}
	
	function delErrPassword() {
		var x=document.getElementById("password");
		while (x.parentNode.childNodes.length>=6) {
			x.parentNode.removeChild(x.parentNode.lastChild);
		}
	}
	
	function delErrConfPassword() {
		var x=document.getElementById("confermaPassword");
		while (x.parentNode.childNodes.length>=6) {
			x.parentNode.removeChild(x.parentNode.lastChild);
		}
	}
	
	function delErrEmail() {
		var x=document.getElementById("email");
		while (x.parentNode.childNodes.length>=6) {
			x.parentNode.removeChild(x.parentNode.lastChild);
		}
	}
	
	function delErrDataNascita() {
		var x=document.getElementById("dataNascita");
		while (x.parentNode.childNodes.length>=6) {
			x.parentNode.removeChild(x.parentNode.lastChild);
		}
	}

