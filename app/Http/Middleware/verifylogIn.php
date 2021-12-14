<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\Tokens;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class verifylogIn
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $token = $request->bearerToken();
            if(isset($token)){
                $userID = decodingUserID($request);
                $userExist = Tokens::where("userID", $userID)->first();
                if (!isset($userExist)) {
                    return response([
                        "Message" => "User not Logged In!"
                    ]);
                } else {
                    return $next($request);
                }
            }
            elseif($token == null) {
                return response([
                    "Message" => "Bad request!"
                ]);
            }
            else {
                return $next($request);
                }
        } catch (Exception $e) {
        }
    }
}
