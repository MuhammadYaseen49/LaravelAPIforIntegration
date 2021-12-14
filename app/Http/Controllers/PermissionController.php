<?php

namespace App\Http\Controllers;

use App\Http\Requests\givePermission;
use App\Http\Requests\viewPhoto;
use App\Models\permission;
use App\Models\photos;
use App\Models\User;
use Throwable;

class PermissionController extends Controller
{
    
    public function givePermission(givePermission $request){
        try{
            $fields = $request->validated();

            $userID = decodingUserID($request);

            $receiverAddress = photos::where('address', $fields['address'])->first();
            $Address= $receiverAddress->address;
            $sender = User::where('id', $userID)->first();
            $User = User::where('email',$fields['access_to'])->first();
            
            if($receiverAddress == null){
                return [
                    'Message' => 'Photo does not exist'
                ];
            }
            if ($sender['email'] == $fields['access_to']) {
                return response([
                    'Message' => "You can't give permission to yourself"
                ]);
            }
            if ($User == null) {
                return response([
                    'Message' => 'User not Exist'
                ]);
            }

            permission::create([
                'access_to' => $fields['access_to'],
                'granted_by' => $userID,
                'address' => $Address
            ]);

            return response([
                'Message' => 'Permission given successfully'
            ]);
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function viewPhoto(viewPhoto $request){
        try{
            $fields = $request->validated();
            
            $userID = decodingUserID($request);

            $PhotoAddress = photos::where('address', $fields['address'])->first();
            $address = $PhotoAddress->address;
            $user = User::where('id', $userID)->first();
            $permission = permission::where('address', $fields['address'])->where('access_to', $user['email'])->first();
            
            if ($PhotoAddress == null) {
                return response([
                    'Message' => 'Bad request'
                ]);
            }

            if ($permission == null) {
                return response([
                    'Message' => 'You dont have permission!'
                ]);
            }

            if ($PhotoAddress->privacy == 'hidden' && $permission['address'] == $fields['address']) {
                return response([
                    'Message' => 'Photo is hidden'
            ]);
            }

            if ($permission['access_to'] == $user['email']) {
                return response([
                    'Requested photo' => $address
                ]);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}