$(document).ready(function () {
    initThemePublicLayoutElements();


    $('div.quantity .bootstrap-touchspin-up').on("click", function (e) {

        $quantity_field = $(this).closest('.quantity').find('input[name="quantity"]');

        $quantity_field.val(parseInt($quantity_field.val()) + 1).trigger('change');

    });

    $('div.quantity .bootstrap-touchspin-down').on("click", function (e) {
        if (parseInt($quantity_field.val()) > 0) {
            $quantity_field = $(this).closest('.quantity').find('input[name="quantity"]');
            $('input[name="quantity"]').val(parseInt($quantity_field.val()) - 1).trigger('change');

        }

    });

});

$(document).ajaxComplete(function (event, xhr, settings) {
    initThemePublicLayoutElements();
});
