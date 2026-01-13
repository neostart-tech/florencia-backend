<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCodePromoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'user' => $this->whenLoaded('user'),
            'code_promo' => $this->whenLoaded('code_promo'),

            'created_at' => $this->created_at,
        ];
    }
}
