@isset($product)
    <div class="container">
        <div class="row">
            @php $cols = $product->activePlans->count() == 4 ? 3 : 4  @endphp
            @foreach($product->activePlans as $plan)
                <div class="col-lg-{{$cols}} col-md-6">
                    <div class="pricing red">
                        <h4 class="pricing--title">{{$plan->name}}</h4>
                        <p class="pricing--price">
                            @if($plan->free_plan)
                                <span class="ammount">{{  \Payments::currency(0.00) }}</span>
                            @else
                                <span class="ammount">{{  \Payments::currency($plan->price) }}</span>
                                {!! $plan->cycle_caption  !!}
                            @endif
                        </p>
                        <div class="pricing--features">
                            <ul>
                                @foreach($product->activeFeatures as $feature)
                                    @if($plan_feature = $plan->features()->where('feature_id',$feature->id)->first())
                                        <li>
                                            @if(!empty($plan_feature->pivot->plan_caption))
                                                {{ $plan_feature->pivot->plan_caption }}
                                            @else
                                                @if($feature->type=="boolean")
                                                    @if($plan_feature->pivot->value)
                                                        <i class="fa fa-check"></i>
                                                    @endif
                                                @else
                                                <strong>{{$plan_feature->pivot->value }}</strong>  {{$feature->unit }}
                                                @endif
                                                {{ $feature->caption }}
                                            @endif
                                        </li>
                                    @else
                                        <li>{{$feature->caption}}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                        @if(user() && user()->subscribed(null, $plan->id))
                            <a href="#"
                               class="pricing--btn">@lang('corals-marketplace-marty::labels.pricing.current_package')</a>
                            <br/>
                            {{ user()->currentSubscription(null, $plan->id)->ends_at?('ends at: '.format_date_time(user()->currentSubscription(null, $plan->id)->ends_at)):'' }}
                        @else
                            <a href="{{ url('subscriptions/checkout/'.$plan->hashed_id) }}" class="pricing--btn">
                                @lang('corals-marketplace-marty::labels.pricing.subscribe_now')
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <p class="text-center text-danger">
        <strong>@lang('corals-marketplace-marty::labels.pricing.product_not_found')</strong></p>
@endisset
