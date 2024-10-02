function inscription() {
    if (! isValidEmail($('#iemail').val())){
        $("#imessage").html("Veuillez fournir un email correct");
    }else if( ($('#ipassword').val().length)==0 || ($('#ipassword2').val().length)==0 ){
        $("#imessage").html("Veuillez fournir un mot de passe");
    }else{
        $.ajax({
            method: "POST",
            url: "inscription.php",
            data: {
                "iemail": $('#iemail').val(),
                "ipassword": $('#ipassword').val(),
                "ipassword2": $('#ipassword2').val()
            }
        }).done(function(data) {
            var reponse = JSON.parse(data);
            if(reponse.exists){
                $("#imessage").html("L'email "+ reponse.email + " est deja inscrit");
            }else if(!reponse.passwords_match){
                $("#imessage").html("Les mots de passe ne correspondent pas");
            }else{
                $("#imessage").html("Inscription réussie");

                openTab('connexion');
            }
        }).fail(function() {
            $("#imessage").html("FAIL");
        });
    }
}

function connexion() {
    if (! isValidEmail($('#cemail').val())){
        $("#cmessage").html("Veuillez fournir un email correct");
    }else if( ($('#cpassword').val().length)==0 ){
        $("#cmessage").html("Veuillez fournir un mot de passe");
    }else{
        $.ajax({
            method: "POST",
            url: "connexion.php",
            data: {
                "cemail": $('#cemail').val(),
                "cpassword": $('#cpassword').val()
            }
        }).done(function(data) {
            var reponse = JSON.parse(data);
            if(reponse.registered){
                if(reponse.password){
                    $("#cmessage").html("Connecté en tant que "+ reponse.email );
                    window.location.href = "acceuil.php";
                }else{
                    $("#cmessage").html("Mot de passe incorrect");
                }
            }else{
                $("#cmessage").html("L'email "+ reponse.email+ " n'est pas inscrit" );
            }
        }).fail(function() {
            $("#cmessage").html("FAIL");
        });
    }
}


// Fonction pour choisir entre inscription et connexion (trouvée sur internet)
function openTab(tabName) {
    var i, tabcontent;
    tabcontent = document.getElementsByClassName("tab");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    document.getElementById(tabName + "Tab").style.display = "block";
}

window.onload = function() {
    openTab('inscription');
};

function isValidEmail(email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}