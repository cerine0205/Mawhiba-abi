<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/submit', [SubmissionController::class, 'store']);

Route::get('/submissions/export', [SubmissionController::class, 'exportCsv']);

// Route::get('/clear-submissions', function () {
//     \App\Models\Submission::truncate();
//     return 'All submissions deleted';
// });