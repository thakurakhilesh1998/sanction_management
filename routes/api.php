<?php

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\XENController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProgressController;


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


Route::post('login',[AuthController::class,'login']);
Route::post('profile-data',[ProfileController::class,'getProfile']);
Route::post('dashboard',[ProfileController::class,'dashboard']);

Route::post('view-xen-sanction',[XENController::class,'viewSanction']);
Route::post('view-gp-xen',[XENController::class,'viewGpSan']);
//Save progress API
Route::post('add-progress-xen',[XENController::class,'addProgress']);
Route::post('save-progress-xen',[XENController::class,'saveProgress']);

// Update Progress API
Route::post('update-progress-xen',[XENController::class,'updateProgress']);
Route::post('updatedb-progress-xen',[XENController::class,'updateDBProgress']);

// Route::middleware('auth:sanctum')->get('get-data/{role}/{zone}', [ProgressController::class, 'getData']);
Route::get('get-data/{role}/{zone}', [ProgressController::class, 'getData']);
Route::get('get-gpsan/{gp}/{block}/{district}',[ProgressController::class,'getSanctionDetails']);

?>