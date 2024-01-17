<li>
    <label class="check">
        <input class=""
               name="category[]" value="{{ $category->slug }}"
               type="checkbox"
               id="ex-check-{{ $category->id }}"
                {{ \Shop::checkActiveKey($category->slug,'category')?'checked':'' }}>
        <span class="checkmark"></span>
    </label>
    <a href="#" for="ex-check-{{ $category->id }}">
        <b>{{ $category->name }}</b>
        <span class="quantity">({{ \Shop::getCategoryAvailableProducts($category->id, true)}}
                                        )</span>
    </a>

    @if($category->hasChildren())
        <ul style="padding-left: 30px;">
            @foreach($category->children as $child)
                @include('partials.category_filter_item',['category' => $child, 'level'=> ++$level])
            @endforeach
        </ul>
    @endif
</li>