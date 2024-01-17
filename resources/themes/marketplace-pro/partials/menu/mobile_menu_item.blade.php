@foreach($menus as $menu)
    @if($menu->hasChildren('active')  && $menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))?'active':'' }} {{ $menu->isRoot()?'':'has-children' }}">
            <span class="arrow collapsed" data-toggle="collapse" data-target="menu-{{$menu->id}}"
                  aria-expanded="true" role="status">
                <i class="zmdi zmdi-minus"></i>
                <i class="zmdi zmdi-plus"></i>
            </span>
            <a href="{{ url($menu->url) }}" title="Home">
                <i class="fa fa-home" aria-hidden="true"></i>{{ $menu->name }}</a>
            <div class="subCategory collapse" aria-expanded="true" role="status">
                <ul>
                    @include('partials.menu.menu_item', ['menus' => $menu->getChildren('active')])
                </ul>
            </div>
        </li>
    @elseif($menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))?'active':'' }} item home-page has-sub">

            @if($menu->hasChildren('active'))
                <span class="arrow collapsed" data-toggle="collapse" data-target="#menu-{{$menu->id}}"
                      aria-expanded="true" role="status">
                <i class="zmdi zmdi-minus"></i>
                <i class="zmdi zmdi-plus"></i>
                </span>
            @endif
            <a href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
                <span>@if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif {{ $menu->name }}</span>
            </a>
            @if($menu->hasChildren('active'))

                <div class="subCategory collapse" aria-expanded="false" role="status" id="menu-{{$menu->id}}">
                    <ul>
                        @include('partials.menu.menu_item', ['menus' => $menu->getChildren('active')])
                    </ul>
                </div>
            @endif
        </li>
    @endif
@endforeach