<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CheckoutController;
use App\Http\Controllers\API\FrontendController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// Route::post('add-category', [CategoryController::class, 'store']);

Route::middleware('auth:sanctum', 'isApiAdmin')->group( function () {
        Route::get('/checkingAuthenticated', function () {
            return response()->json(['message' => 'You are In', 'status' => 200], 200);
        });
        Route::post('/admin-add-category', [CategoryController::class, 'store']);
        Route::post('/admin-add-store', [StoreController::class, 'store']);
        Route::get('/admin-edit-category/{id}', [CategoryController::class, 'edit']);
        Route::put('/admin-update-category/{id}', [CategoryController::class, 'update']);
        Route::delete('/admin-delete-category/{id}', [CategoryController::class, 'destroy']);

        Route::post('/admin-store-product', [ProductController::class, 'store']);
        // Route::get('/admin-edit-product/{id}', [ProductController::class, 'edit']);
        Route::post('/admin-update-product/{id}', [ProductController::class, 'update']);
        Route::delete('/admin-delete-product/{id}', [ProductController::class, 'destroy']);
    }
);

Route::middleware('auth:sanctum', 'isApiOwner')->group( function () {
        Route::get('/checkingAuthenticatedOwner', function () {
            return response()->json(['message' => 'You are In', 'status' => 200], 200);
        });
        Route::post('/add-category', [CategoryController::class, 'store']);
        Route::post('/add-store', [StoreController::class, 'store']);
        Route::get('/edit-category/{id}', [CategoryController::class, 'edit']);
        Route::put('/update-category/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete-category/{id}', [CategoryController::class, 'destroy']);

        Route::post('/store-product', [ProductController::class, 'store']);
        // Route::get('/edit-product/{id}', [ProductController::class, 'edit']);
        Route::post('/update-product/{id}', [ProductController::class, 'update']);
        Route::delete('/delete-product/{id}', [ProductController::class, 'destroy']);
    }
);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/all-stores', [StoreController::class, 'allStores']);
    Route::get('/view-store', [StoreController::class, 'index']);

    Route::post('/view-category', [CategoryController::class, 'index']);
    Route::get('/product-view-category/{id}', [CategoryController::class, 'product']);

    Route::get('/fetchProducts/{slug}', [FrontendController::class, 'products']);
    Route::get('viewproductdetail/{category_slug}/{product_slug}', [FrontendController::class, 'viewProduct']);

    Route::post('/view-products', [ProductController::class, 'index']);
    Route::get('/edit-product/{id}', [ProductController::class, 'edit']);

    Route::post('/add-to-cart', [CartController::class, 'addtocart']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::put('/update-cartquantity/{cart_id}/{scope}', [CartController::class, 'updatequantity']);
    Route::delete('/delete-cartitem/{cart_id}', [CartController::class, 'destroy']);

    // CHECKOUT
    Route::post('/validate-order', [CheckoutController::class, 'validateorder']);
    Route::post('/place-order', [CheckoutController::class, 'placeorder']);

    Route::get('/orders', [OrderController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route::get('/view-category', [CategoryController::class, 'index']);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });