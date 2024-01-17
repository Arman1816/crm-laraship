@foreach($menus as $menu)
    @if($menu->hasChildren('active')  && $menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }} {{ $menu->isRoot()?'':'has-children' }}">
          <span>
          <a href="{{ url($menu->url) }}">
              <span>@if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif {{ $menu->name }}</span>
          </a>
              <span class="sub-menu-toggle"></span>
          </span>
            <ul class="offcanvas-submenu">
                @include('partials.menu.menu_item', ['menus' => $menu->getChildren('active')])
            </ul>
        </li>
    @elseif($menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}">
            <a href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
                <span>@if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif {{ $menu->name }}</span>
            </a>
        </li>
    @endif
@endforeach