<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filtering, search, and sorting.
     */
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'brand', 'images', 'variants.attributeValues.attribute'])->where('is_active', true);

        // Filter by search query (q)
        if ($search = $request->input('q') ?? $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhereHas('categories', function ($catQuery) use ($search) {
                      $catQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category (category)
        if ($category = $request->input('category')) {
            $categories = is_array($category) ? $category : explode(',', $category);
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('name', $categories)
                  ->orWhereIn('slug', $categories);
            });
        }

        // Filter by max price (maxPrice)
        if ($maxPrice = $request->input('maxPrice') ?? $request->input('max_price')) {
            $query->where('base_price', '<=', (float)$maxPrice);
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        switch ($sort) {
            case 'price-asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'featured':
            default:
                $query->orderBy('is_featured', 'desc')->orderBy('id', 'asc');
                break;
        }

        // Pagination or All
        $perPage = $request->input('per_page', 50);
        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']) . '-' . Str::random(5);

        $product = Product::create($data);

        if (!empty($data['category_ids'])) {
            $product->categories()->sync($data['category_ids']);
        }

        $product->load(['categories', 'brand', 'images']);

        return (new ProductResource($product))
            ->response()
            ->setStatusCode(210);
    }

    /**
     * Display the specified product by ID, SKU, or Slug.
     */
    public function show(string $id)
    {
        $product = Product::with(['categories', 'brand', 'images', 'variants.attributeValues.attribute'])
            ->where('id', $id)
            ->orWhere('sku', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return new ProductResource($product);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::where('id', $id)
            ->orWhere('sku', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $data = $request->validated();

        $product->update($data);

        if (array_key_exists('category_ids', $data)) {
            $product->categories()->sync($data['category_ids']);
        }

        $product->load(['categories', 'brand', 'images']);

        return new ProductResource($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::where('id', $id)
            ->orWhere('sku', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
