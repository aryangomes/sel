<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Utils\LogFormatter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiController extends Controller
{

    protected $tablePermissions;

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
        $keySuccessContent = ApiController::KEY_SUCCESS_CONTENT,
        $codeStatusSuccessResponse = Response::HTTP_OK
    ) {
        $this->codeStatusResponse =  $codeStatusSuccessResponse;

        $this->requestResponse = [$keySuccessContent => $successContent];
    }


    protected function setErrorResponse(
        $errorContent = 'Request Not Processed.',
        $keyErrorContent = ApiController::KEY_ERROR_CONTENT,
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

    public function canPerformAction($action, $model = null)
    {

        $this->authorize('canPerformAction', [
            $model,
            $action
        ]);
    }


    public function canPerformActionOrResourceBelongsToUser(
        $action,
        $idResource,
        $model
    ) {

        $this->authorize('canPerformActionOrResourceBelongsToUser', [
            $model,
            $action,
            $idResource
        ]);
    }

    public function makeNameActionFromTable($actionWithoutTablePermission = '')
    {
        return Str::slug("{$this->tablePermissions} {$actionWithoutTablePermission}");
    }
}
