@extends('layouts.public')

@section('content')
    @include('partials.page_header',['content'=>$pricing->title])
    <section class="pricing_area section--padding">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <p>{!! $pricing->rendered !!}</p>
                    </div>
                </div>
            </div>
            <div class="row">
                @foreach($products as $product)
                    <div class="col-lg-12 col-md-12">
                        <div class="pricing red">
                            <h4 class="pricing--title">{{$product->name}}</h4>
                            <div class="pricing--features">
                                <ul>
                                    <li>
                                        {{$product->description}}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    {!!   \Shortcode::compile( 'pricing',$product->id ) !!}
                @endforeach
            </div>
        </div>
    </section>
@endsection