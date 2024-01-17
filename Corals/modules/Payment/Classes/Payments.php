<?php

namespace Corals\Modules\Payment\Classes;


use Corals\Modules\Payment\Common\Models\Tax;
use Corals\Modules\Payment\Common\Models\TaxClass;
use Corals\Modules\Payment\Payment;
use Corals\User\Models\User;
use Illuminate\Support\Collection;
use Money\Currencies\ISOCurrencies;
use Money\Currency;


class Payments
{
    public $gateway;

    /**
     * Payments constructor.
     * @param null $gateway_key
     * @param array $params
     * @throws \Exception
     */
    function __construct($gateway_key = null, array $params = [])
    {
        if ($gateway_key) {
            $gateway = Payment::create($gateway_key);

            $config = config('payment_' . strtolower($gateway_key));

            if (!$config) {
                throw new \Exception(trans('Payment::exception.invalid_gateway'));
            }

            $this->gateway = $gateway;

            $this->gateway->setAuthentication();

            foreach ($params as $key => $value) {
                $this->gateway->setParameter($key, $value);
            }
        }
    }

    public function getCurrenciesList()
    {
        $codes = \Corals\Modules\Payment\Common\Models\Currency::query()
            ->select('code', \DB::raw("concat(name,' (',code,')') as title"))
            ->pluck('title', 'code');

        return $codes;
    }

    public function getCodeList()
    {
        $codes = \Corals\Modules\Payment\Common\Models\Currency::pluck('code', 'code');
        return $codes;
    }

    public function getAvailableGateways($supportConfig = null, $excludeConfig = null)
    {
        $supportedGateways = \Settings::get('supported_payment_gateway', []);

        if ($supportConfig || $excludeConfig) {
            foreach ($supportedGateways as $gatewayKey => $gateway_title) {
                $paymentGateway = Payment::create($gatewayKey);

                if (
                    ($supportConfig && !$paymentGateway->getConfig($supportConfig))
                    || ($excludeConfig && $paymentGateway->getConfig($excludeConfig))
                ) {
                    unset($supportedGateways[$gatewayKey]);
                }
            }
        }

        return $supportedGateways;
    }


    public function loadGatewayScripts()
    {
        $scripts = "";
        $gateways = $this->getAvailableGateways();
        foreach ($gateways as $gateway => $gateway_title) {
            $payment_gateway = Payment::create($gateway);
            $scripts .= $payment_gateway->loadScripts();
        }
        return $scripts;
    }


    public function setAvailableGateways($supported_gateways)
    {
        \Settings::set('supported_payment_gateway', json_encode($supported_gateways));
    }


    public function isGatewaySupported($gateway)
    {
        return array_key_exists($gateway, $this->getAvailableGateways());
    }


