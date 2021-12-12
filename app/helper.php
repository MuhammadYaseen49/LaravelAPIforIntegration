<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

function decodingUserID(Request $request)
{
    $getToken = $request->bearerToken();
    $key = config('constants.KEY');
    $decoded = JWT::decode($getToken, new Key($key, "HS256"));
    $userID = $decoded->id;
    return $userID;

}
