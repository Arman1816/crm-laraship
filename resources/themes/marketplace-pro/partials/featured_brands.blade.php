@php $brands = \Shop::getFeaturedBrands(); @endphp
@if(!$brands->isEmpty())
    <div class="section introduct-logo">
        <div class="row">
            <div class="tiva-manufacture  col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                <div class="block">
                    <div id="manufacture" class="owl-carousel owl-theme owl-loaded owl-drag">
                        @foreach($brands as $brand)
                            <div class="item">
                                <div class="logo-manu">
                                    <a href="{{ url('shop?brand[]='.$brand->slug) }}" title="{{ $brand->name }}">
                                        <img class="img-fluid" src="{{ $brand->thumbnail }}" alt="{{ $brand->name }}"/>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
