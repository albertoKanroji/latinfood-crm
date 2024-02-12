<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductImageController extends Controller
{
public function index()
{
    $products = Product::select('image')->get();

    $imageUrls = $products->map(function($product) {
        return asset('storage/products/' . $product->image);
    });

    return response()->json($imageUrls);
}




}
