<div class="p-2">
    <div class="table-responsive">
        <table class="table table-striped">
            @foreach($pricePerQuantity as $pricePerQ)
                @continue(empty(data_get($pricePerQ,'description')))
                <tr>
                    <th>{{ data_get($pricePerQ,'description') }}</th>
                </tr>
            @endforeach
        </table>
    </div>
</div>
