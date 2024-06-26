<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\StoreAuthRequest;
use App\Http\Requests\V1\User\MakeAdminRequest;
use App\Http\Requests\V1\User\UpdateUserToAdminRequest;
use App\Http\Resources\Auth\RegisteredResources;
use App\Http\Resources\V1\User\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;


class RegisteredUserController extends Controller
{
    public function store(StoreAuthRequest $request)
    {
        $user = User::create($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return new RegisteredResources($user);
    }

    public function dashboard()
    {

        return response([
            "success" => true,
            "user" => Auth::user()
        ]);
    }

    public function makeAdmin(MakeAdminRequest $request)
    {

  
        $user = User::create($request->validated());
        return new UserResource($user);

    }

    public function updateUserToAdmin(UpdateUserToAdminRequest $request, User $user)
    {

        $user->update($request->validated());
        $updatedUser=  new UserResource($user);
        
        $adminOruser=$updatedUser->is_admin==true?" become admin successfully":" is not admin anymore";
         
        $isAdmin=$updatedUser->is_admin==true;

        return [
            "user" =>  $updatedUser,
            'message' => $updatedUser->name. $adminOruser,
            'success'=>true,
            "isAdmin"=>  $isAdmin
        ];

    }
}
