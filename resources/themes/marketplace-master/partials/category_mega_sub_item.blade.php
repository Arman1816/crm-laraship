<li>
    <a href="{{ url()->current().'?category='.$category['slug'] }}">
        {!! \Shop::checkActiveKey($category['slug'],'category')?'<i class="fa fa-check text-success"></i>':'' !!}
        {{ $category['name'] }}
{{--        ({{ $category['products_count'] }})--}}
    </a>
    @if(count($category['children']))
        <ul>
            @foreach($category['children'] as $child)
                @include('partials.category_mega_sub_item',['category'=>$child])
            @endforeach
        </ul>
    @endif
</li>