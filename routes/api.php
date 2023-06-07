<?php

use App\Http\Controllers\API\Admin\CommentsController;
use App\Http\Controllers\API\Client\CommentsController as ClientCommentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\Client\RatingsController;
use App\Http\Controllers\API\OrdersController;
use App\Http\Controllers\API\ProductsController;
use App\Http\Controllers\API\RoleController;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Auth

Route::middleware('auth:sanctum')->get('/user' , [AuthController::class , 'user']);
Route::post('/register' , [AuthController::class , 'register']);
Route::post('/login' , [AuthController::class , 'login']);

Route::resource('category' , CategoryController::class);
Route::post('/category/store' , [CategoryController::class , 'storeSubCat']);

Route::/*middleware('auth:sanctum')
    ->*/resource('product' , ProductsController::class);
Route::get('products' , [ProductsController::class , 'search']);
Route::middleware('auth:sanctum')->post('product/{product}/comment' , [ClientCommentController::class , 'store' ]);
Route::middleware('auth:sanctum')->post('product/{product}/rating' , [RatingsController::class , 'store' ]);


Route::/*middleware('auth:sanctum')
        ->*/resource('role' , RoleController::class);

Route::post('role/{role}/permission' , [RoleController::class , 'addPermissionRole']);
Route::patch('role/{role}/permission' , [RoleController::class , 'updatePermissionRole']);
Route::delete('role/{role}/permission' , [RoleController::class , 'deletePermissionRole']);


Route::get('comments' , [CommentsController::class , 'index']);
Route::patch('comment/{comment}' , [CommentsController::class , 'update']);
Route::delete('comment/{comment}' , [CommentsController::class , 'destroy']);

Route::middleware('auth:sanctum')->post('checkout' , [OrdersController::class , 'store']);
Route::post('order/payment/callback' , [OrdersController::class , 'callback'])->name('order.callback');
