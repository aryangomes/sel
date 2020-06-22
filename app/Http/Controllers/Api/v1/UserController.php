<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRegisterRequest $request)
    {
        $requestValidated = $request->validated();

        $userWasCreated = false;

        $this->authorize('create', new User());

        try {
            DB::beginTransaction();

            $userCreated = User::create($requestValidated);

            $userWasCreated = isset($userCreated);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        if ($userWasCreated) {
            DB::commit();

            $this->setSuccessResponse($userCreated, 'user', 201);
        } else {
            DB::rollBack();
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $requestValidated = $request->validated();

        $userWasUpdated = false;


        $this->authorize('update', $user);

        try {
            DB::beginTransaction();

            $userWasUpdated = $user->update($requestValidated);
        } catch (\Exception $exception) {

            $this->logErrorFromException($exception);
        }

        if ($userWasUpdated) {
            DB::commit();

            $this->setSuccessResponse($user, 'user', 200);
        } else {
            DB::rollBack();
            $this->setErrorResponse();
        }

        return $this->responseWithJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

        $userWasLogout =  $userWasDeleted = false;
       
     
        $this->authorize('delete', $user);
        // if (Auth::guard('api')->check()) {

            try {
                DB::beginTransaction();

                // $userWasLogout = $user->logout();

                $userWasDeleted = $user->delete();
            } catch (\Exception $exception) {
                $this->logErrorFromException($exception);
            }
        // }

        // $userWasLogoutAndDeleted = ($userWasLogout ==  $userWasDeleted);
 
        if ($userWasDeleted) {
            DB::commit();
            $this->setSuccessResponse('User deleted successfully', 'success', 200);
        } else {
            DB::rollBack();
            $this->setErrorResponse('User deleted failed', 'errors', 422);
        }

        return $this->responseWithJson();
    }
}
