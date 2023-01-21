<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request){

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return new ApiErrorResponse(null,'These credentials do not match our records.', Response::HTTP_NOT_FOUND );
        }

        $token = $user->createToken('currency_convertion')->plainTextToken;

        $response = [
            'user' => $user,
            'access_token' => $token
        ];

        return new ApiSuccessResponse($response, ['message' => "Login successful!"] );
    }
}
