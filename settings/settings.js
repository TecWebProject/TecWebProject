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
        xmlhttp.open("GET", "/settings/script_select_provincia.php?regione=" + str, true);
        xmlhttp.send();

        document.getElementById("modSelectProvincia").innerHTML = xmlhttp.response
    }
}

// Nasconde le opzioni del selettore delle province e lo disabilita
function clearProvince() {
    document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
    document.getElementById("modSelectProvincia").disabled = true;
};

// Controllo username
function checkUsername(username) {
    // Controllo offline
    if (/^[a-zA-Z0-9]+$/.test(username) == false) {
        document.getElementById("errorModUsername").innerHTML = "Username non valido. Usare solo lettere maiuscole o minuscole o cifre.";
    }
    // Controllo online
    else {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText == "0") {
                    document.getElementById("errorModUsername").innerHTML = "Username valido";
                } else if (this.responseText == "1") {
                    document.getElementById("errorModUsername").innerHTML = "Username non disponibile";
                } else if (this.responseText == "-1") {
                    document.getElementById("errorModUsername").innerHTML = "Username non valido. Usare solo lettere maiuscole o minuscole o cifre.";
                } else {
                    console.error("Input is not valid");
                }
            }
        };
        xmlhttp.open("GET", "/settings/script_check_username.php?username=" + username, true);
        xmlhttp.send();
    }

}

function checkNome(nome) {
    var valid = /^[a-zA-Z\ ]+$/.test(nome);
    if (valid) {
        document.getElementById("errorModNome").innerHTML = "Nome valido.";
    } else {
        document.getElementById("errorModNome").innerHTML = "Nome non valido. Usare solo lettere e spazi.";
    }
}

function checkCognome(conome) {
    var valid = /^[a-zA-Z\ ]+$/.test(conome);
    if (valid) {
        document.getElementById("errorModCognome").innerHTML = "Cognome valido.";
    } else {
        document.getElementById("errorModCognome").innerHTML = "Cognome non valido. Usare solo lettere e spazi.";
    }
}

// Controllo email
function checkEmail(email) {
    // Controllo offline con regex basato su RFC822
    var valid = (/^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(email));
    if (valid) {
        document.getElementById("errorModEmail").innerHTML = "Email valida.";
    } else {
        document.getElementById("errorModEmail").innerHTML = "Email non valida.";
    }
}

// Controllo età
function checkBDay(bDay) {
    var date = new Date(bDay);
    var valid;
    //  if (bDay == "") {
    //      valid = false;
    //  } else
    if (/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(bDay)) {
        // Accetta dd/mm/yyyy, dd-mm-yyyy e dd.mm.yyyy
        valid = true;
    } else {
        valid = false;
    }

    if (valid) {
        var today = new Date()
        if (today >= date) {
            document.getElementById("errorModDataNascita").innerHTML = "Data di nascita valida.";
        } else {
            document.getElementById("errorModDataNascita").innerHTML = "Data di nascita nel futuro.";
        }
    } else {
        document.getElementById("errorModDataNascita").innerHTML = "Formato data non valido.";
    }
}

function clearError(str) {
    console.log("cancello errore " + str);
    switch (str) {
        case 'username':
            document.getElementById("errorModUsername").innerHTML = "";
            break;
        case 'email':
            document.getElementById("errorModEmail").innerHTML = "";
            break;
        case 'nome':
            document.getElementById("errorModNome").innerHTML = "";
            break;
        case 'cognome':
            document.getElementById("errorModCognome").innerHTML = "";
            break;
        case 'data':
            document.getElementById("errorModDataNascita").innerHTML = "";
            break;
        default:
            console.error("passato: " + str);
    }
}
