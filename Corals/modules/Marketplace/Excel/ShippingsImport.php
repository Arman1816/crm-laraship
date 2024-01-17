<?php


namespace Corals\Modules\Marketplace\Excel;

use Corals\Modules\Marketplace\Models\Shipping;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Facades\Excel;

class ShippingsImport implements ToArray
{
    protected $store;

    public function __construct($store)
    {
        $this->store;
    }

    /**
     * @param array $rules
     * @throws \Exception
     */
    public function array(array $rules)
    {
        $headers = $rules[0] ?? [];

        if (empty($headers)) {
            throw new \Exception('Invalid file structure');
        }

        unset($rules[0]);

        $wrongData = [];
        $wrongCounter = 0;
        $successCounter = 0;

        foreach ($rules as $index => $rule) {
            $rule = array_combine($headers, $rule);

            $validator = Validator::make($rule, [
                'name' => 'required',
                'shipping_method' => 'required',
                'priority' => 'required|numeric',
                'rate' => 'numeric|required_if:shipping_method,FlatRate',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();

                $rule['errors'] = '[' . implode(", ", $errors) . ']';

                $wrongData[] = $rule;

                $wrongCounter++;
            } else {
                $rule['min_order_total'] = $rule['min_order_total'] ?? 0.0;
                $rule['store_id'] = $this->store ? $this->store->id : null;

                Shipping::create($rule);

                $successCounter++;
            }
        }

        if (count($wrongData) > 0) {
            $exportName = 'errors/shipping_rules_errors_' . now()->format('Y-m-d_h-m-s') . '.xlsx';

            Excel::store(new ShippingsErrorExport($wrongData), $exportName);

            session()->put('shipping-rules-report', storage_path('app/' . $exportName));
        }

        flash(trans('Marketplace::messages.shipping.success.import',
            ['successCount' => $successCounter, 'wrongCount' => $wrongCounter]))->success();
    }
}
