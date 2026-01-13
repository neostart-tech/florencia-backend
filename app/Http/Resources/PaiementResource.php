<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaiementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'moyen_paiement' => $this->moyen_paiement,
            'reference_transaction' => $this->reference_transaction,
            'statut' => $this->statut,

            // owner polymorphique
            'owner_type' => $this->owner_type,
            'owner_id' => $this->owner_id,
            'owner' => $this->whenLoaded('owner'),

            'created_at' => $this->created_at,
        ];
    }
}
