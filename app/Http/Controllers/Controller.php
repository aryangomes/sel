<?php

namespace App\Http\Controllers;

use App\Models\Utils\LogFormatter;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $codeStatusResponse = Response::HTTP_OK;

    const KEY_ERROR_CONTENT = 'errors';

    const KEY_SUCCESS_CONTENT = 'success';

    protected $requestResponse = [];

    protected function responseWithJson()
    {
        return response()->json($this->requestResponse, $this->codeStatusResponse);
    }

    protected function setSuccessResponse(
        $successContent = 'Request Processed',
        $keySuccessContent = Controller::KEY_SUCCESS_CONTENT,
        $codeStatusSuccessResponse = Response::HTTP_OK
    ) {
        $this->codeStatusResponse =  $codeStatusSuccessResponse;

        $this->requestResponse = [$keySuccessContent => $successContent];
    }


    protected function setErrorResponse(
        $errorContent = 'Request Not Processed.',
        $keyErrorContent = Controller::KEY_ERROR_CONTENT,
        $codeStatusErrorResponse =  Response::HTTP_BAD_REQUEST
    ) {
        $this->codeStatusResponse =  $codeStatusErrorResponse;

        $this->requestResponse = [$keyErrorContent => $errorContent];
    }


    protected function logErrorFromException(\Exception $exception)
    {

        $textsOfException = [
            '[logErrorFromException] Message .: ' . $exception->getMessage(),
            '[logErrorFromException] File .: ' . $exception->getFile(),
            '[logErrorFromException] Previous .: ' . $exception->getPrevious()
        ];

        Log::error(LogFormatter::formatTextLog($textsOfException));


        $codeStatusResponse = ($exception->getCode() <= 0 ||
            $exception->getCode() > 600) ?
            Response::HTTP_INTERNAL_SERVER_ERROR :  $exception->getCode();

        $this->codeStatusResponse =  $codeStatusResponse;

        $this->requestResponse = ['errors' => $exception->getMessage()];
    }
}
