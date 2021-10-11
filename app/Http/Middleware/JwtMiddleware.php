<?php

namespace App\Http\Middleware;

use App\Models\Permission;
use App\Models\User;
use Closure;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userPermissions = [];
            if($user->permissions){
                $userPermissions = [];
                $userPermissions = array_map('intval', explode(',', $user->permissions));
            }else{
                $userPermissions = $user->role->permissions->pluck('id')->toArray();
            }
            
            $allPermissions = Permission::all();
            
            foreach($allPermissions as $permission){
                Gate::define($permission->name, function($user) use ($permission,$userPermissions) {
                    return in_array($permission->id,$userPermissions) ? true : false;
                });
            }
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['message' => 'Token is Invalid'],401);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['message' => 'Token is Expired'],401);
            }else{
                return response()->json(['message' => 'Authorization Token not found'],401);
            }
        }
        return $next($request);
    }
    
}