    public function getTaxClassesList()
    {
        return TaxClass::orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function getTaxesList()
    {
        return Tax::query()->join('tax_classes', 'tax_classes.id', 'taxes.tax_class_id')
            ->select(\DB::raw("concat(tax_classes.name ,' (',taxes.name, ') - priority(', taxes.priority, ')') as label"),
                'taxes.id')
            ->orderBy('tax_class_id')->pluck('label', 'id')
            ->toArray();
    }


    /**
     * @param $taxable
     * @param array $address
     * @return array
     * @throws \Exception
     */
    public function calculateTax($taxable, $address = [])
    {
        try {
            return $this->calculateTaxClassTaxes($taxable->tax_classes, $address);
        } catch (\Exception $ex) {
            throw new \Exception(trans('Payment::exception.tax.error_calculating_tax',
                ['message' => $ex->getMessage()]));
        }
    }

    public function calculateTaxClassTaxes(Collection $taxClasses, $address = [])
    {
        $taxes = [];

        foreach ($taxClasses as $tax_class) {
            $class_taxes = $tax_class->getTaxByPriority();
            $rate = 0;
            $applied_country = [];
            $applied_state = [];
            $applied_zip = [];

            $state = strtolower($address['state'] ?? null);

            $country = $address['country'] ?? null;
            $zip = $address['zip'] ?? null;

            foreach ($class_taxes as $tax) {
                $calculate = false;
                $taxState = strtolower($tax->state);
                if (empty($tax->country) && !isset($applied_country[$tax->name])) {
                    $calculate = true;
                    $applied_country[$tax->name] = $tax->rate;
                } else {
                    if ($tax->country == $country && !isset($applied_country[$tax->name])) {
                        if ($taxState == $state && !isset($applied_state[$tax->name])) {
                            if (($tax->zip == $zip || empty($tax->zip)) && !isset($applied_zip[$tax->name])) {
                                $calculate = true;
                                $applied_country[$tax->name] = $tax->rate;
                                $applied_state[$tax->name] = $tax->rate;
                                $applied_zip[$tax->name] = $tax->rate;
                            }
                        } else {
                            if (empty($taxState) && !isset($applied_state[$tax->name])) {
                                $calculate = true;
                                $applied_country[$tax->name] = $tax->rate;
                                $applied_state[$tax->name] = $tax->rate;
                                $applied_zip[$tax->name] = $tax->rate;
                            }
                        }
                    }
                }

                if ($calculate) {
                    if ($tax->compound == 1) {
                        $rate += $tax->rate;
                        $taxes[$tax->id] = ['name' => $tax->name, 'rate' => ($rate / 100)];
                    } else {
                        $taxes[$tax->id] = ['name' => $tax->name, 'rate' => ($tax->rate / 100)];
                    }
                }
            }
        }

        return $taxes;
    }

    function currency($amount, $currency = null)
    {
        if (is_null($amount)) {
            $amount = 0;
        }

        if ($currency) {
            return \Currency::format($amount, $currency);
        }

        return app('currency')->convert($amount, \Payments::admin_currency_code(), $this->session_currency());
    }

    function session_currency()
    {
        return \Currency::getUserCurrency();
    }

    function currency_symbol()
    {
        return \Currency::getCurrency()['symbol'];
    }

    function currency_convert($amount, $from = null, $to = null, $format = false)
    {
        if (($from == $to) && ($from != null)) {
            return $amount;
        }
        if (!$from) {
            $from = \Payments::admin_currency_code();
        }
        if (!$to) {
            $to = $this->session_currency();
        }
        $to = strtoupper($to);
        $conversion = \Currency::convert($amount, $from, $to, false);
        $iso_currencies = new ISOCurrencies();
        $currency = new Currency($to);
        if ($currency) {
            $decimals = $iso_currencies->subunitFor($currency);
            $amount = number_format((float)$conversion, $decimals, '.', '');
        } else {
            $amount = $conversion;
        }
        if ($format) {
            return \Currency::format($amount, $to);
        }
        return $amount;
    }

    function admin_currency($amount)
    {
        return \Currency::format($amount, \Payments::admin_currency_code());
    }

    function admin_currency_code($lower_case = false)
    {
        $default_currency = config('currency.default');
        $admin_currency_code = \Settings::get('admin_currency_code', $default_currency);
        if ($lower_case) {
            return strtolower($admin_currency_code);
        } else {
            return strtolower($admin_currency_code);
        }
    }

    function getActiveCurrenciesList()
    {
        $currencies = \Currency::getActiveCurrencies();
        $active_currencies = [];
        foreach ($currencies as $currency) {
            $active_currencies[$currency['code']] = $currency['code'] . " " . $currency['symbol'];
        }
        return $active_currencies;
    }

    public function expiryMonth()
    {
        $months = [];

        foreach (range(1, 12) as $number) {
            $month = sprintf('%02d', $number);
            $months[$month] = $month;
        }

        return $months;
    }

    public function expiryYear()
    {
        $years = [];

        foreach (range(now()->year, now()->year + 20) as $year) {
            $years[$year] = $year;
        }

        return $years;
    }

    /**
     * @param $invoice
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function createPaymentToken($invoice, $params)
    {
        $amount = $invoice->total;

        $currency = $invoice->currency;
        $description = "Payment for Invoice#" . $invoice->id;

        $parameters = $this->gateway->preparePaymentTokenParameters($amount, $currency, $description, $params);

        $request = $this->gateway->purchase($parameters);

        $response = $request->send();

        if ($response->isSuccessful()) {
            return $response->getPaymentTokenReference();
        } else {
            throw new \Exception($response->getDataText());
        }
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Exception
     */
    public function checkPaymentToken($params)
    {
        $parameters = $this->gateway->prepareCheckPaymentTokenParameters($params);

        if ($this->gateway->getConfig('require_token_confirm')) {
            $request = $this->gateway->confirmPaymentToken($parameters);
        } else {
            $request = $this->gateway->checkPaymentToken($parameters);
        }

        $response = $request->send();

        if ($response->isSuccessful()) {
            return $response->getPaymentTokenReference();
        } else {
            throw new \Exception(trans($response->getDataText()));
        }
    }

    /**
     * @param $order
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function getPaymentView($order)
    {
        $gateway = $this->gateway;
        $action = '';

        $gatewayPaymentTokenURL = url("invoice/payments/gateway-payment-token/{$this->gateway->getName()}/$order->hashed_id");

        return view($this->gateway->getPaymentViewName())
            ->with(compact('gateway', 'order', 'action', 'gatewayPaymentTokenURL'));
    }

    /**
     * @param $request
     * @param $invoice
     * @return bool
     * @throws \Exception
     */
    public function doPayment($request, $invoice): bool
    {
        $amount = $invoice->total;

        $order = (object)[
            'id' => $invoice->id,
            'amount' => $amount,
            'currency' => $invoice->currency,
            'billing' => [
                'billing_address' => [
                    'email' => $invoice->user->email
                ]
            ]
        ];

        $checkoutDetails = [
            'token' => $request->get('checkoutToken'),
            'gateway' => $this->gateway->getName(),
        ];

        $user = user() ?? new User;

        $response = $this->gateway->createCharge(
            $parameters = $this->gateway->prepareCreateChargeParameters($order, $user, $checkoutDetails)
        )->send();

        if ($response->isSuccessful()) {
            $status = $this->gateway->getConfig('charge_payment_default_status') ?: 'paid';

            $properties = $invoice->getProperties();

            $properties = array_merge($properties, [
                'reference_id' => $response->getChargeReference(),
                'gateway' => $this->gateway->getName(),
                'gateway_response' => $response->getData(),
            ]);

            $invoice->update(['properties' => $properties]);

            if ($status === 'paid') {
                $invoice->markAsPaid();
            } else {
                $invoice->update(['status' => $status]);
            }

            return true;
        } else {
            $message = 'pay Gateway Order Failed. ' . $response->getMessage();
            throw new \Exception($message);
        }
    }

    public function fetchConnectAccount($accountId)
    {
        $gateway = $this->gateway;

        $request = $gateway->fetchConnectAccount(['account_id' => $accountId]);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $data = $response->getData();

            $status = data_get($data, 'payouts_enabled', false) ? 'PAYOUTS_ENABLED' : 'PAYOUTS_DISABLED';

            $referenceId = data_get($data, 'id');

            user()->setGatewayStatus($gateway->getName(), $status, '', $referenceId, 'AccountConnect', $data);

            return $status;
        } else {
            $message = data_get($response->getData(), 'error.message');
            throw new \Exception($message);
        }
    }

    public function createTransfer($data)
    {
        $request = $this->gateway->transfer($data);

        $response = $request->send();

        if ($response->isSuccessful()) {
            return $response->getData();
        } else {
            $message = data_get($response->getData(), 'error.message');
            throw new \Exception($message);
        }
    }

    public function reverseTransfer($transaction)
    {
        $data = [
            'transferReference' => $transaction->reference,
        ];

        $request = $this->gateway->reverseTransfer($data);

        $response = $request->send();

        if ($response->isSuccessful()) {
            $transaction->update([
                'status' => 'cancelled',
            ]);

            return $response->getData();
        } else {
            $message = data_get($response->getData(), 'error.message');
            throw new \Exception($message);
        }
    }
}
