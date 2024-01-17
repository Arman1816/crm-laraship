@foreach($categories as $category)
    @if($category->hasChildren())
        <li class="dd-item" data-id="{{ $category->id }}">
            <div class="dd-handle">
                <strong>{{ $category->name }}</strong>
                @if($category->status === 'inactive')
                    <i class="fa fa-ban fa-fw text-danger"></i>
                @endif
                <span class="pull-right dd-nodrag">{!! $category->present('action') !!}</span>
            </div>
            <ol class="dd-list">
                @include('Marketplace::categories.hierarchy_item', ['categories'=> $category->getChildren()])
            </ol>
        </li>
    @else
        <li class="dd-item" data-id="{{ $category->id }}">
            <div class="dd-handle">
                <strong>{{ $category->name }}</strong>
                @if($category->status === 'inactive')
                    <i class="fa fa-ban fa-fw text-danger"></i>
                @endif
                <span class="pull-right dd-nodrag">{!! $category->present('action') !!}</span>
            </div>
        </li>
    @endif
@endforeach