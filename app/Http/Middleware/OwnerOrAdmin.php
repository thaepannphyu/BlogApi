<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OwnerOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blog = $request->route()->parameter('blog');

        // if (Auth::check() && Auth::user()->id === $blog->user_id) {
        //     return $next($request);
        // }

        if(Auth::user()->is_admin==1 ||Auth::user()->id === $blog->user_id){
            return $next($request);
        }

       

        return response()->json([
            'message' => 'Access denied. You do not have permission to access '
        ], 401);// Or return a 403 response
    }
}
