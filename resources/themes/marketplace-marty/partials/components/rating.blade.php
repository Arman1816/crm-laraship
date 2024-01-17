<li>
    @for($i = 1 ; $i <= 5; $i++)
        <span class="fa fa-star{{ $rating >= $i ?  'filled' : '' }}"></span>
    @endfor
</li>
@if($rating_count)
    <span class="rating__count">&nbsp;({{$rating_count}})</span>
@endif
