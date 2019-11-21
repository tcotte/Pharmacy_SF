$('.product-collection').collection({
    up: '<a href="#" class="btn btn-light"><i class="fas fa-long-arrow-alt-up"></i></a>',
    down: '<a href="#" class="btn btn-light"><i class="fas fa-long-arrow-alt-down"></i></a>',
    add: '<a href="#" class="btn btn-primary"><i class="fas fa-plus-circle"></i></a>',
    remove: '<a href="#" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>',
});


$(document).ready(function () {

    let btnValid = $('#platformbundle_category_Valider');
    btnValid.after('<button class="btn btn-danger" id="remove-form">Supprimer</button>');

    $("#remove-form").click(function (e) {
        e.preventDefault();
        theHREF = $(this).attr("href");
        $("#confirmModal").modal("show");
    });

    $("#confirmModalNo").click(function (e) {
        $("#confirmModal").modal("hide");
    });

    $("#confirmModalYes").click(function (e) {
        let categoryId = $('h3').attr('attr-id');
        $.ajax({
            url: Routing.generate('deleteFormulary', { id: categoryId}),
            type: 'GET',
            success: function (data) {
                $("#confirmModal").modal("hide");
                window.document.location = Routing.generate('app');
                // alert(data);
            },
            error: function () {
                alert('La requête n\'a pas abouti');
            }
        });
    });


});