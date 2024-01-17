<li class="dropdown locale">
    @if($current_store)
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-link"></i><span id="current_store"> {{ $current_store->name }}</span>
            @if(count($stores) > 1)
                <i class="fa fa-angle-down"></i>
            @endif
        </a>
    @else
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-link"></i><span id="current_store"> @lang('Marketplace::labels.store.all_stores') </span>
            @if(count($stores) > 1)
                <i class="fa fa-angle-down"></i>
            @endif
        </a>
    @endif
    @if(count($stores) > 1)
        <ul class="{{ $ul_class??'' }} dropdown-menu">
            @foreach ($stores as $store)
                <li class="{{ $li_class??'' }}">
                    <a href="{{ url('set-store/'.$store->id) }}">
                        <i class="fa fa-home"></i> {{ $store->name  }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</li>