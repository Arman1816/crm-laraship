<style>
    .import-description-table .table {
        font-size: small;
    }

    .import-description-table .table > tbody > tr > td {
        padding: 4px;
    }

    .required-asterisk {
        color: red;
        font-size: 100%;
        top: -.4em;
    }
</style>

<div>
    {!! CoralsForm::openForm(null, ['url' => url($resource_url.'/upload-import-file'), 'files' => true]) !!}
    {!! CoralsForm::file('file', 'Marketplace::import.labels.file') !!}
    {!! CoralsForm::text('images_root','Marketplace::import.labels.images_root', true,null,
        ['help_text'=>'Marketplace::import.labels.images_root_help']) !!}

    @if($target == 'product')
        {!! CoralsForm::checkbox('clear_images', 'Marketplace::import.labels.clear_images', false) !!}
        {!! \Store::getStoreFields(null, true,'#global-modal') !!}
    @endif

    {!! CoralsForm::formButtons('Marketplace::import.labels.upload_file', [], ['show_cancel' => false]) !!}
    {!! CoralsForm::closeForm() !!}

    {!! CoralsForm::link(url($resource_url.'/download-import-sample'),
    trans('Marketplace::import.labels.download_sample'),
    ['class' => '']) !!}
</div>
<hr/>
<h4>@lang('Marketplace::import.labels.column_description')</h4>
<div class="table-responsive import-description-table">
    <table class="table table-striped">
        <thead>
        <tr>
            <th style="width: 120px;">@lang('Marketplace::import.labels.column')</th>
            <th>@lang('Marketplace::import.labels.description')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($headers as $column => $description)
            <tr>
                <td>{{ $column }}</td>
                <td>{!! $description !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<script>
    initSelect2ajax();
</script>
