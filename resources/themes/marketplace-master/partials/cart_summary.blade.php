@if(\ShoppingCart::CountAllInstances() > 0)

    @foreach($items = \ShoppingCart::getAllInstanceItems() as $item)
        <div class="dropdown-product-item">
        <span class="dropdown-product-remove">
            <a href="{{ url('cart/quantity', [$item->getHash()]) }}"
               data-action="post" data-style="zoom-in"
               data-request_data='@json(["action"=>"removeItem"])'
               data-page_action="updateCart"
               data-toggle="tooltip"
               title="Remove item">
            <i class="icon-cross" style="color: #ff5252;"></i>
            </a>
        </span>
            <a class="dropdown-product-thumb" href="{{ url('shop', [$item->id->product->slug]) }}">
                <img src="{{ $item->id->image }}" alt="Product"></a>
            <div class="dropdown-product-info">
                <a class="dropdown-product-title"
                   href="{{ url('shop', [$item->id->product->slug]) }}">{!! $item->id->product->name !!}</a>
                <span class="dropdown-product-details">@lang('corals-marketplace-master::labels.template.cart.price')
                : {{ $item->subtotal() }}
            </span>
                <br/>
                <span class="dropdown-product-details">@lang('corals-marketplace-master::labels.template.cart.quantity')
                : {!! $item->qty !!}</span>
            </div>
        </div>
    @endforeach
    <div class="toolbar-dropdown-group">
        <div class="column"><span
                    class="text-lg">@lang('corals-marketplace-master::labels.template.cart.subtotal')</span>
        </div>
        <div class="column text-right">
            <span class="text-lg text-medium total">{{ ShoppingCart::subTotalAllInstances() }}</span>
        </div>
    </div>
    <div class="toolbar-dropdown-group">
        <div class="column"><a class="btn btn-sm btn-block btn-secondary"
                               href="{{ url('cart') }}">@lang('corals-marketplace-master::labels.template.cart.view_cart')</a>
        </div>
        <div class="column"><a class="btn btn-sm btn-block btn-success"
                               href="{{ url('checkout') }}">@lang('corals-marketplace-master::labels.template.cart.checkout')</a>
        </div>
    </div>
@else

    <b>@lang('corals-marketplace-master::labels.template.cart.have_no_item_cart')</b>
@endif
