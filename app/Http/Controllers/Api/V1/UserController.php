<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserCollection;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
   public function index(){

    $users=User::latest()->filter(request(["sort","order","is_admin"]))->get();
    
    return new UserCollection($users);


   }
}
