<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Products;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cartItems = Cart::where('user_id',$user_id)->get();
            return response()->json([
                'status' => 200,
                'cart' => $cartItems,
            ]);
        }else{
            return response()->json([
                'status' => 401,
                'message' => 'Login to view cart data',
            ]);

        }
    }

    public function create()
    {
        //
    }

    public function addtocart(Request $request)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;

            $productCheck = Products::where('id',$product_id)->first();
            if ($productCheck) {
                $cartCheck = Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists();
                if ($cartCheck) {
                    return response()->json([
                        'status' => 409,
                        'message' => $productCheck->name. ' Already in Cart',
                    ]);
                } else {
                    $cartItem = new Cart();

                    $cartItem->user_id = $user_id;
                    $cartItem->product_id = $product_id;
                    $cartItem->product_qty = $product_qty;
                    $cartItem->save();

                    return response()->json([
                        'status' => 201,
                        'message' => 'Added to Cart',
                    ]);
                }
                
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found',
                ]);
            }
            
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login/Signup to make a purchase',
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

    public function updatequantity($cart_id, $scope)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();
            $qty = Products::where('id', $cartItem->product_id);
            $qty = $qty->qty;
            if ($scope == "inc") {
                if ($cartItem->product_qty < $qty) {
                    $cartItem->product_qty += 1;
                }
            } elseif ($scope == "dec") {
                if ($cartItem->product_qty > 1) {
                    $cartItem->product_qty -= 1;
                }
            }

            $cartItem->update();

            return response()->json([
                'status' => 200,
                'message' => 'Quantity Updated',
            ]);

        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }

    }

    public function destroy($cart_id)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cartItem = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();
            if ($cartItem) {
                $cartItem->delete();
                return response()->json([
                    'status' => 200,
                    'message' => "Cart Item Removed successfully",
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'No Cart Id Found',
                ]);
            }


        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Login to continue',
            ]);
        }
    }

}
