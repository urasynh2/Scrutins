function close_scrutin(id,orga) {
    $.ajax({
        method: "POST",
        url: "close_scrutin.php",
        data: {
            "email": orga ,
            "id" : id,

        }
    }).done(function(data) {
        if(data=='1'){
            console.log('oui chef');

            refresh('scrutin_orga');
            refresh('scrutin_votant');


        }
    }).fail(function() {

    });
}