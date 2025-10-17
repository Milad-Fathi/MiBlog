<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckWriterRole
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
        // Check if the user is not logged in
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        // Retrieve the current user
        $user = Auth::user();
        
        if ($user->role == 'writer' or $user->role == 'admin') {

            // Proceed with the request
            return $next($request);
            
        }else{
            abort(403, 'Unauthorized action.');
        }
    }
}
