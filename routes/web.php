<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dir\DirController;
use App\Http\Controllers\District\DistrictController; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',[Home::class,'index']);
Auth::routes();


Route::prefix('admin')->middleware(['auth','web','adminCheck'])->group(function()
{
    Route::get('/dashboard',[AdminController::class,'dashboard']);
    Route::get('/',[AdminController::class,'index']);
    Route::get('/user',[UserController::class,'index']);
    Route::post('/add-user',[UserController::class,'create']);
    Route::get('/user/view',[UserController::class,'view']);
    Route::get('/user-edit/{id}',[UserController::class,'edit']);
    Route::put('/user-edit/{id}',[UserController::class,'update']);


});
Route::prefix('dir')->middleware(['auth','web','dirCheck'])->group(function()
{
    Route::get('/dashboard',[DirController::class,'dashboard']);
    Route::get('/',[DirController::class,'index']);
    Route::post('/sanction-add',[DirController::class,'store']);
    Route::get('/view',[DirController::class,'view']);
    Route::get('/edit/{id}',[DirController::class,'edit']);
    Route::put('/sanction-update/{id}',[DirController::class,'update']);
    Route::get('/view-progress',[DirController::class,'viewProgress']);
    Route::get('/blocks/{district}',[DirController::class,'getBlocks']);
    Route::get('/gps/{block}',[DirController::class,'getGps']);
    Route::get('/gpDetails/{gp}',[DirController::class,'showGpDetails']);
});

Route::prefix('district')->middleware(['auth','web','distCheck'])->group(function()
{
    Route::get('/',[DistrictController::class,'index']);
    Route::get('update/',[DistrictController::class,'update']);
    Route::get('/add-progress/{id}',[DistrictController::class,'progress']);
    Route::post('add-progress/',[DistrictController::class,'addProgress']);
    Route::get('dashboard',[DistrictController::class,'dashboard']);
    Route::get('update-progress/{id}',[DistrictController::class,'updateProgress']);
    Route::post('update-freeze',[DistrictController::class,'Freeze']);
    Route::put('update-progress/{id}',[DistrictController::class,'change']);
    Route::get('view-progress/{id}',[DistrictController::class,'view']);
    Route::get('all-details',[DistrictController::class,'allDetails']);
});