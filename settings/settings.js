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
        xmlhttp.open("GET", "script_select_provincia.php?regione=" + str, true);
        xmlhttp.send();
        document.getElementById("modSelectProvincia").innerHTML = xmlhttp.response
    }
}

// Nasconde le opzioni del selettore delle province e lo disabilita
function clearProvince() {
    if (document.getElementById("modSelectProvincia").selectedIndex == 0) {
        document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
        document.getElementById("modSelectProvincia").disabled = true;
    }
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
                    document.getElementById("errorModUsername").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
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
    var accentedCharacters = "àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ";
    var valid = new RegExp("^[A-Za-z" + accentedCharacters + "\s]+").test(nome);
    if (valid) {
        document.getElementById("errorModNome").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
    } else {
        document.getElementById("errorModNome").innerHTML = "Nome non valido. Usare solo lettere e spazi.";
    }

    return valid;
}

function checkCognome(cognome) {
    var accentedCharacters = "àèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ";
    var valid = new RegExp("^[A-Za-z" + accentedCharacters + "\s]+").test(cognome);
    if (valid) {
        document.getElementById("errorModCognome").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
    } else {
        document.getElementById("errorModCognome").innerHTML = "Cognome non valido. Usare solo lettere e spazi.";
    }

    return valid;
}

// Controllo email
function checkEmail(email) {
    // Controllo offline con regex basato su RFC822
    var valid = (/^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(email));
    if (valid) {
        document.getElementById("errorModEmail").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
    } else {
        document.getElementById("errorModEmail").innerHTML = "Email non valida.";
    }

    return valid
}

function checkPassword(password) {
    var length = password.length;
    var specialChar = password.match(/[!@#\$%\^\&*\)\(+=._-]*/);

    valid = true;

    //TODO lunghezza password e caratteri speciali
    // if (length < 8 || special == false) {
    //     valid = false;
    // }

    if (valid) {
        document.getElementById("errorModPassword").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
    } else {
        document.getElementById("errorModPassword").innerHTML = "Password non sicura. Usa almeno 8 caratteri, tra cui almeno uno di .!@#$%^&*()_+-=";
    }

    return valid;
}

function checkPasswordCheck(passwordCheck) {
    var valid = document.getElementById("modPassword").value === passwordCheck;

    if (valid) {
        document.getElementById("errorModPasswordCheck").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
    } else {
        document.getElementById("errorModPasswordCheck").innerHTML = "Le due password non corrispondono.";
    }

    return valid;

}

// Controllo età
function checkBDay(bDay) {
    var d = document.getElementById('modDataNascitaGiorno').value;
    var m = document.getElementById('modDataNascitaMese').value;
    var y = document.getElementById('modDataNascitaAnno').value;
    var date = new Date(y, m, d);
    var stringDate = d + "/" + m + "/" + y
    var valid;
    //  if (bDay == "") {
    //      valid = false;
    //  } else
    if (/^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[1,3-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/.test(stringDate)) {
        // Accetta dd/mm/yyyy, dd-mm-yyyy e dd.mm.yyyy
        valid = true;
    } else {
        valid = false;
    }

    if (valid) {
        var today = new Date()
        if (today >= date) {
            document.getElementById("errorModDataNascita").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
        } else {
            document.getElementById("errorModDataNascita").innerHTML = "Data di nascita nel futuro.";
        }
    } else {
        document.getElementById("errorModDataNascita").innerHTML = "Formato data non valido.";
    }

    return valid;
}

function checkBio(str) {
    document.getElementById("errorModBio").innerHTML = "";
    var valid = true;
    if (/<script/.test(str)) {
        document.getElementById("errorModBio").innerHTML += "<li>Non è possibilie inserire script JavaScript nel testo.</li>";
        valid = false;
    }
    if (/<input/.test(str)) {
        document.getElementById("errorModBio").innerHTML += "<li>Non è possibilie inserire tag HTML input nel testo.</li>";
        valid = false;
    }
    if (/<form/.test(str)) {
        document.getElementById("errorModBio").innerHTML += "<li>Non è possibilie inserire tag HTML form nel testo.</li>";
        valid = false;
    }
    if (/style[\s]*\=/.test(str)) {
        document.getElementById("errorModBio").innerHTML += "<li>Non è possibilie modificare lo stile dei tag HTML.</li>";
        valid = false;
    }
    if (/\/\>/.test(str)) {
        document.getElementById("errorModBio").innerHTML += "<li>Non è possibilie modificare la chiusura dei tag HTML del testo.</li>";
        valid = false;
    }

    return valid;
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
        case 'bio':
            document.getElementById("errorModBio").innerHTML = "";
            break;
        case 'password':
            document.getElementById("errorModPassword").innerHTML = "";
            break;
        case 'passwordCheck':
            document.getElementById("errorModPasswordCheck").innerHTML = "";
            break;
        default:
            console.error("passato: " + str);
    }
}

function checkForm() {

    console.log("Definire checkForm()");

    return true;
}
