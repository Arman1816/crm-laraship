<script type="text/javascript">

    var is_cart_page = {{ Request::is('cart') ? 'true':'false' }};


    $(function () {
        $('.btn-radio').on("click", function (e) {
            $('.btn-radio').not(this).removeClass('active btn-success')
                .siblings('input').prop('checked', false)
                .siblings('.img-radio').parent('div').removeClass('selected-radio');
            $(this).addClass('active btn-success')
                .siblings('input').prop('checked', true).trigger('change')
                .siblings('.img-radio').parent('div').addClass('selected-radio');
        });

    });

    function updateCart(response) {
        if (response.level === "success" || response.level === "warning") {

            if (response.itemhash && response.quantity) {
                $('#item-' + response.itemhash + ' .cart-quantity').val(response.quantity);
            }

            if (response.action == "removeItem") {
                $('#item-' + response.itemhash).fadeOut("normal", function () {
                    $(this).remove();
                });
            } else if (response.item_total) {

                $('#item-total-' + response.itemhash).html(response.item_total);

            }

            if (response.empty == true && is_cart_page) {
                location.reload();
            } else {
                $('#total, .total').html(response.total);
                $('#cart-header-total').html(response.total);
                $('#cart-header-count').html(response.cart_count);
                $('.cart-products-count').html(response.cart_count);

            }
            refreshCartSummary();

        }

    }

    (function () {
        $('.cart-quantity').on('change', function () {
            $(this).closest('form').submit();
        });
    })();


    function refreshCartSummary() {
        var url = '{{ url('cart/summary') }}';
        $.ajax({
            type: 'GET',
            url: url,
            success: function (data) {
                $('.cart_summary').html(data.cart_summary);


            }
        });
    }

    $(document).on("change, keyup", "#sku-form input, #sku-form select", function (event) {

        let url = window.location.href + '/price-per-quantity';

        let $form = $(this).closest('form');

        let formData = new FormData($form.get(0));

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response, textStatus, jqXHR) {
                $("#price-per-quantity-check-result").html(response.message || '');
                let price = response.price;

                if (response.originalPrice && response.originalPrice != price) {
                    price = `<del class="text-muted">${response.originalPrice}</del> ${price}`
                }

                $("#sku-price").html(price);

                $("#sku_hash_id").val(response.sku_hash);
            },
            error: function (response, textStatus, jqXHR) {
            }
        });
    })
</script>
