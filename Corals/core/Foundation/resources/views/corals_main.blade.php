@stack('partial_js')

<script type="text/javascript">
    if (typeof CKEDITOR !== "undefined") {
        CKEDITOR.config.language = '{{ app()->getLocale() }}'
    }

    function initSelect2ajax() {
        $(".select2-ajax").each(function () {
            var element = $(this);

            let parent = $(this).data('select2_parent');
            let isPublic = $(this).data('is_public');
            element.select2({
                dropdownParent: parent ? $(parent) : $('body'),
                ajax: {
                    url: isPublic ? '{{ url('utilities/select2-public') }}' : '{{ url('utilities/select2') }}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            query: params.term, // search term
                            columns: $(this).data('columns'),
                            key_column: $(this).data('key_column'),
                            textColumns: $(this).data('text_columns'),
                            model: $(this).data('model'),
                            where: $(this).data('where'),
                            scopes: $(this).data('scopes'),
                            orWhere: $(this).data('or_where'),
                            resultMapper: $(this).data('result_mapper'),
                            join: $(this).data('join'),
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2,
                allowClear: true
            });

            var selected = element.data('selected');

            if (selected.length) {
                $.ajax({
                    url: isPublic ? '{{ url('utilities/select2-public') }}' : '{{ url('utilities/select2') }}',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'json',
                    delay: 250,
                    data: {
                        selected: selected,
                        columns: element.data('columns'),
                        key_column: $(this).data('key_column'),
                        textColumns: element.data('text_columns'),
                        model: element.data('model'),
                        where: element.data('where'),
                        scopes: $(this).data('scopes'),
                        orWhere: element.data('or_where'),
                        resultMapper: element.data('result_mapper'),
                        join: element.data('join'),
                    },
                    success: function (data, textStatus, jqXHR) {
                        // create the option and append to Select2
                        for (var index in data) {
                            if (data.hasOwnProperty(index)) {
                                var selection = data[index];
                                var option = new Option(selection.text, selection.id, true, true);
                                element.append(option).trigger('change');
                            }
                        }
                    }
                });
            }
        })
    }


</script>

@if(config('notification.broadcast_enabled'))
    @auth
        <script src="{{config('notification.laravel_echo_domain')}}/socket.io/socket.io.js"></script>
        <script>
            function includeScriptFile(scripName) {
                let script = document.createElement('script');
                script.src = window.base_url + '/' + scripName;
                script.type = 'text/javascript';

                $('#laravel_echo_js_scripts').before(script);
            }
        </script>

        <script id="laravel_echo_js_scripts">
            if (typeof (io) !== 'undefined') {
                includeScriptFile('assets/core/compiled/js/laravel-echo-setup.js');
            }

            if (typeof (io) !== 'undefined') {
                window.Echo.private(`broadcasting.user.{{user()->hashed_id}}`)
                    .listen('.broadcasting.user', function (e) {
                        //replace title with new notification count!
                        let tabTitle = $('title');

                        let newTitle = tabTitle.text().replace(/\d+/, e.unread_notifications_count);

                        tabTitle.text(newTitle);

                        if (window.themeBroadCast) {
                            themeBroadCast('.broadcasting.user', e);
                        }

                        themeNotify({
                            'level': "info",
                            'message': `${e.notification.title}`
                        });
                    });
            }
        </script>
    @endauth
@endif
