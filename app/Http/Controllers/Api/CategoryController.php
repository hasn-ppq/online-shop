<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
   public function index()
{
    return Cache::remember('categories', 300, function () {
        return Category::all();
    });
}
}
