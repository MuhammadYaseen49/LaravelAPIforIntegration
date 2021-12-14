<?php

namespace App\Http\Controllers;

use App\Http\Requests\forgetPassword;
use App\Http\Requests\resetPasswordRequest;
use App\Models\ResetPassword;
use App\Models\User;
use App\Services\GenerateToken;
use Illuminate\Support\Facades\Hash;
use Throwable;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(forgetPassword $request){
        try{
            $fields = $request->validated();

            $token = (new GenerateToken)->createToken($fields['email']);
            $PasswordReset_Token = 'http://127.0.0.1:8000/api/resetPassword/' . $token . '/' . $fields['email'];
            
            $findEmail = User::where('email', $fields['email'])->first();
            $findEmail->PasswordReset_Token = $token;
            $findEmail->save();

            ResetPassword::create([
                'email' => $fields['email'],
                'token' => $token,
                'expiry' => 0
            ]);

            resetPassword::dispatch($fields['email'], $PasswordReset_Token);
            
            return [
                'Message' => "Reset password request sent successfully!"
            ];
            
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
    
    public function resetPassword(resetPasswordRequest $request, $token, $email){
        try{
            $fields = $request->validated();

            $verify = ResetPassword::where('email', $email)->where('token', $token)->first();
            
            if($verify == null){
                return [
                    'Message' => "Incorrect Data!"
                ]; 
            }

            $verify->expiry = 1;
            $verify->save();

            User::where('email',$email)->update([
                'password' => Hash::make($fields['password'])
            ]);

            return [
                'Message' => "Password changed successfully!"
            ]; 

        } catch (Throwable $e) {
            return $e->getMessage();
        }

    }
}
