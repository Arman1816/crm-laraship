@foreach($menus as $menu)
    @if($menu->hasChildren('active') && $menu->user_can_access)
        <li class="treeview {{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean') ?'active menu-open':'' }}">
            <a class="hover_menu"  href="#">
                @if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif <span>{{ $menu->name }}</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul  class="hover_menu treeview-menu">
                @include('partials.menu.menu_item', ['menus'=>$menu->getChildren('active')])
            </ul>
        </li>
    @elseif($menu->user_can_access)
        <li class="{{ \Request::is(explode(',',$menu->active_menu_url))|| $menu->getProperty('always_active',false,'boolean')?'active':'' }}">
            <a style="hover_menu" href="{{ url($menu->url) }}" target="{{ $menu->target??'_self' }}">
                @if($menu->icon)<i class="{{ $menu->icon }} fa-fw"></i>@endif <span>{{ $menu->name }}</span>
            </a>
        </li>
    @endif
@endforeach

<style>
.hover_menu:hover{
    background-color: green;
    border-top-right-radius: 50px;
    border-bottom-right-radius: 50px;
    transform: scale(1.1);
}
</style>