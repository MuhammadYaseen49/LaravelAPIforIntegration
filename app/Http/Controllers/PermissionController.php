<?php

namespace App\Http\Controllers;

use App\Models\permission;
use App\Models\photos;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    
    public function givePermission(Request $request)
    {
        $fields = $request->validate([
            'granted_to' => 'required|email',
            'address' => 'required'
        ]);
        //call a helper function to decode user id
        $userID = decodingUserID($request);

        $receiverAddress = photos::where('address', $fields['address'])->first();
        $Address= $receiverAddress->address;
        $sender = User::where('id', $userID)->first();
        $User = User::where('email',$fields['granted_to'])->first();
        
        // dd($receiverAddress);

        if($receiverAddress == null){
            return [
                'Message' => 'Image Does not Exist'
            ];
        }
        if ($sender->email == $fields['granted_to']) {
            return [
                'Message' => 'you cannot give permission to yourself',
                'Address' => $Address
            ];
        }
        if ($User == null) {
            return [
                'User not Exist'
            ];
        }

        permission::create([
            'granted_to' => $fields['granted_to'],
            'granted_by' => $userID,
            'address' => $Address
        ]);

        return response([
            'message' => 'Permission has been granted'
        ], 200);
    }

    public function viewImage(Request $request)
    {
        dd("asa");
        $fields = $request->validate([
            'address' => 'required',
        ]);
        
        //call a helper function to decode user id
        $userID = decodingUserID($request);

        // dd($userID);
        // viewImage
        $imageAddress = photos::where('address', $fields['address'])->first();
        $address = $imageAddress->address;
        // dd($address);
        $user = User::where('id', $userID)->first();
        // dd($user);
        $access = permission::where('address', $fields['address'])->where('granted_to', $user['email'])->first();
        // dd($access);

        
        if ($imageAddress == null) {
            return response(['message' => 'Please Enter Valid address']);
        }
        if ($access == null) {
            return response(['message' => 'You dont have permission! Please request for Access']);
        }
        if ($imageAddress->privacy == 'hidden' && $access['address'] == $fields['address']) {
            return ['Message' => 'This Image is hidden'];
        }
        if ($access['granted_to'] == $user['email']) {
            return response([
                'Your Image to View' => $address
            ]);
        }
        // if ($access == null) {
        //     return response(['message' => 'you dont have permission!. Please grant for Access']);
        // }
        
        if ($access['granted_to'] == $user['email']) {
            return response([
                'address to View' => $imageAddress['address']
            ]);
        }
    }
}