<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->where('status', 'active')
            ->orderBy('sort_order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Display the specified category.
     */
    public function show(string $id)
    {
        $category = Category::withCount('products')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        return new CategoryResource($category);
    }
}
