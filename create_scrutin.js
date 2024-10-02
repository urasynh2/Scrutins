function create_scrutin() {
        var question =$('#question').val()
        var option = $("input[name='option']");
        var votant = $("input[name='votant']");

        var options = {};
        option.each(function() {
            options[$(this).val()] = 0;
        });

        var nbVotes = 0;
        var votants = {};
        votant.each(function() {
            if ($(this).prop('checked')) {
                votants[$(this).val()] = parseInt($("input[id='votes_"+($(this).val())+"']").val()) + 1 ;
                nbVotes += parseInt($("input[id='votes_"+($(this).val())+"']").val()) + 1 ;
            }
        });

        if(question == ""){
            $("#message").html("Veuillez fournir une question").css("color", "red");
        }else if(Object.keys(options)[0]=="" || Object.keys(options)[1]==""){
            $("#message").html("Veuillez fournir au moins 2 options de réponses").css("color", "red");
        }else if(Object.keys(votants).length<=1){
            $("#message").html("Veuillez selectionner au moins 2 votants").css("color", "red");
        }else{
            console.log(question)
            console.log(options);
            console.log(votants);
            console.log(nbVotes);


            $.ajax({
                method: "POST",
                url: "create_scrutin.php",
                data: {
                    "question": question, 
                    "options": options,
                    "votants": votants,
                    "nbVotes": nbVotes
                }
            }).done(function(data) {
                var answer = JSON.parse(data);
                if (answer.scrutin_created) {
                    $("#message").html("Le scrutin a bien été créé").css("color", "green");
                    
                    refresh('scrutin_orga');
                    refresh('scrutin_votant');
        
                } else {
                    $("#message").html("Erreur : " + answer.message).css("color", "red");
                }
            }).fail(function() {
                $("#message").html("Erreur lors de la requête AJAX");
            });
        }
}