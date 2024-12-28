<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\CastMovieController;
use App\Http\Controllers\API\GenreController;
use App\Http\Controllers\API\ProfileController as APIProfileController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\MoviesController;
use App\Http\Controllers\API\ReviewController;
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

Route::prefix('v1')->group(function () {
    Route::apiResource('cast', CastController::class);
    Route::apiResource('genre', GenreController::class);
    Route::apiResource('movie', MoviesController::class);
    Route::apiResource('role', RoleController::class)->middleware('auth:api', 'IsAdmin');
    Route::post('/upload', [MoviesController::class, 'upload']);
    //Movie
    Route::post('/movie', [MoviesController::class, 'store'])->middleware('auth:api', 'IsAdmin');
    Route::get('/movie', [MoviesController::class, 'index']);
    Route::put('/movie/{id}', [MoviesController::class, 'update'])->middleware('auth:api', 'IsAdmin');
    Route::delete('/movie/{id}', [MoviesController::class, 'destroy'])->middleware('auth:api', 'IsAdmin');

    //Genre
    Route::post('/genres', [GenreController::class, 'store'])->middleware('auth:api', 'IsAdmin');
    Route::get('/genres', [GenreController::class, 'index']);
    Route::get('/genres/{id}', [GenreController::class, 'show']);
    Route::put('/genres/{id}', [GenreController::class, 'update'])->middleware('auth:api', 'IsAdmin');
    Route::delete('/genres/{id}', [GenreController::class, 'destroy'])->middleware('auth:api', 'IsAdmin');

    //Cast
    Route::post('/cast', [CastController::class, 'store'])->middleware('auth:api', 'IsAdmin');
    Route::get('/cast', [CastController::class, 'index']);
    Route::get('/cast/{id}', [CastController::class, 'show']);
    Route::put('/cast/{id}', [CastController::class, 'update'])->middleware('auth:api', 'IsAdmin');
    Route::delete('/cast/{id}', [CastController::class, 'destroy'])->middleware('auth:api', 'IsAdmin');

    //Cast Movie
    Route::post('/cast-movie', [CastMovieController::class, 'store'])->middleware('auth:api', 'IsAdmin');
    Route::get('/cast-movie', [CastMovieController::class, 'index']);
    Route::get('/cast-movie/{id}', [CastMovieController::class, 'show']);
    Route::put('/cast-movie/{id}', [CastMovieController::class, 'update'])->middleware('auth:api', 'IsAdmin');
    Route::delete('/cast-movie/{id}', [CastMovieController::class, 'destroy'])->middleware('auth:api', 'IsAdmin');



    //Auth
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/me', [AuthController::class, 'currentUser'])->middleware('auth:api');
        Route::post('/verifikasi-akun', [AuthController::class, 'verifikasi'])->middleware('auth:api');
        Route::post('/generate-otp-code', [AuthController::class, 'generateOtp'])->middleware('auth:api');

        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
        Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
    })->middleware('api');



    //Profile

    Route::post('/profile', [APIProfileController::class, 'profile'])->middleware('auth:api', 'verifiedAccount');

    //Review
    Route::post('/review', [ReviewController::class, 'review'])->middleware('auth:api', 'verifiedAccount');
});
