<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'path' => $this->path,

            // owner polymorphique
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'owner' => $this->whenLoaded('owner'),

            'created_at' => $this->created_at,
        ];
    }
}
