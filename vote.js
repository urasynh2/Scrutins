function vote(email, idVote, option) {
    $.ajax({
        method: "POST",
        url: "vote.php",
        data: {
            'votant': email,
            'id': idVote,
            'option': option
        }
    })
    .done(function(data) {

        refresh('scrutin_orga');
        refresh('scrutin_votant');


    })
    .fail(function() {


    });
}
