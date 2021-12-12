<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("register", [UserController::class, "register"]);
Route::get("emailVerification/{token}/{email}",[UserController::class,'emailVerification']);
Route::post("login", [UserController::class, "login"])->middleware('AccountVerification');

Route::group(["middleware" => ["UserLoggedIn"]], function(){

    Route::post("logout", [UserController::class, "logout"]);
    Route::post("seeProfile", [UserController::class, "seeProfile"]);
    Route::post("updateProfile/{id}", [UserController::class, "updateProfile"]);

});
