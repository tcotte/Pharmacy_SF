
$(document).ready(function(){
    $(".btn-success").click(function () {
        let commandId = $(this).parents(".card").attr('attr-id');
        console.log(commandId);
        $(this).parents(".card").slideUp("slow", function () {
            $.ajax({
                url: Routing.generate('treatCommand', { id: commandId }),
                type: 'POST',
                success: function (data) {
                    alert(data);
                },
                error: function () {
                    alert('La requête n\'a pas abouti');
                }
            });
        });
    });
});


