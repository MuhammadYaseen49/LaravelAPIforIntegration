<?php

namespace App\Http\Controllers;

use App\Http\Requests\userLogIn;
use App\Http\Requests\userRegistration;
use App\Http\Resources\userResource;
use App\Jobs\emailRegistration;
use App\Models\User;
use App\Models\Tokens;
use App\Services\GenerateToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Throwable;

class UserController extends Controller{

    public function register(userRegistration $request){
       try {
            $fields = $request->validated();

            $uniquePhoto = time() . $fields['profile_picture']->getClientOriginalName();
            $fields['profile_picture']->storeAs('user_images/profile_photos/', $uniquePhoto);
            $directory = 'C:/xampp/htdocs/PF_Backend/Laravel/laravelAPIforIntegration/storage/app/user_images/profile_photos/';
            $address = $directory . $uniquePhoto;
            
            $token = (new GenerateToken)->createToken($fields['email']);
            $verificationURL = 'http://127.0.0.1:8000/api/emailVerification/' . $token . '/' . $fields['email'];
            User::create([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'password' => Hash::make($fields['password']),
                'age' => $fields['age'],
                'profile_picture' => $address, 
                'Verification_Token' => $token,
                'email_verified_at' => null,
                'PasswordReset_Token' => null
            ]);
            emailRegistration::dispatch($fields['email'], $verificationURL); //php artisan queue:work
            return [
                'Message' => "Registration request sent successfully!"
            ];

        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function emailVerification($token, $email){
        try {
            $accountVerify = User::where('email', $email)->first();
            if($accountVerify == null){
                return response([
                    'Message' => 'User not found'
                ]);
            }else if($accountVerify['Verification_Token'] != $token){
                return response([
                    'Message' => 'Invalid request'
                ]);
            }else if ($accountVerify['email_verified_at'] != null) {
                return response([
                    'Message' => 'Already verified!'
                ]);
            }else if ($accountVerify) {
                $accountVerify['email_verified_at'] = date('Y-m-d h:i:s');
                $accountVerify->save();
                return response([
                    'Message' => 'User verified successfully!'
                ]);
            } 
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function login(userLogIn $request){
        try {
            $fields = $request->validated();

            $user = User::where('email', $fields['email'])->first();
            if ($user['id'] != null) {
                if (Hash::check($fields['password'], $user['password'])) {
                    $isLoggedIn = Tokens::where('userID', $user['id'])->first();
                    if ($isLoggedIn) {
                        return response([
                            "Message" => "User already logged In",
                        ]);
                    }
                    $token = (new GenerateToken)->createToken($user['id']);
                    Tokens::create([
                        "userID" => $user['id'],
                        "token" => $token
                    ]);

                    return response([
                        'Message' => 'Logged in successfully!',
                        'Token' => $token
                    ]);

                } else {
                    return response([
                        'Message' => 'Invalid email or password'
                    ]);
                }
            } else {
                return response([
                    "Message" => "User not found"
                ]);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function logout(Request $request){
        try {
            $userID = decodingUserID($request);
            $userExist = Tokens::where("userID", $userID)->first();
            if ($userExist) {
                $userExist->delete();
            }

            return response([
                "message" => "logout successfull"
            ]);
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function seeProfile(Request $request){
        try {
            $userID = decodingUserID($request);
            $check = Tokens::where('token', $request->bearerToken())->first();
            if (!isset($check)) {
                return response([
                    "message" => "Invalid request!"
                ]);
            }

            if ($userID) {
                $profile = User::find($userID);
                return response([
                    "Profile" => new userResource($profile)
                ]);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function updateProfile(Request $request, $id){
        try {
            $user = User::all()->where('id', $id)->first();

            if (isset($user)) {             
                if ($request->file('profile_picture') != null) {
                    $uniquePhoto = time() . $user['profile_picture']->getClientOriginalName();
                    $directory = 'C:/xampp/htdocs/PF_Backend/Laravel/laravelAPIforIntegration/storage/app/user_images/profile_photos/';
                    $address = $directory . $uniquePhoto;
                    $request->file('profile_picture')->storeAs('user_images/profile_photos/', $uniquePhoto);
                    $user->profile_picture = $address;
                }
                
                $user->save();

                return response([
                    'message' => 'You have successfully updated your Profile',
                ]);
            }
            if ($user == null) {
                return response([
                    'message' => 'User not found',
                ]);
            }

        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
