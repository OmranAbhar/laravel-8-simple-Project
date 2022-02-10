<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UsersRegisterController;
use App\Http\Controllers\Product\chart\ChartProductPriceController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\ProductType\ProductTypeController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});
//Login
Route::resource('/',LoginController::class);
Route::get('/login',[LoginController::class,'index']);
Route::get('/SignOut',[LoginController::class,'signOut']);
Route::post('/login',[LoginController::class,'login'])->name('user.login');

//User Register
Route::get('/userRegister',[UsersRegisterController::class,'index'])->name('user.register.list');
Route::post('/userRegisterAdd',[UsersRegisterController::class,'userRegisterAdd'])->name('user.register.add');
Route::get('/getUserRegisterList',[UsersRegisterController::class,'getUserRegisterList'])->name('get.users.register.list');
Route::post('/getUserRegisterDetails',[UsersRegisterController::class,'getUserRegisterDetails'])->name('get.users.register.details');
Route::post('/updateUserRegisterDetails',[UsersRegisterController::class,'updateUserRegisterDetails'])->name('update.users.register.details');
Route::post('/deleteUserRegister',[UsersRegisterController::class,'deleteUserRegister'])->name('delete.user.register');

//Product
Route::get('/product',[ProductController::class,'index'])->name('product.list');
Route::post('/productAdd',[ProductController::class,'productAdd'])->name('product.add');
Route::get('/getProductList',[ProductController::class,'getProductList'])->name('get.products.list');
Route::post('/getProductDetails',[ProductController::class,'getProductDetails'])->name('get.products.details');
Route::post('/updateProductDetails',[ProductController::class,'updateProductDetails'])->name('update.products.details');
Route::post('/deleteProduct',[ProductController::class,'deleteProduct'])->name('delete.product');

//product Type
Route::get('/productType',[ProductTypeController::class,'index'])->name('product.type.list');
Route::post('/productTypeAdd',[ProductTypeController::class,'productTypeAdd'])->name('product.type.add');
Route::get('/getProductTypeList',[ProductTypeController::class,'getProductTypeList'])->name('get.products.type.list');
Route::post('/getProductTypeDetails',[ProductTypeController::class,'getProductTypeDetails'])->name('get.products.type.details');
Route::post('/updateProductTypeDetails',[ProductTypeController::class,'updateProductTypeDetails'])->name('update.products.type.details');
Route::post('/deleteProductType',[ProductTypeController::class,'deleteProductType'])->name('delete.product.type');

//chart
Route::post('/chartProductPrice',[ChartProductPriceController::class,'index'])->name('chart.product.price');

