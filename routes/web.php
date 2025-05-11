<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShippingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

//home
Route::get('/home', [HomeController::class, 'index'])->name('home');

//admin login
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'admin'])->name('admin');

});

//banner
Route::resource('banner', BannerController::class);
//category
Route::resource('/category', CategoryController::class);
//Brand
Route::resource('brand', BrandController::class);
//shippings
Route::resource('/shipping', ShippingController::class);
//products
Route::resource('/product', ProductController::class);

//settings

Route::get('settings', [AdminController::class, 'settings'])->name('settings');

//update settins
Route::post('settings/update', [AdminController::class, 'settingsUpdate'])->name('settings.update');

//profile
Route::get('/profile', [AdminController::class, 'profile'])->name('admin-profile');

//update profile.
Route::post('/admin/profile/update/{id}', [AdminController::class, 'profileUpdate'])->name('profile-update');