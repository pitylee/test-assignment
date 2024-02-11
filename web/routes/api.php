<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::get('me', [AuthController::class, 'me']);

    // Candidate
    Route::get('candidates', [CandidateController::class, 'candidates']);
    Route::get('candidates/{id}', [CandidateController::class, 'candidate']);
    Route::post('candidates/hire', [CandidateController::class, 'hire']);

    // Contact
    Route::post('contact', [ContactController::class, 'contact']);
});