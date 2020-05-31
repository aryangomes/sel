<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginUserAdministrator;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    private $user;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }



    public function loginUserAdministrator(LoginUserAdministrator $request)
    {
        $requestValidated = $request->validated();

        $userWasFound = isset($this->userWasFound($requestValidated['email'], 'email'));

        $userWasAuthenticated = false;

        if ($userWasFound) {

            $userPasswordIsCorrect = $this->checkUserPassword($requestValidated['password']);

            if ($userPasswordIsCorrect) {
                $tokenAcess['token'] = $this->authenticateUser();

                $userWasAuthenticated = true;
            }
        }

        if ($userWasAuthenticated) {
            $userAuthenticated['user'] = $this->user;

            $successResponse = [
                $tokenAcess,
                $userAuthenticated
            ];
            $this->setSuccessResponse(
                $successResponse
            );
        } else {
            $errorMessage = 'User not was authenticated';
            $errorStatusCode = 400;

            if (!$userWasFound) {
                $errorMessage = 'User not found';
                $errorStatusCode = 404;
            }

            $this->setErrorResponse(
                $errorMessage,
                'errors',
                $errorStatusCode
            );
        }

        return $this->responseWithJson();
    }

    public function loginUserNotAdministrator()
    {
        # code...
    }

    private function authenticateUser()
    {
        $tokenAcess = null;
        try {

            $tokenAcess = $this->user()->generateTokenAccess();
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        return $tokenAcess;
    }


    private function userWasFound($credential, $credentialSearch)
    {
        $userWasFound = false;

        try {

            $this->user = User::where([
                $credentialSearch, $credential
            ])->first();

            $userWasFound = (isset($this->user));
        } catch (\Exception $exception) {
            $this->logErrorFromException($exception);
        }

        return $userWasFound;
    }



    private function checkUserPassword($userPlainPassword)
    {
        $checkUserPassword = false;

        try {

            $checkUserPassword = Hash::check($userPlainPassword, $this->user->password);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        return $checkUserPassword;
    }
}
