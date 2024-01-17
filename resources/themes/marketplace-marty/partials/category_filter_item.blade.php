<li>
    <div class="custom-checkbox2">
        <input name="category[]" value="{{ $category->slug }}"
               type="checkbox"
               id="ex-check-{{ $category->id }}"
                {{ \Shop::checkActiveKey($category->slug,'category')?'checked':'' }}>
        <label for="ex-check-{{ $category->id }}">
            <span class="circle"></span>
            {{$category->name}}
            ({{ \Shop::getCategoryAvailableProducts($category->id, true)}})
        </label>

    </div>
    @if($category->hasChildren())
        <ul style="padding-left: 25px;">
            @foreach($category->children as $child)
                @include('partials.category_filter_item',['category'=>$child])
            @endforeach
        </ul>
    @endif
</li>