<?php

use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\View\ComponentAttributeBag;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', function () {
    return view('home', [
        // 'items' => Items::all(),
        'categories' => Category::all(),
        'attributes' => new ComponentAttributeBag([]),
    ]);
});

Route::get('/orders', function () {
    return view('orders', [
        'orders' => Order::all(),
    ]);
});

Route::get('/categories/{category:id}', function (Category $category) {
    return view('categories', [
        'items' => $category->items,
        'currentCategory' => $category,
    ]);
});