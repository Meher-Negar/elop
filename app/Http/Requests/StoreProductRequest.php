<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'in:active,draft,archived'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'icon' => ['nullable', 'string'],
            'plate' => ['nullable', 'string'],
            'colors' => ['nullable', 'array'],
            'sizes' => ['nullable', 'array'],
            'stock' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
