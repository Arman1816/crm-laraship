<!-- Default box -->
<div class="card box {{ $box_class??'' }}">
    <div class="box-header card-header with-border {{ empty($box_title) && empty($box_actions)?'hidden':'' }}">
        <h3 class="box-title card-title {{ !empty($box_title) || !empty($box_actions)?'':'hidden' }}">{{ $box_title ?? '' }}</h3>

        <div class="box-tools pull-right">
            {{ $box_actions ?? '' }}
        </div>
    </div>
    <div class="box-body card-body">
        {{ $slot }}
    </div>
    <!-- /.box-body -->
    <div class="box-footer card-footer {{ !empty($box_footer)?'':'hidden' }}">{{ $box_footer ?? '' }}</div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->