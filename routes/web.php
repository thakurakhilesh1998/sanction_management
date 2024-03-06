<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dir\DirController;
use App\Http\Controllers\District\DistrictController; 
use App\Http\Controllers\GP\GPController;
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

Route::middleware(['web','preventCache'])->group(function()
{
    Route::get('/',[Home::class,'index']);
    Route::get('/details/{data?}',[Home::class,'viewDetails']);
    Route::get('/showGpDetails/{gp}',[Home::class,'showGpDetails']);
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
    Route::get('/change-password',[UserController::class,'changePassword']);
    Route::put('/change-password',[UserController::class,'updatePassword']);

});
Route::prefix('dir')->middleware(['auth','web','dirCheck'])->group(function()
{
    Route::get('/dashboard',[DirController::class,'dashboard']);
    Route::get('/',[DirController::class,'index']);
    Route::post('/sanction-add',[DirController::class,'store']);
    Route::get('/view/{data?}',[DirController::class,'view']);
    Route::get('/edit/{id}',[DirController::class,'edit']);
    Route::put('/sanction-update/{id}',[DirController::class,'update']);
    Route::get('/view-progress',[DirController::class,'viewProgress']);
    Route::get('/blocks/{district}',[DirController::class,'getBlocks']);
    Route::get('/gps/{block}',[DirController::class,'getGps']);
    Route::get('/gpDetails/{gp}',[DirController::class,'showGpDetails']);
    Route::get('/change-password',[DirController::class,'changePassword']);
    Route::put('/change-password',[DirController::class,'updatePassword']);
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
    Route::get('all-details/{data?}',[DistrictController::class,'allDetails']);
    Route::get('/change-password',[DistrictController::class,'changePassword']);
    Route::put('/change-password',[DistrictController::class,'updatePassword']);
    Route::get('/add-sanction',[DistrictController::class,'addSanction']);
    Route::post('/add-sanction',[DistrictController::class,'saveSanction']);
    Route::get('/view-sanction',[DistrictController::class,'viewSanction']);
    Route::get('/editSanction/{id}',[DistrictController::class,'edit']);
    Route::put('/update-sanction/{id}',[DistrictController::class,'updateSanction']);
});
});

Route::prefix('gp')->middleware(['auth','web','gpCheck'])->group(function()
{
    Route::get('/dashboard',[GPController::class,'dashboard']);
    Route::get('/status',[GPController::class,'viewStatus']);
    Route::post('/uploadimg',[GPController::class,'uploadImg']);
    Route::get('/view-sanction',[GPController::class,'viewSanction']);
});

