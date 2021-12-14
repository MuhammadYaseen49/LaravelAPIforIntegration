<?php

use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;


Route::group(["middleware" => ["UserLoggedIn"], function(){

    Route::post("givePermission", [PermissionController::class, "givePermission"]);
    Route::post("viewPhoto", [PermissionController::class, "viewPhoto"]);

 
});
