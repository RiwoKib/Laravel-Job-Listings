<?php

use App\Models\Listing;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

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


//get all listings
Route::get('/', [ListingController::class, 'index']);


//show create Listing form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

//store Listing
Route::post('/listings', [ListingController::class, 'store'])->middleware('auth');


//show manage Listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

//show edit Listing
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');


//update Listing
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');


//delete Listing
Route::delete('/listings/{listing}', [ListingController::class, 'delete'])->middleware('auth');


//show Listing by id
Route::get('/listings/{listing}', [ListingController::class, 'show']);


//show register form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

//create new User
Route::post('/users', [UserController::class, 'store']);


//show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

//authencticate User login
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

//log User out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');



