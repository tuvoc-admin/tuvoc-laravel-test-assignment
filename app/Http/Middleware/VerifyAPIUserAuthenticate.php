<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class VerifyAPIUserAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->get('token');
        $user = User::where('uuid', '=', $token)->first();
        if (!$user) {
            return response()->json(['success'=>false, 'message' => 'Login Fail, bad token']);
        }
        
       return $next($request);
    }
}
