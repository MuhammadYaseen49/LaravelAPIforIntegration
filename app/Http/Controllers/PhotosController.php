<?php

namespace App\Http\Controllers;

use App\Http\Resources\photosResource;
use App\Models\photos;
use Illuminate\Http\Request;
use Throwable;

class PhotosController extends Controller
{
    public function uploadPhoto(Request $request){
        try {
            $fields = $request->validate([
                'name' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'privacy' => 'required'
            ]);

            if ($fields) {
                $extension = $fields['name']->extension();
                $uniquePhoto = time() . $fields['name']->getClientOriginalName();

                $fields['name']->storeAs('user_images/uploaded_photos/', $uniquePhoto);
                $directory = 'C:/xampp/htdocs/PF_Backend/Laravel/LaravelAPI_MySQL_Advance_VueJS/storage/app/user_images/uploaded_photos/';
                $address = $directory . $uniquePhoto;
            }

            $userID = decodingUserID($request);

            if (isset($userID)) {
                photos::create([
                    'userID' => $userID,
                    'name' => $uniquePhoto,
                    'extension' => $extension,
                    'address' => $address,
                    'privacy' => $fields['privacy']
                ]);
            }
            //if user is not logged in
            if (!isset($userID)) {
                return response([
                    'message' => 'Cant upload photo without logging In'
                ]);
            }
            //message on Success
            return response([
                'message' => 'Image Upload successfully',
                'shareable Link' => $address
            ], 200);
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function deletePhoto($id){

        if (photos::where('id', $id)->delete($id)) {
            return response([
                'Status' => '200',
                'message' => 'Image Deleted successfully'
            ], 200);
        } else {
            return response([
                'message' => 'Not Found.'
            ], 200);
        }
    }

    public function myPhotos(Request $request){
        $userID = decodingUserID($request);
        $check = Photos::where('userID', $userID)->get();
        //message on Successfully

        if ($check->isEmpty()) {
            return response([
                'Message' => 'No Photos Found'
            ], 200);
        } else {
            return photosResource::collection($check);
        }

    }

    public function searchPhoto(Request $request){
        $searchable = $request->name;

        $photo = photos::where('privacy','public')->where('name', 'LIKE', '%' . $searchable . '%')->orWhere('address', 'LIKE', '%' . $searchable . '%')->get();
        if (count($searchable) > 0)
            return response(['Photo' => $photo], 200);
        else {
            return response(['Message' => 'No Details found']);
        }
    }

   
}
