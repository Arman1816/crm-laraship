@extends('layouts.crud.create_edit')



@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('marketplace_shipping_create_edit') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @parent
    <div class="row">
        <div class="col-md-8">
            @component('components.box')
                {!! CoralsForm::openForm(null,['url' => url($resource_url.'/upload'),'method'=>'POST','files'=>true]) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-warning">
                            <p>@lang('Marketplace::labels.shipping.upload_warning')</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        {!! CoralsForm::file('shipping_import_file', 'Marketplace::attributes.shipping.upload_file',true,['help_text'=>trans('Marketplace::labels.shipping.download_sample_upload',['sample_upload_url'=>url('uploads/sample/marketplace_shippings_shipping.xlsx')])]) !!}

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        {!! CoralsForm::formButtons() !!}
                    </div>
                </div>
                {!! CoralsForm::closeForm() !!}
            @endcomponent
        </div>
    </div>
@endsection

@section('js')
@endsection
