<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        //
    }

    public function products($slug)
    {
        $category = Category::where('slug', $slug)->where('status', '0')->first();
        if ($category) {
            $product = Products::where('category_id', $category->id)->where('status', '0')->get();
            if ($product) {
                return response()->json([
                    'status' => 200,
                    'product_data' => [
                        'product' => $product,
                        'category' => $category,
                    ],
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Product Not Found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found',
            ]);
        }
    }


    public function viewProduct($category_slug, $product_slug)
    {
        $category = Category::where('slug', $category_slug)->where('status', '0')->first();
        if ($category) {
            $product = Products::where('category_id', $category->id)->where('slug', $product_slug)->where('status', '0')->first();
            if ($product) {
                return response()->json([
                    'status' => 200,
                    'product' => $product,
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Product Not Found',
                ]);
            }
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Category Not Found',
            ]);
        }
    }




}
