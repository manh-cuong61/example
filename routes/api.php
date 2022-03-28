<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SliderController;

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
Route::put('/users/mutiple-user', [UserController::class, "updateMutipleUser"]);
Route::get('/users/get-permission', [UserController::class, "getPermissions"]);
Route::get('/users/push-shopping-cart', [UserController::class, "pushShoppingCart"]);
Route::get('/users/get-shopping-cart', [UserController::class, "getShoppingCart"]);
Route::get('/users/get-all', [UserController::class, "getAll"]);
Route::apiResource('/users', UserController::class);

Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::apiResource('/roles', RoleController::class);

Route::apiResource('/permissions', PermissionController::class);

Route::get('captcha', [CaptchaController::class, 'index']);

Route::apiResource('menus', MenusController::class)->parameters([
    'menus' => 'id'
]);

Route::apiResource('categories', CategoriesController::class)->parameters([
    'categories' => 'id'
]);

Route::apiResource('products', ProductController::class)->parameters([
    'products' => 'id'
]);

Route::apiResource('sliders', SliderController::class)->parameters([
    'products' => 'id'
]);

Route::apiResource('settings', SettingController::class)->parameters([
    'products' => 'id'
]);
