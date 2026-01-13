<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendrierResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'debut' => $this->debut,
            'fin' => $this->fin,
            'is_active' => $this->is_active,

            'horaires' => $this->whenLoaded('horaires'),

            'created_at' => $this->created_at,
        ];
    }
}
