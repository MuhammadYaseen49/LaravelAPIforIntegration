<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class photosResource extends JsonResource
{ 
    public function toArray($request)
    {
        // return parent::toArray($request);

        //With Resource
        return [
                'Photo' => $this->address
            ];
    }
}
