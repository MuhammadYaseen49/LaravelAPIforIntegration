<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class VerifyRegistration
{
    public function handle(Request $request, Closure $next)
    {
        $email = $request->email;
        $Verification = User::where('email', $email)->first();

        if ($Verification->email_verified_at == null) {
            return response([
                "message" => "Account not registered!"
            ]);
        } else {
            return $next($request);
        }
    }
}
