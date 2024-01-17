@component('components.box')
    <div class="row">
        <div class="col-md-3">
            <h3>Address Hierarchy</h3>
        </div>
    </div>
    <div class="row">

        <div class="col-md-3">
            {!! CoralsForm::select('country_id','country', \Address::getLocationsList(null,false,'active','name ASC','country', null), false, null, [
                           'id'=>'country_id',
                            'class'=>'dependent-select',
                                'data'=>  [
                                            'dependency-field'=>'state_id',
                                            'dependency-ajax-url'=> url('utilities/address/get-location-type-children'),
                                            'dependency-args'=>'country_target_type'
                                ]
                    ], 'select2') !!}

            {!! Form::hidden('country_target_type','state',['id'=>'country_target_type']) !!}
        </div>

        @if(isset($withStates) && $withStates)
            <div class="col-md-3">
                {!! CoralsForm::select('state_id','state',  [], false, null, [
                               'id'=>'state_id',
                                'class'=>'dependent-select',
                                'data'=>  [
                                                'dependency-field'=>'city_id',
                                                'dependency-ajax-url'=> url('utilities/address/get-location-type-children'),
                                                'dependency-args'=>'state_target_type'
                                    ]
                    ], 'select2') !!}

                {!! Form::hidden('state_target_type','city',['id'=>'state_target_type']) !!}

            </div>
        @endif

        @if(isset($withCities) && $withCities)
            <div class="col-md-3">
                {!! CoralsForm::select('city_id','city',[], false, null, ['id'=>'city_id'], 'select2') !!}
            </div>
        @endif

    </div>
@endcomponent