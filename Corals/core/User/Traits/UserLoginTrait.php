<?php

namespace Corals\User\Traits;


use Carbon\Carbon;

trait UserLoginTrait
{
    /**
     * @param $user
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    protected function doLogin($user, $message = '')
    {
        $activeToken = $user->tokens()->where('revoked', false)->first();

        if ($activeToken) {
//            $activeToken->revoke();
        }

        $tokenResult = $user->createToken('Corals-API');

        $token = $tokenResult->token;

        $token->save();

        $user->setPresenter(new \Corals\User\Transformers\API\UserPresenter());

        $userDetails = $user->presenter();

        $userDetails['authorization'] = 'Bearer ' . $tokenResult->accessToken;

        $userDetails['expires_at'] = Carbon::parse(
            $tokenResult->token->expires_at
        )->toDateTimeString();

        return apiResponse($userDetails, $message);
    }
}