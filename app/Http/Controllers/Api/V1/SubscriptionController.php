<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
  public function subscribers(User $user)  {

    return  $user->subscribers()->count();
  }

  public function toogleSubscribe(User $user)  {
    if($user->id!==auth()->id()){
      if($user->isSubscribed()){
        return $user-> unSubscribeTo();
      }else{
        return $user-> subscribeTo();
      }
    }
  }
  
}
