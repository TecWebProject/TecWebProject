// Esegue query per le province e aggiorna il selettore
function showProvince() {

    var regione = document.getElementById("modSelectRegione").value;

    var provincia = document.getElementById("modSelectProvincia").value;

    if (regione == "") {
        // Scelto "Seleziona Provincia"
        document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
        document.getElementById("modSelectProvincia").disabled = true;
    } else {
        // Stringa valida, eseguo query
        // IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>' + this.responseText;
                document.getElementById("modSelectProvincia").disabled = false;
            }
        };
        xmlhttp.open("GET", "script_select_provincia.php?regione=" + regione, true);
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
// Controllo username
function checkUsername(username) {

    // Controllo offline
    if (/^[a-zA-Z0-9]+$/.test(username) == false) {
        document.getElementById("errorModUsername").innerHTML = "Username non valido. Usare solo lettere maiuscole o minuscole o cifre.";
    }
    // Controllo online
    else {
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
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

function checkNome() {

    var input = document.getElementById("modNome");

    if (input != null) {

        var nome = input.value;

        var valid = new RegExp("^[A-Za-zàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\s]+$").test(nome);

        if (valid) {
            document.getElementById("errorModNome").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
        } else {
            document.getElementById("errorModNome").innerHTML = "Nome non valido. Usare solo lettere e spazi.";
        }

        return valid;
    }

    return true;
}

function checkCognome() {

    var input = document.getElementById("modCognome");

    if (input != null) {

        var cognome = input.value;

        var valid = new RegExp("^[A-Za-zàèìòùÀÈÌÒÙáéíóúýÁÉÍÓÚÝâêîôûÂÊÎÔÛãñõÃÑÕäëïöüÿÄËÏÖÜŸçÇßØøÅåÆæœ\s$]+$").test(cognome);
        if (valid) {
            document.getElementById("errorModCognome").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
        } else {
            document.getElementById("errorModCognome").innerHTML = "Cognome non valido. Usare solo lettere e spazi.";
        }
        return valid;
    }

    return true;
}

// Controllo email
function checkEmail() {

    var input = document.getElementById("modEmail");

    if (input != null) {
        var email = input.value;

        // Controllo offline con regex basato su RFC822
        var valid = (/^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/.test(email));
        if (valid) {
            document.getElementById("errorModEmail").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
        } else {
            document.getElementById("errorModEmail").innerHTML = "Email non valida.";
        }

        return valid
    }
    return true;
}

function checkPassword() {

    var input = document.getElementById("modPassword");

    if (input != null) {

        var password = input.value;

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

    return true;
}

function checkPasswordCheck() {

    var input = document.getElementById("modPasswordCheck");

    if (input != null) {

        var passwordCheck = input.value;

        var valid = document.getElementById("modPassword").value === passwordCheck;

        if (valid) {
            document.getElementById("errorModPasswordCheck").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
        } else {
            document.getElementById("errorModPasswordCheck").innerHTML = "Le due password non corrispondono.";
        }

        return valid;
    }

    return true;

}

// Controllo età
function checkBDay() {

    if (
        document.getElementById("modDataNascitaGiorno") == null ||
        document.getElementById("modDataNascitaMese") == null ||
        document.getElementById("modDataNascitaAnno") == null
    )
        return true;

    var d = document.getElementById('modDataNascitaGiorno').value;
    var m = document.getElementById('modDataNascitaMese').value;
    var y = document.getElementById('modDataNascitaAnno').value;
    var date = new Date(y, m, d);
    var stringDate = d + "/" + m + "/" + y;
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
        var today = new Date();
        if (today >= date) {
            document.getElementById("errorModDataNascita").innerHTML = "<img src='correctEntry.png' class='modCorrectEntry'/>";
        } else {
            valid = false;
            document.getElementById("errorModDataNascita").innerHTML = "Data di nascita nel futuro.";
        }
    } else {
        document.getElementById("errorModDataNascita").innerHTML = "Formato data non valido.";
    }

    return valid;
}

function checkBio() {

    var input = document.getElementById("modTextAreaBio")

    if (input != null) {

        var str = input.value;

        document.getElementById("errorModBio").innerHTML = "";
        var valid = true;
        if (/<script/.test(str)) {
            document.getElementById("errorModBio").innerHTML += "<li>Non è possibilie inserire script nel testo.</li>";
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

    return true;
}

function checkProvenienza() {

    var regione = document.getElementById("modSelectRegione");
    var provincia = document.getElementById("modSelectProvincia");

    if (regione == null && provincia == null) {
        return true;
    }

    if (regione.value == null || provincia.value == null) {
        document.getElementById("errorModProvenienza").innerHTML = "Non è possibile selezionare solo uno dei due campi per la provenienza.";
        return false;
    }

    if (regione.value != null && provincia.value != null) {
        return true;
    }

}

function clearError(str) {
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
        case 'provenienza':
            document.getElementById("errorModProvenienza").innerHTML = "";
            break;
        default:
            console.error("passato: " + str);
    }
}

function checkForm() {

    var nome = checkNome();
    var cognome = checkCognome();
    var email = checkEmail();
    var password = checkPassword();
    var passwordCheck = checkPasswordCheck();
    var bDay = checkBDay();
    var bio = checkBio();
    var provenienza = checkProvenienza();

    return (nome && cognome && email && password && passwordCheck && bDay && bio && provenienza);
}
