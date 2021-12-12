<?php

use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get("forgetPassword", [ForgetPasswordController::class, "forgetPassword"])
->middleware('AccountVerification');

Route::post("resetPassword/{token}/{email}", [ForgetPasswordController::class, "resetPassword"]);
