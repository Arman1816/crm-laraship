@if($layout !="auth")
    <!-- menu mobie left -->
    <div class="mobile-top-menu d-md-none">
        <button type="button" class="close" aria-label="Close">
            <i class="fa fa-close" style="font-size:12px"></i>
        </button>
        <div class="tiva-verticalmenu block">
            <div class="box-content block-content">
                <div class="verticalmenu" role="navigation">
                    <ul class="menu level1">
                        @if($layout =="public")
                            @foreach(\Shop::getActiveCategories() as $category)
                                <li class="{{ $hasChildren = $category->hasChildren()?'has-children':'' }} item  parent">
                                    @if($hasChildren)

                                        <span class="arrow collapsed" data-toggle="collapse"
                                              data-target="#category-{{$category->slug}}"
                                              aria-expanded="true" role="status">
                                            <i class="zmdi zmdi-minus"></i>
                                            <i class="zmdi zmdi-plus"></i>
                                        </span>

                                        <a href="#" class="hasicon" title=" {{ $category->name }}">
                                            {{ $category->name }}
                                            <span>({{
                                            \Shop::getCategoryAvailableProducts($category->id, true)
                                            }})
                                            </span>
                                        </a>
                                    @else
                                        <a class="hasicon" title=" {{ $category->name }}"
                                           href="{{url('shop?category='.$category->slug)}}">
                                            {{ $category->name }}
                                            <span>
                                        ({{ \Shop::getCategoryAvailableProducts($category->id, true)}})
                                        </span>
                                        </a>
                                    @endif


                                    @if($hasChildren)
                                        <div class="subCategory collapse" id="category-{{$category->slug}}"
                                             aria-expanded="false"
                                             role="status">
                                            <div class="menu-items">
                                                <ul>
                                                    @foreach($category->children as $child)
                                                        <li class="item">
                                                            <a href="{{ url('shop?category='.$child->slug) }}"
                                                               title="">{{ $child->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        @else

                            @auth
                                @php $vendor_role = \Settings::get('marketplace_general_vendor_role', '') @endphp
                                @if ($vendor_role  && !user()->hasRole($vendor_role))
                                    {!! '<a href="' . url('marketplace/store/enroll') . '" class="btn btn-info btn-block">'.trans('Marketplace::labels.store.become_a_seller').'</a>' !!}

                                @endif
                            @endauth
                            @include('partials.menu.mobile_menu_item', ['menus' => Menus::getMenu('sidebar','active')])

                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- menu mobie right -->
<div id="mobile-pagemenu" class="mobile-boxpage d-flex hidden-md-up active d-md-none">
    <div class="content-boxpage col">
        <div class="box-header d-flex justify-content-between align-items-center">
            <div class="title-box">@lang('corals-marketplace-pro::labels.template.home.menu')</div>
            <div class="close-box">@lang('corals-marketplace-pro::labels.template.home.close')</div>
        </div>
        <div class="box-content">
            <nav>
                <!-- Brand and toggle get grouped for better mobile display -->
                <div id="megamenu" class="clearfix">
                    <ul class="menu level1">
                        @include('partials.menu.mobile_menu_item', ['menus' => Menus::getMenu('frontend_top','active')])
                        @auth
                            <li class="item  has-sub">
                            <span class="arrow collapsed" data-toggle="collapse"
                                  data-target="#profile"
                                  aria-expanded="true" role="status">
                                    <i class="zmdi zmdi-minus"></i>
                                    <i class="zmdi zmdi-plus"></i>
                                </span>
                                <a href="#">
                                    {{ user()->name }}

                                </a>
                                <div class="subCategory collapse" id="profile" aria-expanded="false" role="status"
                                     style="">

                                    <ul>
                                        <li class="">
                                            <a href="{{ url('dashboard') }}">
                                                <i class="fa fa-dashboard fa-fw"></i>
                                                @lang('corals-marketplace-pro::labels.partial.dashboard')
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="{{ url('profile') }}"><i
                                                        class="fa fa-user fa-fw"></i> @lang('corals-marketplace-pro::labels.partial.profile')
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('logout') }}" data-action="logout">
                                                <i class="fa fa-sign-out fa-fw"></i> @lang('corals-marketplace-pro::labels.partial.logout')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @else
                            <li class="item  has-sub">
                            <span class="arrow collapsed" data-toggle="collapse"
                                  data-target="#profile"
                                  aria-expanded="true" role="status">
                                    <i class="zmdi zmdi-minus"></i>
                                    <i class="zmdi zmdi-plus"></i>
                                </span>
                                <a href="#">
                                    @lang('corals-marketplace-pro::labels.partial.account')

                                </a>
                                <div class="subCategory collapse" id="profile" aria-expanded="false" role="status"
                                     style="">

                                    <ul>
                                        <li class="">
                                            <a href="{{ route('login') }}">
                                                <i class="fa fa-sign-in fa-fw"></i>
                                                @lang('corals-marketplace-pro::labels.partial.login')
                                            </a>
                                        </li>
                                        <li class="">
                                            <a href="{{ route('register') }}">
                                                <i class="fa fa-user fa-fw"></i>
                                                @lang('corals-marketplace-pro::labels.partial.register')
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>