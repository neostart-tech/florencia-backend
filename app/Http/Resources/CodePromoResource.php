<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CodePromoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'pourcentage' => $this->pourcentage,
            'date_debut' => $this->date_debut,
            'date_fin' => $this->date_fin,

            'users' => $this->whenLoaded('users'),

            'created_at' => $this->created_at,
        ];
    }
}
