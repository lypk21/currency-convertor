<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UserLoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Utils\Constants;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{

    public function register(UserRegisterRequest $request) {
        event(new Registered($user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ])));
        $user->generateToken();
        return $this->successResponse($user, "register successfully", Constants::HTTP_CODE_CREATE);
    }

    public function login(UserLoginRequest $request) {
        if(Auth::attempt($request->only(['email','password']))) {
            $user = Auth::guard()->user();
            $user->generateToken();
            return $this->successResponse($user, "login successfully");
        }
        return $this->errorResponse("login failed" );
    }

    public function logout(Request $request) {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return $this->successResponse($user, "User logged out");
    }
}
