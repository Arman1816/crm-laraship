@if($user->hasPermissionTo('Marketplace::my_orders.access'))
    <li class="nav-item @if($active_tab=="orders") active @endif">
        <a class="nav-link @if($active_tab=="orders") active @endif" href="#orders" data-toggle="tab">
            <i class="fa fa-shopping-cart"></i> @lang('Marketplace::labels.shop.my_orders')
        </a>
    </li>
@endif
@if ($user->hasPermissionTo('Payment::invoices.view'))
    <li class="nav-item @if($active_tab=="invoices") active @endif">
        <a class="nav-link @if($active_tab=="invoices") active @endif" href="#invoices" data-toggle="tab">
            <i class="fa fa-file-text-o"></i>
            @lang('Marketplace::labels.shop.my_invoices')
        </a>
    </li>
@endif