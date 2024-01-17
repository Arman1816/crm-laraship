<div class="mega">
    <div class="d-md-none">
        <h6><a href="{{ url()->current().'?category='.$category['slug'] }}">{{ $category['name'] }}
{{--                ({{ $category['products_count'] }})--}}
            </a></h6>
        <hr/>
    </div>
    <div class="d-flex flex-wrap">
        @foreach($category['children'] as $child)
            <ul class="category-col">
                <li>
                    <h6><a href="{{ url()->current().'?category='.$child['slug'] }}"
                           class="">
                            {!! \Shop::checkActiveKey($child['slug'],'category')?'<i class="fa fa-check text-success"></i>':'' !!}
                            {{ $child['name'] }}
{{--                            ({{ $child['products_count'] }})--}}
                        </a></h6>
                    @if(count($child['children']))
                        <ul class="first-level-sub-cat">
                            @foreach($child['children'] as $subChild)
                                <li>
                                    <a href="{{ url()->current().'?category='.$subChild['slug'] }}">
                                        {!! \Shop::checkActiveKey($subChild['slug'],'category')?'<i class="fa fa-check text-success"></i>':'' !!}
                                        {{ $subChild['name'] }}
{{--                                        ({{ $subChild['products_count'] }})--}}
                                    </a>
                                    @if(count($subChild['children']))
                                        <ul>
                                            @foreach($subChild['children'] as $subChild2)
                                                <li>
                                                    <a href="{{ url()->current().'?category='.$subChild2['slug'] }}">
                                                        {!! \Shop::checkActiveKey($subChild2['slug'],'category')?'<i class="fa fa-check text-success"></i>':'' !!}
                                                        {{ $subChild2['name'] }}
{{--                                                        ($subChild2['products_count'])--}}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            </ul>
        @endforeach
    </div>
</div>