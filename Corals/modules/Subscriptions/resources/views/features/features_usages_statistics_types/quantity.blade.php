<div class="col-md-4">
    <div class="card wd-lg-50p wd-xl-auto">
        <div class="card-header" style="padding: 20px 20px 0;background-color: transparent;">
            <h6 class="card-title tx-14 mg-b-0">{!! $data['feature_name'] !!}</h6>
        </div><!-- card-header -->
        <div class="card-body">
            <h3 class="tx-bold tx-inverse lh--5 mg-b-15">{{$data['feature_usage_count']}} <span
                        class="tx-base tx-normal tx-gray-600">/ {{$data['feature_limit']}}</span>
            </h3>
            <div class="progress mg-b-0 ht-3"  title="{{$data['used_percentage']}}%">
                <div class="progress-bar wd-{{  round($data['used_percentage']/5)*5 }}p bg-purple" role="progressbar"
                     aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>
