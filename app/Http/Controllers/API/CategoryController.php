<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
  
    public function index(Request $request)
    {
        $store_id = $request->store_id;

        $category = Category::where('store_id', $store_id)->get();
        return response()->json([
            'status' => 200,
            'message' => "Query Successful",
            'category' => $category,
        ]);
    }

    public function product($id)
    {

        $category = Category::where('store_id', $id)->get();
        return response()->json([
            'status' => 200,
            'message' => "Query Successful",
            'category' => $category,
        ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'store_id' => 'required|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'validation_errors' => $validator->messages(),
            ]);
        } else {

            $category = new Category();

            $category->slug = $request->input('slug');
            $category->name = $request->input('name');
            $category->store_id = $request->input('store_id');
            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Category Added Successfully',
            ]);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No category Id Found',
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'store_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'validation_errors' => $validator->messages(),
            ]);
        } else {

            $category = Category::find($id);

            if ($category) {
                $category->slug = $request->input('slug');
                $category->name = $request->input('name');
                $category->store_id = $request->input('store_id');
                $category->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Category Updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Category ID not found',
                ]);
            }
        }
    }


    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 200,
                'message' => "Category deleted successfully    ",
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No category Id Found',
            ]);
        }
    }
}
