<?php

namespace Corals\Activity\HttpLogger\Contracts;

use Illuminate\Http\Request;

interface LogWriter
{
    public function logRequest(Request $request);
}
