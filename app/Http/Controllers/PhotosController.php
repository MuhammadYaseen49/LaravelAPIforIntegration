<?php

namespace App\Http\Controllers;

use App\Http\Requests\uploadPhoto;
use App\Http\Resources\photosResource;
use App\Models\photos;
use Illuminate\Http\Request;
use Throwable;

class PhotosController extends Controller
{
    public function uploadPhoto(uploadPhoto $request){
        try {
            $fields = $request->validated();

            $extension = $fields['name']->extension();
            $uniquePhoto = date('d-m-Y_H-i-s') . $fields['name']->getClientOriginalName();
            $directory = 'C:/xampp/htdocs/PF_Backend/Laravel/laravelAPIforIntegration/storage/app/user_images/uploaded_photos/';
            $address = $directory . $uniquePhoto;

            $fields['name']->storeAs('user_images/uploaded_photos/', $uniquePhoto);

            $userID = decodingUserID($request);

            if (isset($userID)) {
                photos::create([
                    'userID' => $userID,
                    'name' => $uniquePhoto,
                    'extension' => $extension,
                    'address' => $address,
                    'privacy' => $fields['privacy']
                ]);
                return response([
                    'Message' => 'Photo uploaded successfully',
                    'Shareable Link' => $address
                ]);
            }
            if (!isset($userID)) {
                return response([
                    'Message' => 'Cant upload photo without logging In'
                ]);
            }
            
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function myPhotos(Request $request){
        try{
            $userID = decodingUserID($request);
            $check = Photos::where('userID', $userID)->get();
        
            if ($check->isEmpty()) {
                return response([
                    'Message' => 'No Photos Found'
                ]);
            } else {
                return photosResource::collection($check);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function updatePhotoPrivacy(Request $request, $id){
        try{
            $userID = decodingUserID($request);

            $photo = photos::where('id', $id)->where('user_id', $userID);
            if ($photo == null) {
                return response([
                    'Message' => 'Request not found'
                ]);
            }
            if (isset($photo)) {
                $photo->update([
                    'privacy' => $request->privacy
                ]);
                return response([
                    'Message' => 'Privacy updated successfully'
                ]);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function deletePhoto($id){
        try{
            if (photos::where('id', $id)->delete($id)) {
                return response([
                    'Message' => 'Photo deleted successfully'
                ]);
            } else {
                return response([
                    'Message' => 'Not found'
                ]);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }

    public function searchPhoto(Request $request){
        try{
            $searchable = $request->name;

            $photo = photos::where('privacy','public')->where('name', 'LIKE', '%' . $searchable . '%')->orWhere('address', 'LIKE', '%' . $searchable . '%')->orWhere('extension', 'LIKE', '%' . $searchable . '%')->get();
            if (count($photo) > 0)
                return response([
                    'Photo' => $photo
                ]);
            else {
                return response([
                    'Message' => 'No result'
                ]);
            }
        } catch (Throwable $e) {
            return $e->getMessage();
        }
    }
}
