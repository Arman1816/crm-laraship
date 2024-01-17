@extends('layouts.public')

@section('content')
    @include('partials.page_header')

    <div class="container padding-bottom-3x mb-1">
        <div class="row">
            <div class="col-md-9 flex-xs-first main-blogs col-lg-8 order-lg-1">
                {!! $item->rendered !!}
            </div>
            <div style="background-color: #091E08" class="sidebar-3 sidebar-collection col-lg-2 col-md-2 col-sm-2 order-lg-2">
                @include('partials.sidebar')
            </div>
        </div>
    </div>
@stop
