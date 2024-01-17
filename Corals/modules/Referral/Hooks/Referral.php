<?php

namespace Corals\Modules\Referral\Hooks;

use Corals\Modules\Referral\Middleware\ReferralMiddleware;
use Corals\Modules\Referral\Models\ReferralLink;


class Referral
{
    /**
     * Referral constructor.
     */
    function __construct()
    {
    }

    public function referrals_middleware($middleware)
    {
        $middleware[] = ReferralMiddleware::class;
        return $middleware;
    }

    public function check_registration_referral_programs($user)
    {

        if (request()->cookie('ref')) {
            $referralLink = ReferralLink::find(request()->cookie('ref'));
            if (empty($referralLink)) {
                return;
            }

            if ($referralLink->program->referral_action != "registration") {
                return;
            }
            $reward_points = $referralLink->program->options['rewards']['registration'];

            $referralLink->relationships()->create(['user_id' => $user->id, 'reward' => $reward_points]);
            $referralLink->user->reward_points += $reward_points;
            $referralLink->user->save();
        }
        return;
    }

    public function check_subscribed_referral_programs($subscription)
    {

        if (request()->cookie('ref')) {

            $link_id = request()->cookie('ref');

            $referralLink = ReferralLink::find($link_id);

            if (empty($referralLink)) {
                return;
            }

            if ($referralLink->program->referral_action != "subscription") {
                return;
            }

            $reward_points = $referralLink->program->options['rewards']['subscription']['plan_' . $subscription->plan->id];

            $referralLink->relationships()->create(['user_id' => $subscription->user->id, 'reward' => $reward_points]);
            $referralLink->user->reward_points += $reward_points;
            $referralLink->user->save();


        }
        return;
    }

    public function check_ecommerce_referral_programs($order)
    {

        if (request()->cookie('ref')) {

            $link_id = request()->cookie('ref');

            $referralLink = ReferralLink::find($link_id);

            if (empty($referralLink)) {
                return;
            }

            if ($referralLink->program->referral_action != "ecommerce") {
                return;
            }

            $reward_by = $referralLink->program->options['rewards']['ecommerce']['reward_per'];
            $reward_points = 0;
            if ($reward_by == "sale") {
                $reward_points = $referralLink->program->options['rewards']['ecommerce']['reward'];
            } else if ($reward_by == "order_amount") {
                $reward_points = (int)$order->amount * $referralLink->program->options['rewards']['ecommerce']['reward'];
            }
            $referralLink->relationships()->create(['user_id' => $order->user->id, 'reward' => $reward_points]);
            $referralLink->user->reward_points += $reward_points;
            $referralLink->user->save();


        }
        return;
    }


}

