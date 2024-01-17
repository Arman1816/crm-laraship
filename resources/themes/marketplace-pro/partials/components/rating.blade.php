<div class="rating">
    @for($i = 1 ; $i <= 5; $i++)
        <i class="fa fa-star {{ $rating >= $i ?  'filled' : 'd-none' }}"></i>
    @endfor
</div>
@if($rating_count)
    <span class="text-muted align-middle">&nbsp;@lang('corals-marketplace-pro::labels.partial.component.customer_review',['rating' => $rating ,'count' => $rating_count ])</span>
@endif