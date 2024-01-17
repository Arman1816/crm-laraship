@if(!isset($home) || !$home)
    <section class="breadcrumb-area breadcrumb--center breadcrumb--smsbtl">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title">
                        @if(isset($item))
                            <h3>{!! $item->title !!}</h3>
                        @elseif(isset($title))
                            <h3>{!! $title !!}</h3>

                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif