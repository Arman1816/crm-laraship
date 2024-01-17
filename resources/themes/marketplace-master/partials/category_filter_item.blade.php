<li>
    <div class="">
        <input class=""
               name="category[]" value="{{ $category->slug }}"
               type="checkbox"
               id="ex-check-{{ $category->id }}"
                {{ \Shop::checkActiveKey($category->slug,'category')?'checked':'' }}>
        <label class=""
               for="ex-check-{{ $category->id }}">
            {{ $category->name }}
            ({{ \Shop::getCategoryAvailableProducts($category->id, true)}})
        </label>
    </div>
    @if($category->hasChildren())
        <ul style="padding-left: 10px;">
            @foreach($category->children as $child)
                @include('partials.category_filter_item',['category'=>$child])
            @endforeach
        </ul>
    @endif
</li>