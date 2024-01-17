@extends('layouts.master')

@section('title',$title)

@section('css')
    {!! \Html::style('assets/corals/plugins/nestable/nestable.css') !!}
@endsection
@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_categories') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            @component('components.box')
                <div class="dd" id="sortable-tree">
                    <ol class="dd-list">
                        @include('Marketplace::categories.hierarchy_item', ['categories'=> \Marketplace::getCategoriesRootList()])
                    </ol>
                </div>
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
    {!! \Html::script('assets/corals/plugins/nestable/jquery.nestable.js') !!}

    <script type="text/javascript">
        var original_tree = '';

        var updateTree = function (e) {
            var tree = $(e.target).nestable('serialize');
            tree = JSON.stringify(tree);

            if (_.isEqual(original_tree, tree)) {
                return;
            }

            var formData = new FormData();
            formData.append('tree', tree);

            var url = '{!! url(config('marketplace.models.category.resource_url').'/update-tree') !!}';

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (data, textStatus, jqXHR) {
                    original_tree = JSON.stringify($('#sortable-tree').nestable('serialize'));
                    themeNotify(data);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    themeNotify(data);
                }
            });
        };

        var nestable = $('#sortable-tree').nestable();

        nestable.nestable('collapseAll');

        nestable.on('change', updateTree);

        original_tree = JSON.stringify($('#sortable-tree').nestable('serialize'));
    </script>
@endsection