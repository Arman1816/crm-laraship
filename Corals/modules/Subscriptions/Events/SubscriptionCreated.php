<?php


namespace Corals\Modules\Subscriptions\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubscriptionCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var
     */
    public $subscription;

    /**
     * @var
     */
    public $gateway;
    public $response;

    /**
     * SubscriptionCreated constructor.
     * @param $subscription
     * @param $gateway
     * @param $response
     */
    public function __construct($subscription, $gateway, $response)
    {
        $this->subscription = $subscription;
        $this->gateway = $gateway;
        $this->response = $response;
    }

}
