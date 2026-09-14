<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $primaryCategory = $this->categories->first();

        return [
            'id' => $this->sku ?? (string)$this->id,
            'db_id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'category' => $primaryCategory ? $primaryCategory->name : 'General',
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'icon' => $this->icon,
            'plate' => $this->plate,
            'price' => (float)$this->base_price,
            'compareAt' => $this->compare_price ? (float)$this->compare_price : null,
            'desc' => $this->description ?? $this->short_description,
            'colors' => $this->colors ?? [],
            'sizes' => $this->sizes,
            'stock' => (int)$this->stock,
            'rating' => (float)$this->rating,
            'reviews' => (int)$this->reviews_count,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
            'is_featured' => (bool)$this->is_featured,
            'is_active' => (bool)$this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
