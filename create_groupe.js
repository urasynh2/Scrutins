function create_groupe() {
    var nomGroupe = $('#nomGroupe').val();
    var membre = $("input[name='membreGroupe']:checked");
    var membres = [];

    membre.each(function() {
        if ($(this).prop('checked')) {
            membres.push($(this).val()); 
        }
    });

    console.log(membres);

    if (nomGroupe === "") {
        $("#gmessage").html("Veuillez fournir un nom pour votre groupe").css("color", "red");
    } else if (membres.length <= 1) { // Utilisation de membres.length pour vérifier la longueur de l'array
        $("#gmessage").html("Veuillez sélectionner au moins 2 votants").css("color", "red");
    } else {
        $.ajax({
            method: "POST",
            url: "create_groupe.php",
            data: {
                'nomGroupe': nomGroupe,
                'membres': membres
            }
        }).done(function(data) {
            var answer = JSON.parse(data);
            if (answer.groupe_created) {
                $("#gmessage").html("Le groupe a bien été créé").css("color", "green");
                refresh('groupes');
            } else {
                $("#gmessage").html("Erreur : " + answer.message).css("color", "red");
            }
        }).fail(function() {
            $("#gmessage").html("Erreur lors de la requête AJAX").css("color", "red");
        });
    }
}
