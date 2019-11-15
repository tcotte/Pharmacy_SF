$(document).ready(function(){
    $("#excel_button").click(function () {
        let commandId = $('h3').attr('attr-id');
        console.log(commandId);
        $.ajax({
            url: Routing.generate('excel', { id: commandId }),
            type: 'GET',
            success: function (data) {
                alert(data);
            },
            error: function () {
                alert('Le fichier Excel n\'a pas été enregistré correctement');
            }
        });
    });
});