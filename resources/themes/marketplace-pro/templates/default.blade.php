@extends('layouts.public')

@section('content')
    @include('partials.page_header')
    <div class="container" id="about-us">
        @php \Actions::do_action('pre_content',$item, $home??null) @endphp
        {!! $item->rendered !!}
    </div>
@stop
