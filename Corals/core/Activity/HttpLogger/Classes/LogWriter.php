<?php

namespace Corals\Activity\HttpLogger\Classes;

use Corals\Activity\HttpLogger\Contracts\LogWriter as LogWriterContract;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LogWriter implements LogWriterContract
{
    public function logRequest(Request $request)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $body = json_encode($request->except(config('http_logger.except')));

        $bodyContent = $request->getContent();

        if (!empty($bodyContent) && empty($body)) {
            $body .= $bodyContent;
        }

        $user = $request->user();

        $files = [];

        foreach (iterator_to_array($request->files) as $index => $file) {
            $files[$index] = $this->getFileNames($file);
        }

        return \DB::table('http_log')->insertGetId([
            'ip' => $request->ip(),
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : null,
            'uri' => $uri,
            'method' => $method,
            'headers' => json_encode($request->headers->all()),
            'body' => $body,
            'files' => json_encode($files),
            'created_at' => now(),
        ]);
    }

    protected function getFileNames($file)
    {
        if (is_array($file)) {
            $name = [];
            foreach ($file as $key => $aFile) {
                $name[$key] = $this->getFileNames($aFile);
            }
        } else {
            if ($file instanceof UploadedFile) {
                $name = [
                    'clientOriginalName' => $file->getClientOriginalName(),
                    'size' => $file->isValid() && $file->isFile() ? $file->getSize() : 0,
                    'clientOriginalExtension' => $file->getClientOriginalExtension(),
                    'clientMimeType' => $file->getClientMimeType()
                ];
            } else {
                $name = $file;
            }
        }

        return $name;
    }
}
