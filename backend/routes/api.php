<?php

use App\Http\Controllers\CommitteeMemberController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\ImportantDateController;
use App\Http\Controllers\IntervenantController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProgramController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Edition Routes
Route::prefix('editions')->group(function () {
    Route::get('/', [EditionController::class, 'index']);
    Route::get('/{id}', [EditionController::class, 'show']);
    Route::post('/', [EditionController::class, 'store']);
    Route::put('/{id}', [EditionController::class, 'update']);
    Route::delete('/{id}', [EditionController::class, 'destroy']);
    Route::post('/{id}/images', [EditionController::class, 'addImages']);
    Route::delete('/{id}/images/{imageIndex}', [EditionController::class, 'removeImage']);
});

// Important Date Routes
Route::prefix('important-dates')->group(function () {
    Route::get('/', [ImportantDateController::class, 'index']);
    Route::get('/{id}', [ImportantDateController::class, 'show']);
    Route::post('/', [ImportantDateController::class, 'store']);
    Route::put('/{id}', [ImportantDateController::class, 'update']);
    Route::delete('/{id}', [ImportantDateController::class, 'destroy']);
});

// Intervenant Routes
Route::prefix('intervenants')->group(function () {
    Route::get('/', [IntervenantController::class, 'index']);
    Route::get('/{id}', [IntervenantController::class, 'show']);
    Route::post('/', [IntervenantController::class, 'store']);
    Route::put('/{id}', [IntervenantController::class, 'update']);
    Route::delete('/{id}', [IntervenantController::class, 'destroy']);
});

// Participant Routes
Route::prefix('participants')->group(function () {
    Route::get('/', [ParticipantController::class, 'index']);
    Route::get('/{id}', [ParticipantController::class, 'show']);
    Route::post('/', [ParticipantController::class, 'store']);
    Route::put('/{id}', [ParticipantController::class, 'update']);
    Route::delete('/{id}', [ParticipantController::class, 'destroy']);
});

// Program Routes
Route::prefix('programs')->group(function () {
    Route::get('/', [ProgramController::class, 'index']);
    Route::get('/{id}', [ProgramController::class, 'show']);
    Route::post('/', [ProgramController::class, 'store']);
    Route::put('/{id}', [ProgramController::class, 'update']);
    Route::delete('/{id}', [ProgramController::class, 'destroy']);
});

// Committee Member Routes
Route::prefix('committee-members')->group(function () {
    Route::get('/', [CommitteeMemberController::class, 'index']);
    Route::get('/{id}', [CommitteeMemberController::class, 'show']);
    Route::post('/', [CommitteeMemberController::class, 'store']);
    Route::put('/{id}', [CommitteeMemberController::class, 'update']);
    Route::delete('/{id}', [CommitteeMemberController::class, 'destroy']);
});

// Document Routes
Route::prefix('documents')->group(function () {
    Route::get('/', [DocumentController::class, 'index']);
    Route::get('/{id}', [DocumentController::class, 'show']);
    Route::post('/', [DocumentController::class, 'store']);
    Route::put('/{id}', [DocumentController::class, 'update']);
    Route::delete('/{id}', [DocumentController::class, 'destroy']);
    Route::get('/{id}/download', [DocumentController::class, 'download']);
});

