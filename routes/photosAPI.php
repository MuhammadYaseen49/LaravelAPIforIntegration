<?php

use App\Http\Controllers\PhotosController;
use Illuminate\Support\Facades\Route;



Route::group(["middleware" => ["UserLoggedIn"]], function(){

    Route::post("uploadPhoto", [PhotosController::class, "uploadPhoto"]);
    Route::post("myPhotos", [PhotosController::class, "myPhotos"]);
    Route::post("updatePhotoPrivacy", [PhotosController::class, "updatePhotoPrivacy"]);
    Route::post("deletePhoto/{id}", [PhotosController::class, "deletePhoto"]);
    Route::post("searchPhoto", [PhotosController::class, "searchPhoto"]);
 
});
