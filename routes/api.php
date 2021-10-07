<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('categories', [CategoryController::class, 'index']);
// Route::post('categories', [CategoryController::class, 'store']);
// Route::get('categories/{category:id}', [CategoryController::class, 'show']);
// Route::delete('categories/{category:id}', [CategoryController::class, 'destroy']);
// Route::put('categories/{category:id}', [CategoryController::class, 'update']);

// ----------------------------------------------------------------------------

// Route::get('customers', [CustomerController::class, 'index']);
// Route::post('customers', [CustomerController::class, 'store']);
// Route::get('customers/{customer:id}', [CustomerController::class, 'show']);
// Route::delete('customers/{customers}', [CustomerController::class, 'destroy']);






Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('orders', OrderController::class);
    Route::apiResource('offices', OfficeController::class);
    Route::apiResource('employees', EmployeeController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('categories', CategoryController::class);
});

//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