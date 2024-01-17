@if(\ShoppingCart::CountAllInstances() > 0)
    @foreach($items = \ShoppingCart::getAllInstanceItems() as $item)
        <div class="cart_product"  >
            <div class="product__info">
                <div class="thumbn">
                    <img src="{{$item->id->image}}" alt="cart product thumbnail">
                </div>
                <div class="info">
                    <a class="title" href="{{ url('shop', [$item->id->product->slug]) }}">
                        {!! $item->id->product->name !!}
                    </a>
                </div>
            </div>
            <div class="product__action">
                <a href="{{ url('cart/quantity', [$item->getHash()]) }}" data-action="post"
                   data-style="zoom-in" data-request_data='@json(["action"=>"removeItem"])'
                   data-page_action="updateCart"
                   data-toggle="tooltip"
                   title="Remove item">
                    <span class="lnr lnr-trash"></span>
                </a>
                <p>{{ $item->subtotal() }}</p>
            </div>
        </div>
    @endforeach
    <div class="total">
        <p>
            <span>
                @lang('corals-marketplace-marty::labels.template.cart.subtotal')
            </span>
            {{ \ShoppingCart::totalAllInstances() }}
        </p>
    </div>
    <div class="cart_action">
        <a class="go_cart" href="{{ url('cart') }}">
            @lang('corals-marketplace-marty::labels.template.cart.view_cart')</a>
        <a class="go_checkout" href="{{ url('checkout') }}">
            @lang('corals-marketplace-marty::labels.template.cart.checkout')
        </a>
    </div>
@else
    <div class="total">
        <p class="text-left">@lang('corals-marketplace-marty::labels.template.cart.have_no_item_cart')</p>
    </div>
@endif
