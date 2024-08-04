<?php

namespace App\Models;

class HusmusenError
{
    public string $errorCode;
    public string $errorDescription;

    public function __construct(string $errorCode, string $errorDescription)
    {
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
    }

    public static function SendError(int $httpStatusCode, string $errorCode, string $errorDescription)
    {
        $error = new HusmusenError($errorCode, $errorDescription);

        return response_handler($error, $httpStatusCode, request());
    }
}
