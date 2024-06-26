<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\PasswordComfirmationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class PasswordController extends Controller
{
    public function changePassword(PasswordComfirmationRequest $request) {
       
        $validedRequest=collect($request->validated());

        $user = Auth::user();
        $user->update([
            "password" =>Hash::make($validedRequest->new_password)
        ]);

        $user->tokens()->delete();

        return response([
            "success"=>true,
            "message"=>"successfully update"
        ]);
    }

}
