<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product') ? $this->route('product')->id : null;

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'sku' => ['sometimes', 'required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($productId)],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'base_price' => ['sometimes', 'required', 'numeric', 'min:0'],
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
