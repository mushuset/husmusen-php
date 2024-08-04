<?php

namespace App\Models;

use Illuminate\Http\JsonResponse;

class HusmusenError
{
    public string $errorCode;
    public string $errorDescription;

    public function __construct(string $errorCode, string $errorDescription)
    {
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
    }

    public static function SendError(int $httpStatusCode, string $errorCode, string $errorDescription): JsonResponse
    {
        $error = new HusmusenError($errorCode, $errorDescription);

        return response()->json($error, $httpStatusCode);
    }
}
