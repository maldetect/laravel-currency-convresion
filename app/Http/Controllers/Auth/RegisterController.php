<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\ApiErrorResponse;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try{
           $validated = $request->validated();
           $validated['password'] = bcrypt($validated['password']);
           $user = User::create($validated);
        }catch(Throwable $e){
            return new ApiErrorResponse($e,'Unable to process your request!');
        }

        return new ApiSuccessResponse($user, ['message'=>'Account has been created!']);
    }
}
