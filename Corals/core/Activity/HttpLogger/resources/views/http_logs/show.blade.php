@extends('layouts.crud.show')

@section('content_header')
    @component('components.content_header')
    @slot('page_title')
    {{ $title_singular }}
    @endslot

    @slot('breadcrumb')
        {{ Breadcrumbs::render('http_log_show', $title_singular) }}
    @endslot
    @endcomponent
@endsection

@section('content')
    @component('components.box',['box_class'=>'box-success'])
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Uri</th>
                            <th>Method</th>
                            <th>IP Address</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>Created at</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $httpLog->presentStripTags('uri') }}</td>
                            <td>{{ $httpLog->present('method') }}</td>
                            <td>{{ $httpLog->present('ip') }}</td>
                            <td>{!! $httpLog->present('user_id') !!}</td>
                            <td>{!! $httpLog->present('email') !!}</td>
                            <td>{!! $httpLog->present('created_at') !!}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <b>Headers</b>
                {!! formatProperties($httpLog->headers) !!}
            </div>
            <div class="col-md-4">
                <div>
                    <b>Body</b>
                    {!! formatProperties($httpLog->body) !!}
                </div>
                <div>
                    <b>Files</b>
                    {!! formatProperties($httpLog->files) !!}
                </div>
            </div>
            <div class="col-md-4">
                <b>Response</b>
                {!! formatProperties($httpLog->response) !!}
            </div>
        </div>
    @endcomponent
@endsection

