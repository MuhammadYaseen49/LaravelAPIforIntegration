<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Tokens;
use Illuminate\Http\Request;

class verifylogIn
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $userID = decodingUserID($request);
            $userExist = Tokens::where("userID", $userID)->first();
            if (!isset($userExist)) {
                return response([
                    "message" => "User not Logged In!"
                ]);
            } else {
                return $next($request);
            }
        } catch (Exception $e) {
        }
    }
}
