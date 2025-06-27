<?php

use App\Http\Controllers\CommitteeMemberController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\ImportantDateController;
use App\Http\Controllers\IntervenantController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\FormPricesController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\KeynoteController;
use App\Http\Controllers\GlobalSearchController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout']);
});

// Edition Routes
Route::prefix('editions')->group(function () {
    Route::get('/', [EditionController::class, 'index']);
    Route::get('/current', [EditionController::class, 'getCurrentEdition']);
    Route::get('/previous', [EditionController::class, 'getPreviousEditions']);
    Route::get('/{id}', [EditionController::class, 'show']);
    Route::post('/', [EditionController::class, 'store']);
    Route::post('/{id}', [EditionController::class, 'update']);
    Route::delete('/{id}', [EditionController::class, 'destroy']);
    Route::delete('/delete/{id}', [EditionController::class, 'deleteById']); // Additional delete endpoint
    Route::post('/{id}/images', [EditionController::class, 'addImages']);
    Route::delete('/{id}/images/{imageIndex}', [EditionController::class, 'removeImage']);
});

// Important Date Routes
Route::prefix('important-dates')->group(function () {
    Route::get('/', [ImportantDateController::class, 'index']);
    Route::get('/edition/{editionId}', [ImportantDateController::class, 'getDatesByEdition']);
    Route::get('/{id}', [ImportantDateController::class, 'show']);
    Route::post('/', [ImportantDateController::class, 'store']);
    Route::put('/{id}', [ImportantDateController::class, 'update']);
    Route::delete('/{id}', [ImportantDateController::class, 'destroy']);
    Route::get('/{editionId}/countdown', [ImportantDateController::class, 'getLatestCountdownDate']);
});

// Intervenant Routes
Route::prefix('intervenants')->group(function () {
    Route::get('/', [IntervenantController::class, 'index']);
    Route::get('/edition/{editionId}', [IntervenantController::class, 'getByEdition']);
    Route::get('/{id}', [IntervenantController::class, 'show']);
    Route::post('/', [IntervenantController::class, 'store']);
    Route::post('/{id}', [IntervenantController::class, 'update']);
    Route::delete('/{id}', [IntervenantController::class, 'destroy']);
    Route::post('/add-programs/{id}', [IntervenantController::class, 'addPrograms']);
});

// Participant Routes
Route::prefix('participants')->group(function () {
    Route::get('/', [ParticipantController::class, 'index']);
    Route::get('/{id}', [ParticipantController::class, 'show']);
    Route::post('/', [ParticipantController::class, 'store']);
    Route::post('/{id}', [ParticipantController::class, 'update']);
    Route::delete('/{id}', [ParticipantController::class, 'destroy']);
    Route::get('/edition/{editionId}', [ParticipantController::class, 'getByEdition']);
});

// Program Routes
Route::prefix('programs')->group(function () {
    Route::get('/', [ProgramController::class, 'index']);
    Route::get('/edition/{editionId}', [ProgramController::class, 'getProgramsByEdition']);
    Route::post('/date', [ProgramController::class, 'getProgramsByDate']);
    Route::get('/{id}', [ProgramController::class, 'show']);
    Route::get('/{programId}/intervenants', [ProgramController::class, 'getIntervenantsByProgramId']);
    Route::post('/', [ProgramController::class, 'store']);
    Route::put('/{id}', [ProgramController::class, 'update']);
    Route::delete('/{id}', [ProgramController::class, 'destroy']);
    Route::get('/edition/{editionId}/dates', [ProgramController::class, 'getProgramDates']);
});

// Committee Member Routes
Route::prefix('committee-members')->group(function () {
    Route::get('/', [CommitteeMemberController::class, 'index']);
    Route::get('/edition/{editionId}/{committee}', [CommitteeMemberController::class, 'getMembersByEditionAndCommittee']);
    Route::get('/{id}', [CommitteeMemberController::class, 'show']);
    Route::post('/', [CommitteeMemberController::class, 'store']);
    Route::post('/{id}', [CommitteeMemberController::class, 'update']);
    Route::delete('/{id}', [CommitteeMemberController::class, 'destroy']);
});

// Document Routes
Route::prefix('documents')->group(function () {
    Route::get('/download/{id}', [DocumentController::class, 'download']);
    Route::get('/', [DocumentController::class, 'index']);
    Route::get('/{id}', [DocumentController::class, 'show']);
    Route::post('/', [DocumentController::class, 'store']);
    Route::post('/{id}', [DocumentController::class, 'update']);
    Route::delete('/{id}', [DocumentController::class, 'destroy']);
});
// Add this to your existing routes
Route::prefix('partenaires')->group(function () {
    Route::get('/', [PartenaireController::class, 'index']);
    Route::get('/edition/{editionId}', [PartenaireController::class, 'getPartenairesByEdition']);
    Route::post('/', [PartenaireController::class, 'store']);
    Route::get('/{id}', [PartenaireController::class, 'show']);
    Route::post('/{id}', [PartenaireController::class, 'update']);
    Route::delete('/{id}', [PartenaireController::class, 'destroy']);
});
// Form Prices Routes
Route::prefix('form-prices')->group(function () {
    Route::get('/', [FormPricesController::class, 'index']);
    Route::get('/edition/{editionId}', [FormPricesController::class, 'getByEdition']);
    Route::get('/{id}', [FormPricesController::class, 'show']);
    Route::post('/', [FormPricesController::class, 'store']);
    Route::put('/{id}', [FormPricesController::class, 'update']);
    Route::delete('/{id}', [FormPricesController::class, 'destroy']);
});
// Sponsor Routes
Route::prefix('sponsors')->group(function () {
    Route::get('/', [SponsorController::class, 'index']);
    Route::get('/edition/{editionId}', [SponsorController::class, 'getSponsorsByEdition']);
    Route::get('/{id}', [SponsorController::class, 'show']);
    Route::post('/', [SponsorController::class, 'store']);
    Route::post('/{id}', [SponsorController::class, 'update']);
    Route::delete('/{id}', [SponsorController::class, 'destroy']);
});
// Keynote Routes
Route::prefix('keynotes')->group(function () {
    Route::get('/', [KeynoteController::class, 'index']);
    Route::get('/{id}', [KeynoteController::class, 'show']);
    Route::post('/', [KeynoteController::class, 'store']);
    Route::post('/{id}', [KeynoteController::class, 'update']);
    Route::delete('/{id}', [KeynoteController::class, 'destroy']);
    Route::get('/edition/{editionId}', [KeynoteController::class, 'getByEdition']);
});

// Global Search Route
Route::get('/global-search', [GlobalSearchController::class, 'search']);   