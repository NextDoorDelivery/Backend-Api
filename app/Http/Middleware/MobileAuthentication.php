<?php

/**
 * This controller handles mobile device authentication using mobile_token and device_uuid.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LkMobileDeviceToken;


class MobileAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $request->validate([
            'mobile_token' => 'required',
            'device_uuid' => 'required',
        ]);

        $result = LkMobileDeviceToken::where('DeviceUuid', $request['device_uuid'])
                  ->where('MobileAuthToken', $request['mobile_token'])->first();

        if($result == null){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
