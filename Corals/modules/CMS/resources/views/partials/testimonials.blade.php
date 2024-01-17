@section('css')
    @php
        \Assets::add(asset('assets/corals/plugins/OwlCarousel2-2.2.1/assets/owl.carousel.min.css'));
        \Assets::add(asset('assets/corals/plugins/OwlCarousel2-2.2.1/assets/owl.theme.default.min.css'));
    @endphp


    {!! \Html::style('assets/modules/cms/css/testimonials.css') !!}

    <style>
        .owl-nav {
            position: absolute;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            top: 50%;
            left: 0;
            z-index: 1000;
            width: 100%;
            height: 0;
        }

        .owl-nav > div {
            background: #1a3d63;
            color: white;
            z-index: 100;
            width: 30px;
            height: 40px;
            cursor: pointer;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .owl-nav > div:hover {
            background: #38c7f1;
            color: #1a3d63;
        }

        @media (max-width: 1270px) {
            .owl-nav .owl-prev {
                left: -10px !important;
            }

            .owl-nav .owl-next {
                right: -10px !important;
            }
        }

        .owl-nav .owl-prev {
            position: relative;
            left: -35px;
        }

        .owl-nav .owl-next {
            position: relative;
            right: -35px;
        }

        /* Review */

        .reviews {
            padding: 50px 0;
            padding-bottom: 25px;
        }

        .reviews .owl-nav {
            display: -webkit-box !important;
            display: -ms-flexbox !important;
            display: flex !important;
        }

        .reviews .container {
            max-width: 1200px !important;
        }

        @media (min-width: 768px) {
            .reviews .active.center .item {
                -webkit-transform: scale(1.05);
                -ms-transform: scale(1.05);
                transform: scale(1.05);
            }
        }

        .reviews .item {
            padding: 15px 10px;
            -webkit-transform: scale(1);
            -ms-transform: scale(1);
            transform: scale(1);
        }

        .reviews .item .card {
            border-radius: 0;
            border: none;
            -webkit-box-shadow: 0 0 10px 0 #999;
            box-shadow: 0 0 10px 0 #999;
            padding: 35px 0;
        }

        .reviews .item .card .info-col {
            margin-bottom: 25px;
            padding: 15px 25px;
        }

        .reviews .item .card .info-col .title {
            font-size: 18px;
            font-weight: 600;
        }

        .reviews .item .card .info-col .sub-title {
            font-size: 14px;
            font-weight: 600;
        }

        @media (max-width: 580px) {
            .reviews .item .card .title {
                font-size: 14px !important;
            }

            .reviews .item .card .sub-title {
                font-size: 12px !important;
            }

            .reviews .item .card li {
                margin: 0 5px 0 0 !important;
            }
        }

        .reviews .item .card ul {
            padding: 0;
            margin: 0;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .reviews .item .card ul li {
            padding: 0;
            margin: 0 10px 0 0;
            list-style: none;
            color: #ffc400;
        }

        .reviews .item .card i {
            padding: 0;
            margin: 0 10px 0 0;
            list-style: none;
            color: #ffc400;
        }

        .reviews .item .card .p-col {
            font-size: 14px;
            font-weight: 400;
            padding-left: 25px;
        }

        .reviews .item .card .img-col {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .reviews .item .card .img-col .user {
            border-radius: 50%;
            min-width: 80px;
            min-height: 80px;
            width: 80px;
            height: 80px;
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
        }

    </style>
@endsection

<section class="reviews">
    <h4 class="main-title text-center font-weight-bold pb-4">@lang('CMS::labels.testimonial.what_customer_says')</h4>
    <div class="container">
        <div class="owl-carousel owl-theme">
            @foreach($testimonials = \CMS::getTestimonialsList() as $testimonial)
                <div class="item">
                    <div class=" card">
                        <div class="row">
                            <div class="col-xs-7 col-7 info-col">
                                <h4 class="title">{!! $testimonial->title !!}</h4>
                                <h6 class="sub-title">{!! $testimonial->getProperty('job_title') !!}</h6>
                                {!! \RatingManager::drawStarts($testimonial->getProperty('rating')) !!}
                            </div>

                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4 img-col">

                                <div class="user" style="background-image: url({{$testimonial->image}})"></div>

                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12 p-col">
                                <p>{!! $testimonial->content !!}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@section('js')

    @parent
    @php
        \Assets::add(asset('assets/corals/plugins/OwlCarousel2-2.2.1/owl.carousel.min.js'));
    @endphp

    <script>
        $(".reviews .owl-carousel").owlCarousel({
            loop: true,
            margin: 35,
            nav: true,
            center: true,
            autoplay: true,
            navText: [
                '<i class="fa fa-angle-left"></i>',
                '<i class="fa fa-angle-right"></i>'
            ],
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 2
                },
                750: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    </script>
@endsection