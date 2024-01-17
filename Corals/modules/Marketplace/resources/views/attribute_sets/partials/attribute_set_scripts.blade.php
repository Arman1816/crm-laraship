<script type="text/javascript">
    let attribute_set_id = $('{{ $attribute_set_id }}').val();

    if (attribute_set_id) {
        getSetAttributes(attribute_set_id);
    }

    $(document).on('change', '{{ $attribute_set_id }}', function () {
        let attribute_set_ids = $(this).val();
        getSetAttributes(attribute_set_ids);
    });

    function getSetAttributes(attribute_set_ids) {
        if (attribute_set_ids == null || attribute_set_ids.length === 0) {
            $("{{ $set_attributes_div }}").html('');
            window.dispatchEvent(new CustomEvent("attributeSetsChanged", {detail: {attributes_list: []}}));
            return;
        }

        let url = '{{ url("marketplace/attribute-sets/attributes") }}' + '/' + "{!!($product->exists?($product->hashed_id):'')!!}";

        $.ajax({
            type: "GET",
            url: url,
            dataType: 'json',
            data: {
                attribute_set_ids: JSON.stringify(attribute_set_ids),
                field_name: '{{ $set_attribute_field_name??'' }}',
                model_class: '{!! getObjectClassForViews($product) !!}'
            },
            success: function (data) {
                $("{{ $set_attributes_div }}").html(data.rendered_fields);

                window.dispatchEvent(new CustomEvent("attributeSetsChanged", {detail: data}));
            }
        });
    }
</script>
