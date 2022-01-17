<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $store_id = $request->store_id;

        $products = Products::where('store_id', $store_id)->get();
        return response()->json([
            'status' => 200,
            'message' => "Query Successful",
            'products' => $products,
        ]);
    }

    
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'brand' => 'required|max:20',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:4',
            'unit' => 'required|max:191',
            'image' => 'required|image|mimes:jpeg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'validation_errors' => $validator->messages(),
            ]);
        } else {

            $product = new Products();
            $product->store_id = $request->input('store_id');
            $product->category_id = $request->input('category_id');
            $product->slug = $request->input('slug');
            $product->name = $request->input('name');
            $product->description = $request->input('description');

            $product->brand = $request->input('brand');
            $product->selling_price = $request->input('selling_price');
            $product->original_price = $request->input('original_price');
            $product->qty = $request->input('qty');
            $product->unit = $request->input('unit');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move('uploads/product/', $filename);
                $product->image = 'uploads/product/' . $filename;
            }

            $product->status = $request->input('status') == true ? '1' : '0';

            $product->save();

            return response()->json([
                'status' => 200,
                'message' => 'Product ' . $product->name . ' Added Successfully',
            ]);
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $product = Products::find($id);

        if ($product) {
            return response()->json([
                'status' => 200,
                'product' => $product,
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No product Id Found',
            ]);
        }

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|max:191',
            'category_id' => 'required|max:191',
            'slug' => 'required|max:191',
            'name' => 'required|max:191',
            'brand' => 'required|max:20',
            'selling_price' => 'required|max:20',
            'original_price' => 'required|max:20',
            'qty' => 'required|max:4',
            'unit' => 'required|max:191',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'validation_errors' => $validator->messages(),
            ]);
        } else {
            $product = Products::find($id);

            if ($product) {

                $product->store_id = $request->input('store_id');
                $product->category_id = $request->input('category_id');
                $product->slug = $request->input('slug');
                $product->name = $request->input('name');
                $product->description = $request->input('description');

                $product->brand = $request->input('brand');
                $product->selling_price = $request->input('selling_price');
                $product->original_price = $request->input('original_price');
                $product->qty = $request->input('qty');
                $product->unit = $request->input('unit');

                if ($request->hasFile('image')) {

                    $path = $product->image;

                    if (File::exists($path)) {
                        File::delete($path);
                    }

                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/product/', $filename);
                    $product->image = 'uploads/product/' . $filename;
                }

                $product->status = $request->input('status');

                $product->update();

                return response()->json([
                    'status' => 200,
                    'message' => 'Product ' . $product->name . ' Updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No product Found',
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $product = Products::find($id);

        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => "product deleted successfully    ",
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No product Id Found',
            ]);
        }
    }
}
