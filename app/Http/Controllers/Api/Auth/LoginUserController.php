<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Resources\Auth\LoginResourceFail;
use App\Http\Resources\Auth\LoginResourceSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    public function login(LoginRequest $request){

        $creditials=$request->validated();
   
       
        if(Auth::attempt($creditials)){
           return new LoginResourceSuccess($creditials);
        }

        return new LoginResourceFail($creditials);
    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "success" => true,
            "message" => "logout successfully"
        ]);
    }
}
