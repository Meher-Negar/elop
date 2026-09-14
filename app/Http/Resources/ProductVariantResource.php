<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => (float)$this->price,
            'compare_price' => $this->compare_price ? (float)$this->compare_price : null,
            'cost_price' => $this->cost_price ? (float)$this->cost_price : null,
            'weight' => $this->weight ? (float)$this->weight : null,
            'barcode' => $this->barcode,
            'status' => $this->status,
            'attributes' => $this->whenLoaded('attributeValues', function () {
                return $this->attributeValues->map(function ($av) {
                    return [
                        'attribute_id' => $av->attribute_id,
                        'attribute_name' => $av->attribute?->name,
                        'attribute_slug' => $av->attribute?->slug,
                        'value' => $av->value,
                    ];
                });
            }),
        ];
    }
}
