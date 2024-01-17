<?php

namespace Corals\Activity\HttpLogger\Http\Middleware;

use Closure;
use Corals\Activity\HttpLogger\Contracts\LogProfile;
use Corals\Activity\HttpLogger\Contracts\LogWriter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class HttpLogger
{
    protected $logProfile;
    protected $logWriter;

    public function __construct(LogProfile $logProfile, LogWriter $logWriter)
    {
        $this->logProfile = $logProfile;
        $this->logWriter = $logWriter;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!schemaHasTable('http_log')) {
            return $next($request);
        }

        $httpLogId = null;
        $response = $next($request);

        if (!$this->logProfile->shouldLogRequest($request)) {
            return $response;
        }

        $httpLogId = $this->logWriter->logRequest($request);

        $exception = $response->exception ?? null;

        if ($httpLogId && ($response->getStatusCode() >= 400 && $response->getStatusCode() < 500 || $exception)) {
            $httpResponse = [
                'responseStatusCode' => $response->getStatusCode(),
            ];

            if ($response instanceof JsonResponse) {
                $httpResponse['responseData'] = $response->getData();
            }

            if ($exception) {
                $httpResponse['message'] = $exception->getMessage();

                if ($exception instanceof ValidationException) {
                    $httpResponse['status'] = $exception->status;
                    $httpResponse['errors'] = $exception->errors();
                }
            }

            \DB::table('http_log')->where('id', $httpLogId)->update([
                'response' => json_encode($httpResponse)
            ]);
        }

        return $response;
    }
}
