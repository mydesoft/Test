<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserType;
use App\Enums\HttpStatusCode;

class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->user_type == UserType::ADMIN->value) {
            
              return $next($request);
        }
       else{
            return response()->json([
            'statusText' => 'You have to be an admin to access this resource',
            'statusCode' => HttpStatusCode::FORBIDDEN,
        ], HttpStatusCode::FORBIDDEN->value);
        }
    }
}
