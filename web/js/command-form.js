
    $(document).ready(function () {
        $('.product-select').each(function (product) {
            let productId = $(this).val();
            let productSelect = $(this);
            $.get(Routing.generate('getProductInfo', {id: productId}), function (data) {
                product = JSON.parse(data);
                productSelect.after(
                    "<div class='row'>" +
                    "<div class='col-md-4'>" +
                    "<b>" + product.designation + "</b>" +
                    "</div><div class='col-md-2'>" +
                    product.supplier +
                    "</div><div class='col-md-2'>" +
                    product.reference +
                    "</div><div class='col-md-2'>" +
                    product.price + "€" +
                    "</div><div class='col-md-2'>" +
                    product.cdt +
                    "</div></div>"
                );

            });
        });
        $('div[id^="platformbundle_command_commandProducts_"]').addClass("row");
        $('div[id^="platformbundle_command_commandProducts_"] div:first-child').wrap(
            "<div class='col-md-11'></div>"
        );
        $('div[id^="platformbundle_command_commandProducts_"] div:nth-child(2)').wrap(
            "<div class='col-md-1' style='padding-left: 0px; padding-right:40px;'></div>"
        );

        $('div[id^="platformbundle_command_commandProducts_"] div:nth-child(2)').addClass('number-type');

    });
