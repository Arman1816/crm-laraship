{!! CoralsForm::openForm(null,['url' => url($resource_url.'/withdraw'),'method'=>'POST','files'=>false]) !!}
<div class="row">
    <div class="col-md-6 col-xs-12 col-sm-6">
        {!! CoralsForm::number('amount', 'Marketplace::attributes.transaction.withdraw_amount', false, null ,
        ['step'=>0.01, 'min'=>0, 'max'=>$balance, 'left_addon'=>'<span class="fa fa-money"></span>']) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {!! CoralsForm::textarea('notes','Marketplace::attributes.transaction.withdraw_notes',false, $store->getSettingValue('marketplace_bank_information') , ['class'=>'','rows'=>5,'help_text'=>'Marketplace::labels.transactions.withdraw_notes_help']) !!}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        {!! CoralsForm::formButtons(trans('Corals::labels.save',['title' => $title_singular]), [], ['show_cancel' => true])  !!}
    </div>
</div>


{!! CoralsForm::closeForm($transaction) !!}

