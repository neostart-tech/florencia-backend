<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FideliteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'pourcentage' => $this->pourcentage,
            'is_active' => $this->is_active,

            'user' => $this->whenLoaded('user'),

            'created_at' => $this->created_at,
        ];
    }
}
