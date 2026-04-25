<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
{
    $cacheKey = 'products_' . md5(json_encode($request->all()));

    $products = Cache::remember($cacheKey, 60, function () use ($request) {

        return Product::query()

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })

            ->when($request->category_id, function ($query) use ($request) {
                $query->where('category_id', $request->category_id);
            })

            ->latest()
            ->paginate($request->get('per_page', 10));
    });

    return ProductResource::collection($products);
}
}