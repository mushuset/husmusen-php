<?php

namespace App\Models;

/**
 * A special type representing different errors that Husmusen can encounter.
 *
 * A list of all different error codes:
 * - ERR_UNKNOWN_ERROR
 * - ERR_OBJECT_NOT_FOUND
 * - ERR_FILE_NOT_FOUND
 * - ERR_USER_NOT_FOUND
 * - ERR_MISSING_PARAMETER
 * - ERR_INVALID_PARAMETER
 * - ERR_ALREADY_EXISTS
 * - ERR_DATABASE_ERROR
 * - ERR_FILESYSTEM_ERROR
 * - ERR_INVALID_PASSWORD
 * - ERR_FORBIDDEN_ACTION
 *
 * @property string $errorCode        A Husmusen specific error code.
 * @property string $errorDescription A more detailed description of how the error occurred.
 */
class HusmusenError
{
    public string $errorCode;
    public string $errorDescription;

    public function __construct(string $errorCode, string $errorDescription)
    {
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
    }

    /**
     * Creates a Response ready to be sent back to a request.
     *
     * @param string $errorCode        see `HusmusenError`
     * @param string $errorDescription see `HusmusenError`
     *
     * @return \Illuminate\Http\Response
     */
    public static function create(int $httpStatusCode, string $errorCode, string $errorDescription)
    {
        $error = new HusmusenError($errorCode, $errorDescription);

        return response_handler($error, $httpStatusCode, request());
    }
}
