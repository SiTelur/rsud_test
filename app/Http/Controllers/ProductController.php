<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     * Filters: is_active = 1
     * Query param: search (applies LIKE to name and description)
     * Pagination: 10 per page
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Product::where('is_active', true);
        if (! is_null($search) && $search !== '') {
            $like = "%{$search}%";
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like);
            });
        }

        $products = $query->paginate(10);

        return ProductResource::collection($products);
    }
}
