@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
        @slot('page_title')
            {{ $title_singular }}
        @endslot

        @slot('breadcrumb')
            {{ Breadcrumbs::render('store_enroll') }}
        @endslot
    @endcomponent
@endsection

@section('content')
    @component('components.box',['box_class'=>'box-success card-success'])
        <div class="row">
            <div class="col-md-12">
                    {!! CoralsForm::openForm() !!}

                    <div class="content my-3">
                        {!! \Settings::get('marketplace_general_enroll_terms','') !!}
                    </div>

                {!! CoralsForm::checkbox('accept_terms', 'Marketplace::attributes.store.accept_terms' ) !!}

                {!! CoralsForm::formButtons(trans('Marketplace::labels.store.enroll_confirm'),[],['href'=>url('dashboard')]) !!}

                    {!! CoralsForm::closeForm() !!}
            </div>
        </div>
    @endcomponent
@endsection

