<?php

use App\Http\Controllers\Api\BookableAvailabilityController;
use App\Models\Bookable;
use App\Http\Controllers\Api\BookableController;
use App\Http\Controllers\Api\BookablePriceController;
use App\Http\Controllers\Api\BookableReviewController;
use App\Http\Controllers\Api\BookingByReviewController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ReviewController;
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

// Moved to web.php
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('bookables', function(Request $request) {
//     return Bookable::all();
// });

// Route::get('bookables/{id}', function(Request $request, $id){
//     return Bookable::findOrFail($id);
// });

// Route::get('bookables/{id}/{optional?}', function(Request $request, $id, $optional = null){
//     dd($id, $optional); 
// });
// All the invokable controlers:
Route::get('bookables/{id}/availability', (BookableAvailabilityController::class))->name('bookables.availability.show');

Route::get('bookables/{id}/reviews', (BookableReviewController::class))
->name('bookables.reviews.index');

Route::get('booking-by-review/{reviewkey}', BookingByReviewController::class)
->name('booking-by-review.show');

Route::get('bookables/{id}/price', (BookablePriceController::class))->name('bookables.price.show');

Route::post('checkout', CheckoutController::class)->name('checkout');

// Route::get('bookables', [BookableController::class, 'index']);
// Route::get('bookables/{id}', [BookableController::class, 'show']);
Route::apiResource('bookables', (BookableController::class))->only(['index', 'show']);
route::apiResource('reviews', ReviewController::class)->only(['show', 'store']);
