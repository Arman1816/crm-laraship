@php($block = is_object($block) ? $block : unserialize($block))

@if($block)
    @if($block->as_row)
        <div class="row">
            @endif
            @forelse($block->activeWidgets as $widget)

                @if($widget->widget_width)
                    <div class="col-md-{{ $widget->widget_width }}">
                        {!! $widget->rendered !!}
                    </div>
                @else
                    {!! $widget->rendered !!}
                @endif
            @empty
            @endforelse
            @if($block->as_row)
        </div>
    @endif
@else
    <p class="text-center text-danger">
        <strong> {!! trans('CMS::labels.block.block_cannot_found',['block_key' => $block_key]) !!}</strong></p>
@endif
