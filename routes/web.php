<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
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




//backend section
Auth::routes(['register' => false]);

//home
Route::get('/home', [HomeController::class, 'index'])->name('home');

//admin login
Route::group(['prefix' => '/admin', 'middleware' => 'auth', 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    //Route::get('/', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');

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

//changepassword
Route::get('change-password', [AdminController::class, 'changePassword'])->name('change.password.form');
Route::post('change-password', [AdminController::class, 'changPasswordStore'])->name('change.password');







// front end section
//Route::get('/home',[FrontendController::class,'index']);






//user login
Route::get('user/login', [FrontendController::class, 'login'])->name('login.form');
Route::get('user/login', [FrontendController::class, 'loginSubmit'])->name('login.submit');
Route::get('user/login', [FrontendController::class, 'logout'])->name('login.logout');

Route::get('user/register', [FrontendController::class, 'register'])->name('register.form');
Route::get('user/register', [FrontendController::class, 'registerSubmit'])->name('register.submit');





//order
Route::post('cart/order', [OrderController::class, 'store'])->name('cart.order');
Route::get('order/pdf/{id}', [OrderController::class, 'pdf'])->name('order.pdf');
Route::get('/income', [OrderController::class, 'incomeChart'])->name('product.order.income');
Route::get('order/track', [OrderController::class, 'orderTrak'])->name('order.track');
Route::get('order/track/order', [OrderController::class, 'productTrackorder'])->name('product.track.order');