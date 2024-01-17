@extends('layouts.public')

@section('editable_content')
    <div class="py-5">
        @include('TroubleTicket::troubleTickets.public.create', ['troubleTicket'=>$troubleTicket])
    </div>
@endsection
