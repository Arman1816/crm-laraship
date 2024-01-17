<?php

namespace Corals\Modules\Payment\Common\Services;


class PaymentService
{
    public function getPaymentSettings()
    {
        $settings = [];

        foreach (\Payments::getAvailableGateways() as $key => $gateway) {
            $configFile = 'payment_' . strtolower($key);
            if (config($configFile)) {
                $settings[config($configFile . '.key')] = config($configFile);
            }
        }

        return $settings;
    }
}
