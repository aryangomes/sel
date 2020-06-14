<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Events\LoginUserEvent;
use App\Events\LogoutUserEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Login\LoginUserAdministrator;
use App\Http\Requests\Login\LoginUserAdministratorRequest;
use App\Http\Requests\Login\LoginUserNotAdministratorRequest;
use App\Http\Requests\LoginRequest;
use App\Listeners\LoginUserAdministratorListener;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
    private $userWasAuthenticated;
    private $userWasFound;
    private $userTokenAccess;
    private $userPasswordIsCorrect;

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



    public function loginUserAdministrator(LoginUserAdministratorRequest $request)
    {
        $requestValidated = $request->validated();

        $this->userWasFound = ($this->userWasFound($requestValidated['email'], 'email') != null);

        $this->userWasAuthenticated = false;

        $this->userPasswordIsCorrect = false;

        if ($this->userWasFound) {

            $this->userPasswordIsCorrect = $this->checkUserPassword($requestValidated['password']);

            if ($this->userPasswordIsCorrect && $this->user->isAdmin) {

                $this->userTokenAccess = $this->authenticateUser();

                $this->userWasAuthenticated = isset($this->userTokenAccess);
            }
        }

        $this->setResponseAuthentication();

        return $this->responseWithJson();
    }

    public function loginUserNotAdministrator(LoginUserNotAdministratorRequest $request)
    {
        $requestValidated = $request->validated();

        $this->userWasFound = ($this->userWasFound($requestValidated['cpf'], 'cpf') != null);

        $this->userWasAuthenticated = false;

        $this->userPasswordIsCorrect = false;

        if ($this->userWasFound) {

            $this->userPasswordIsCorrect = $this->checkUserPassword($requestValidated['password']);

            if ($this->userPasswordIsCorrect && (!$this->user->isAdmin)) {

                $this->userTokenAccess = $this->authenticateUser();

                $this->userWasAuthenticated = isset($this->userTokenAccess);
            }
        }

        $this->setResponseAuthentication();

        return $this->responseWithJson();
    }

    public function logout()
    {

        $this->user = Auth::user();

        $userWasLogout = $this->user->logout();

        $userWasLogout ? $this->setSuccessResponse('User logout successfully', 'success', 204) :
            $this->setErrorResponse('User logout failed', 'errors', 400);

        return $this->responseWithJson();
    }

    private function authenticateUser()
    {
        $tokenAccess = null;
        try {

            $tokenAccess = $this->user->generateTokenAccess();
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        return $tokenAccess;
    }


    private function userWasFound($credential, $credentialSearch)
    {
        $userWasFound = false;

        try {

            $this->user = User::where(
                $credentialSearch,
                $credential
            )->first();

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

    private function setResponseAuthentication()
    {
        if ($this->userWasAuthenticated) {
            $successResponse['user'] = $this->user;
            $successResponse['token'] = $this->userTokenAccess;


            $this->setSuccessResponse(
                $successResponse
            );

            event(new LoginUserEvent($this->user));
        } else {
            $errorMessage = 'User not was authenticated';
            $errorStatusCode = 400;

            if (!$this->userWasFound) {
                $errorMessage = 'User not found';
                $errorStatusCode = 404;
            } elseif (!$this->userPasswordIsCorrect) {
                $errorMessage = 'Wrong password';
                $errorStatusCode = 422;
            }


            $this->setErrorResponse(
                $errorMessage,
                'errors',
                $errorStatusCode
            );
        }
    }
}
