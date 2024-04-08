<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\StoreAuthRequest;
use App\Http\Resources\Auth\RegisteredResources;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function store(StoreAuthRequest $request)
    {

        $request->validated();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return new RegisteredResources($user);
    }

    public function profile()
    {

        return response([
            "success" => true,
            "data" => Auth::user()
        ]);
    }
}
