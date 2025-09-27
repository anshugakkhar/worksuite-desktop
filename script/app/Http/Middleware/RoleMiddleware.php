<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission as permisson;
use App\Models\RoleUser;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userID = user()->id;

            // Step 1: Retrieve all role IDs associated with the user
            $roleIds = RoleUser::where('user_id', $userID)->pluck('role_id');
            if(!empty($roleIds))
            {
                // Step 2: Retrieve permissions associated with these role IDs
                $rolePermissions = permisson::whereIn('role_id', $roleIds)
                ->where('personal_dashboard', '1')
                ->first();   
                if(!empty($rolePermissions->personal_dashboard))
                {
                    return $next($request);
        
                }else
                { 
                    return redirect()->route('emp.dashboard');
                    // abort(403);
                }
            }
            else
            { 
                return redirect()->route('emp.dashboard');
                // abort(403);
            }
    }
}
