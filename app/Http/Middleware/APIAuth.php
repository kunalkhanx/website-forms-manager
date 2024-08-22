<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class APIAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if(!$token){
            return response('', 401);
        }
        $token = str_replace('Bearer ', '', $token);
        $user = User::where('public_token', $token)->where('status', '>', 0)->first();
        if(!$user){
            return response('', 401);
        }
        Auth::setUser($user);
        return $next($request);
    }
}
