<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class userResource extends JsonResource
{ 
    public function toArray($request)
    {
        // return parent::toArray($request);

        //With Resource
        return [
            'Name' => $this->name,
            'Email' => $this->email,
            'Age' => $this->age,
            'Profile Picture' => $this->profile_picture
        ];
    }
}
