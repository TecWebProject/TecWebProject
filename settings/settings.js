function showProvince(str) {
    if (str == "") {
        document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
        document.getElementById("modSelectProvincia").disabled = true;
    } else {
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

function hideProvince(document) {
    document.getElementById("modSelectProvincia").innerHTML = '<option value="">Seleziona provincia</option>';
    document.getElementById("modSelectProvincia").disabled = true;
};
