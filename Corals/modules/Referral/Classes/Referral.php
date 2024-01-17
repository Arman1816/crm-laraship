<?php

namespace Corals\Modules\Referral\Classes;


use Corals\Modules\Referral\Models\ReferralLink;
use Corals\Modules\Subscriptions\Models\Product;

class Referral
{
    /**
     * FooBar constructor.
     */
    function __construct()
    {
    }

    public function getActions()
    {

        $actions = ['registration' => trans('ReferralProgram::attributes.referral_action.registration')];
        if (\Modules::isModuleActive('corals-subscriptions')) {
            $actions ['subscription'] = trans('ReferralProgram::attributes.referral_action.subscription');
        }
        if (\Modules::isModuleActive('corals-ecommerce') || \Modules::isModuleActive('corals-marketplace')) {
            $actions ['ecommerce'] = trans('ReferralProgram::attributes.referral_action.ecommerce');
        }
        $actions = \Filters::do_filter('referral_action', $actions);
        return $actions;
    }

    public function getPointsBalance($user = null)
    {
        if (!$user) {
            $user = null;
        }
        //$user_links = ReferralLink::where('user_id', $user->id)->pluck('id')->toArray();
        //$points = ReferralRelationship::whereIn('referral_link_id', $user_links)->sum('reward');
        return $user->reward_points ?? 0;

    }

    public function deductFromBalance($user, $points)
    {
        $available_points_blanace = \Referral::getPointsBalance($user);
        if ($available_points_blanace < $points) {
            throw new \Exception(trans('ReferralProgram::exception.no_enough_points_balance'));
        }
        $user->reward_points -= $points;
        $user->save();

    }


    public function getPointsNeedforAmount($amount = 0)
    {
        $point_worth = \Settings::get('referral_point_value', 0);

        if (!$point_worth) {
            return false;
        }
        $points = ceil($amount / $point_worth);
        return $points;

    }

    public function getPointsValue($points = 0)
    {
        $point_worth = \Settings::get('referral_point_value', 0);

        $total_point_worth = ($points * $point_worth);
        return $total_point_worth;

    }


    public function prepareActionParameters($action)
    {

        $action_parameters = [];

        if ($action == 'subscription') {
            if (!\Modules::isModuleActive('corals-subscriptions')) {
                throw new \Exception(trans('ReferralProgram::exception.subscription_not_active'));

            }
            $action_parameters['products'] = Product::all();

        }
        $action_parameters = \Filters::do_filter('referral_program_action_parameters', $action_parameters, $action, $this);
        return $action_parameters;
    }

    public static function getReferral($user, $program)
    {
        return ReferralLink::where([
            'user_id' => $user->id,
            'referral_program_id' => $program->id
        ])->first();
    }

}
