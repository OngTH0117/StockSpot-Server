<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BlogPostController;
use App\Http\Controllers\API\StockController;
use App\Http\Controllers\API\WatchlistController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->put('/updateProfile', [UserController::class, 'updateProfile']);
Route::delete('/removeFromWatchlist/{watchlistId}', 'WatchlistController@removeFromWatchlist')->middleware('auth:sanctum');
Route::delete('/deleteBlogPost/{id}', [BlogPostController::class, 'deleteBlogPost'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->post('/addToWatchlist', [WatchlistController::class, 'addToWatchlist']);
Route::get('/userWatchlist/{userId}', [WatchlistController::class, 'getUserWatchlist'])->middleware('auth:sanctum');;
Route::get('/stockScreener',[StockController::class, 'stocks'] );
Route::get('/stock/{identifier}', [StockController::class, 'getStockByIdentifier']);
Route::post('/blogPost', [BlogPostController::class, 'storeBlogPost'])->middleware('auth:sanctum');
Route::get('blogPostImages/{filename}', 'BlogPostController@serveImage');
Route::get('/blogDetail/{id}', [BlogPostController::class, 'getBlogPost']);
Route::get('/dashBlog', [BlogPostController::class, 'dashBlog']);
Route::get('/check-session', [UserController::class, 'checkSession']);
Route::post('logout', [UserController::class, 'logout']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/message', [MessageController::class, 'getMessage']);
Route::get('/stocks', [StockController::class, 'stocks']);

