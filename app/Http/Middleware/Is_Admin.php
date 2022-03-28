<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Is_Admin
{
    const ADMIN = 34;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $roles = Auth::user()->roles;
        $check = 0;
        
        foreach($roles as $role){
            if($role['id'] == self::ADMIN){
                $check = 1;
                break;                
            }
        }
        if($check == 1){
            return $next($request);
        }else{
            return response()->json([
                'data' => 'bạn phải là admin',
            ]);
        }    
    }
}
