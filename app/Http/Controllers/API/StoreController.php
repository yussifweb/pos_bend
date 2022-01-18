<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Stores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth('sanctum')->user()->id;
        $store = Stores::where('user_id', $user_id)->get();
        return response()->json([
            'status' => 200,
            'store' => $store,
        ]);
    }


    public function create()
    {
        //
    }

    public function allStores()
    {
        $user_id = auth('sanctum')->user()->id;
        $store = Stores::where('status', '0')->where('user_id', $user_id)->get();
        return response()->json([
            'status' => 200,
            'store' => $store,
        ]);
    }

    public function store(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'image' => 'required|image|mimes:jpeg,png,jpeg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'validation_errors' => $validator->messages(),
                ]);
            } else {

                $store = new Stores;
                $store->user_id = auth('sanctum')->user()->id;
                $store->name = $request->input('name');

                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move('uploads/store/', $filename);
                    $store->image = 'uploads/store/' . $filename;
                }

                // $store->status = $request->input('status') == true ? '1' : '0';

                $store->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Store ' . $store->name . ' Added Successfully',
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login/Signup to create a store',
            ]);
        }
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        $store = Stores::find($id);

        if ($store) {
            $store->delete();
            return response()->json([
                'status' => 200,
                'message' => "store deleted successfully    ",
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No store Found',
            ]);
        }
    }
}
