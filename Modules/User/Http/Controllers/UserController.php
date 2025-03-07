<?php

namespace Modules\User\Http\Controllers;

use App\Traits\OTP;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\UserStoreRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

class UserController extends Controller
{
    use OTP;

    public function __construct()
    {
//        if (Module::find('Roles')->isEnabled()) {
//            $this->middleware('permission:view users')->only('index');
//            $this->middleware('permission:create user')->only('create');
//            $this->middleware('permission:store user')->only('store');
//            $this->middleware('permission:edit user')->only('edit');
//            $this->middleware('permission:update user')->only('update');
//            $this->middleware('permission:destroy user')->only('destroy');
//        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(UserStoreRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $validated['otp'] = self::generateOTP();
            $validated['otp_expired_at'] = self::expireOTP();
            User::create($validated);

            return response()->json([
                "status" => ResponseAlias::HTTP_OK,
                "message" => __('Data successfully created!'),
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                "status" => ResponseAlias::HTTP_BAD_REQUEST,
                "message" => __('An error occurred!'),
                "error" => $e->getMessage(),
            ], 500);
        }
    }

    public function login(LoginRequest $request)
    {
        if (!auth()->attempt($this->credentials($request), $request->boolean('remember'))) {
            return $this->sendError('Email or password not found');
        }

        $token = $request->user()->createToken('token')->plainTextToken;

        return response()->json([
            "status" => ResponseAlias::HTTP_OK,
            "message" => __('Data successfully created!'),
            "data" => [
                'token' => $token,
                'user' => auth()->user()
            ]
        ], 201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        try {
            $validated = $request->validated();
            $user->update($validated);

            return response()->json([
                "status" => ResponseAlias::HTTP_OK,
                "message" => __('User data successfully updated!'),
                "data" => $user,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                "status" => ResponseAlias::HTTP_BAD_REQUEST,
                "message" => __('An error occurred while updating the user data!'),
                "error" => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return response()->json(__('User successfully deleted!'));
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
