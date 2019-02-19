<?php

namespace App\Http\Middleware;

use Closure;
use App\Room;

class RoomAccess
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
        if(auth()->user()->roomOwner($request->route('room'))) {
            return $next($request);
        }
        abort(403);
    }
}
