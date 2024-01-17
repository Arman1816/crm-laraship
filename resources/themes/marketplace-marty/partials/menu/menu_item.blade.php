@foreach($menus as $menu)
    @if($menu->hasChildren('active') && $menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }} {{ $menu->hasChildren('active') ?'has_dropdown':'' }}">
            <a href="{{url($menu->url)}}">{{$menu->name}}</a>
            <div class="dropdowns dropdown--menu">
                <ul>
                    @include('partials.menu.menu_item', ['menus' => $menu->getChildren('active')])
                </ul>
            </div>
        </li>
    @elseif($menu->user_can_access)
        <li  class="  {{ \Request::is($menu->active_menu_url)?'active':'' }}">
            <a href="{{url($menu->url)}}">{!! $menu->name !!}</a>
        </li>
    @endif
@endforeach