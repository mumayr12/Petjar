<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController; // Make sure this line exists and is correct
use App\Http\Controllers\HomeController;
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

Auth::routes(['register'=>false]);

//home
Route::get('/home',[HomeController::class,'index'])->name('home');

//admin login
Route::group(['prefix'=>'admin','middleware'=>'auth'],function()
{
   Route::get('/',[AdminController::class,'admin'])->name('admin');
});

//banner
// CHANGE THIS LINE:
Route::resource('banner', BannerController::class); 