<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LoginResourceSuccess extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { 
        $isAdmin=Auth::user()->is_admin==1?true:false;

        return [
            "success" => true,
            "message" => "Login successfully",
            "user" => Auth::user(),
            "isAdmin"=> $isAdmin,
            "token" =>Auth::user()->createToken(time())->plainTextToken
        ];
    }
}
