@extends('layouts.public')
@section('css')
    <style>
        .btn {
            padding: 15px;
        }

        .btn-sm {
            padding: 5px;
            line-height: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="py-5">
        @include('TroubleTicket::troubleTickets.public.create', ['troubleTicket'=>$troubleTicket])
    </div>
@endsection
