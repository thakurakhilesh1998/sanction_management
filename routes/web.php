<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Home;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dir\DirController;
use App\Http\Controllers\District\DistrictController; 
use App\Http\Controllers\GP\GPController;
use App\Http\Controllers\Xen\XENController;
use App\Http\Controllers\RD\DirRDController;
use App\Http\Controllers\RD\XENRDController;
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
    Auth::routes([
        'register' => false, // Disable registration route
        'reset'=>false,
    ]);


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
    Route::get('/manage-sanction',[AdminController::class,'manageSanction']);
    Route::post('sanction-delete/{id}',[AdminController::class,'deleteSanction']);
    // Manage Sanction
    Route::get('/sanction-edit/{id}',[AdminController::class,'editSanction'])->name('admin.edit');
    Route::put('/sanction-update/{id}',[AdminController::class,'updateSanction'])->name('admin.update');
    // Manage Progress
    Route::get('/manage-progress',[AdminController::class,'manageProgress']);

});
Route::prefix('dir')->middleware(['auth','web','dirCheck'])->group(function()
{
    Route::get('/dashboard',[DirController::class,'dashboard']);
    Route::get('/',[DirController::class,'index']);
    Route::post('/sanction-add',[DirController::class,'store']);
    Route::get('/view/{data?}',[DirController::class,'view']);
    Route::get('/edit/{id}',[DirController::class,'edit']);
    Route::put('/sanction-update/{id}',[DirController::class,'update']);
    // View Progress Route
    Route::get('/view-progress',[DirController::class,'viewProgress']);
    Route::get('/viewblockprogress/{district}',[DirController::class,'viewBlockProgress']);
    Route::get('/viewgpprogress/{block}/{district}',[DirController::class,'viewGPProgress']);
    Route::get('viewGpDetails/{gp}/{block}',[DirController::class,'viewGpDetails']);

    Route::get('/blocks/{district}',[DirController::class,'getBlocks']);
    Route::get('/viewGPs/{block}',[DirController::class,'getGPStatus']);
    Route::get('/viewGPData/{gp}/{block}',[DirController::class,'getGPPData']);
    Route::get('/gps/{block}',[DirController::class,'getGps']);
    Route::get('/gpDetails/{gp}/{block}/{district}',[DirController::class,'showGpDetails']);
    Route::get('/change-password',[DirController::class,'changePassword']);
    Route::put('/change-password',[DirController::class,'updatePassword']);
    
    
    // View Details uploaded by GPs
    Route::get('/view-pimage',[DirController::class,'viewPImage']);
    Route::get('/viewBlocksGp/{district}',[DirController::class,'viewBlockGp']);

    Route::get('/view-sanction',[DirController::class,'viewSanction']);
    
    
    Route::post('/upload-signed-sanction',[DirController::class,'uploadSignedSanction'])->name('uploadSanctionPdf');

    Route::get('/viewUCgp/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/UC/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not Found.');
        }
        return response()->file($privatePath);
    });
    // Dashboard
    Route::get('completed-work',[DirController::class,'viewCompletedWork']);
    Route::get('new-gp-sanction/{filter}',[DirController::class,'viewNewGPSanction']);
    Route::get('/viewGeneratedPdf/{filename}',function($filename)
    {
        $privatePath = storage_path('app/private/' . $filename);
        if(!file_exists($privatePath))
        {
            dd('not found');
            abort(404, 'File not found.');
        }
        return response()->file($privatePath);
    })->name('viewSanctionFileGenerated');



    // Rural Development Sanction
    Route::get('/rd-add',[DirRDController::class,'addSanction']);
    Route::post('/rd-saveSan',[DirRDController::class,'store']);
    Route::get('/view-rd',[DirRDController::class,'viewSanction']);
    Route::post('/upload-signed-sanction-rd',[DirRDController::class,'uploadSignedSanction'])->name('uploadSanctionRd');
    Route::get('/view-rd-progress/{district}/{block}/{work}',[DirRDController::class,'viewBlockProgress']);  
});

Route::prefix('district')->middleware(['auth','web','distCheck'])->group(function()
{
    Route::get('/',[DistrictController::class,'index']);
    Route::get('update/',[DistrictController::class,'update']);
    Route::get('dashboard',[DistrictController::class,'dashboard']);
    Route::get('all-details/{data?}',[DistrictController::class,'allDetails']);
    Route::get('/change-password',[DistrictController::class,'changePassword']);
    Route::put('/change-password',[DistrictController::class,'updatePassword']);
    Route::get('/add-sanction',[DistrictController::class,'addSanction']);
    Route::post('/add-sanction',[DistrictController::class,'saveSanction']);
    Route::get('/view-sanction',[DistrictController::class,'viewSanction']);
    Route::get('/editSanction/{id}',[DistrictController::class,'edit']);
    Route::put('/update-sanction/{id}',[DistrictController::class,'updateSanction']);
    Route::get('/view-sanction-dir',[DistrictController::class,'viewSanctionDir']);
    Route::post('/update-status',[DistrictController::class,'updateStatus']);

    Route::get('/viewGeneratedSanction/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not Found.');
        }
        return response()->file($privatePath);
    })->name('viewSanctionFile');

    Route::get('/view-signed-sanction-file/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not found');
        }
        return response()->file(file: $privatePath);
    })->name('viewSignedSanctionPdf');

    Route::post('/upload-signed-sanction',[DistrictController::class,'uploadSignedSanction'])->name('uploadSanction');
    
    // View Details of Panchayat Ghar Uploaded by GPs
    Route::get('/view-block-status',[DistrictController::class,'viewBlockStatus']);
    Route::get('/viewGPs/{block}',[DistrictController::class,'getGPStatus']);
    Route::get('/viewGPData/{gp}/{block}',[DistrictController::class,'getGPPData']);

});
});

// Gram Panchayat
Route::prefix('gp')->middleware(['auth','web','gpCheck'])->group(function()
{
    Route::get('/',[GPController::class,'dashboard']);
    Route::get('/dashboard',[GPController::class,'dashboard']);
    Route::get('/status',[GPController::class,'viewStatus']);
    Route::post('/uploadimg',[GPController::class,'uploadImg']);
    Route::get('/view-sanction',[GPController::class,'viewSanction']);
    Route::get('/view-gpsan/{gp}/{block}/{district}',[GPController::class,'viewGPSanction']);

    Route::get('/add-progress/{gp}/{block}/{district}',[GPController::class,'addProgress']);
    Route::post('/add-progress',[GPController::class,'newProgress']);
    Route::get('/update',[GPController::class,'update']);
    Route::get('update-progress/{gp}/{block}/{district}',[GPController::class,'updateProgress']);
    Route::post('/change-progress/{id}',[GPController::class,'changeProgress']);
    Route::post('update-freeze',[GPController::class,'Freeze']);
    Route::get('view-progress/{id}',[GPController::class,'view']);
    Route::post('/upload-signed-sanction',[GPController::class,'uploadUC']);

    // View UC from Gram Panchayat Login
    Route::get('/viewUCgp/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/UC/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not Found.');
        }
        return response()->file($privatePath);
    });

    Route::get('/view-sanction-file/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not found');
        }
        return response()->file($privatePath);
    });

    Route::put('/updatestatus/{id}',[GPController::class,'updateStatus']);
    
});

//XEN 

Route::prefix('xen')->middleware(['auth','web','xenCheck'])->group(function()
{
     // Routes for RD sanction
    Route::get('/view-rd-sanction',[XENRDController::class,'viewSanction']);
    Route::get('view-block-san/{district}/{block}/{work}/{agency}',[XENRDController::class,'viewBlockWiseSan']);
    Route::get('/add-progress-rd/{block}/{district}/{work}',[XENRDController::class,'addProgressRd']);
    Route::post('/add-progress-rd',[XENRDController::class,'saveProgressRd']);
    Route::get('update-progress-rd/{block}/{district}/{work}',[XENRDController::class,'updateFormRd']);
    Route::post('change-progress-rd/{id}',[XENRDController::class,'changeProgressRd']);
    Route::post('/upload-signed-sanction-rd',[XENRDController::class,'uploadUCRD']);


    Route::get('/viewUCRD/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/UC/'.$filename);
        if(!file_exists($privatePath))
        {
            abort(404,"File Not found");
        }
        return response()->file($privatePath);
    });



    Route::get('/',[XENController::class,'index']);
    Route::get('/dashboard',[XENController::class,'index']);
    Route::get('/view-sanction',[XENController::class,'viewSanciton']);
    Route::get('/view-gpsan/{gp}/{block}/{district}',[XENController::class,'viewGPSanction']);
    Route::get('/view-sanction-file/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not found');
        }
        return response()->file($privatePath);
    });

    Route::get('/viewUCgp/{filename}',function($filename)
    {
        $privatePath=storage_path('app/private/UC/' . $filename);
        if(!file_exists($privatePath))
        {
            abort(404,'File Not Found.');
        }
        return response()->file($privatePath);
    });
    Route::post('/upload-signed-sanction',[XENController::class,'uploadUC'])->name('uploadUC');
    Route::get('add-progress/{gp}/{block}/{district}',[XENController::class,'addProgress']);
    Route::post('add-progress',[XENController::class,'saveProgress']);
    Route::get('update-progress/{gp}/{block}/{district}',[XENController::class,'updateProgress']);
    Route::post('change-progress/{id}',[XENController::class,'changeProgress']);
    Route::get('view-progress',[XENController::class,'viewProgress']);
});

// Route to view the Sanction uploaded by PR
Route::get('/view-signed-sanction-file/{filename}',function($filename)
{
    $privatePath = storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404, 'File not found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','dirCheck'])->name('viewSignedSanctionFile');

// Route to view the Sanction uploaded by RD
Route::get('/view-signed-sanction-file-rd/{filename}',function($filename)
{
    $privatePath = storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404, 'File not found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','dirCheck'])->name('viewSignedSanctionFileRD');



Route::get('/view-rd-sanction-file/{filename}',function($filename)
{
    $privatePath=storage_path('app/private/'.$filename);
    if(!file_exists($privatePath))
    {
        abort(404, 'File not found.');
    }

});

Route::get('/viewSignedSanctionFileD/{filename}',function($filename)
{
    $privatePath=storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404, 'File not found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','distCheck'])->name('viewSignedSanctionFileD');


Route::get('/viewSigned/{filename}',function($filename)
{
    $privatePath=storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404, 'File not found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','gpCheck'])->name('viewSigned');

// View UC from Gram Panchayat Login
Route::get('/viewUCgp/{filename}',function($filename)
{
    $privatePath=storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404,'File Not Found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','gpCheck'])->name('viewUC');

// View UC from Directorate Login
Route::get('/viewUCdir/{filename}',function($filename)
{
    $privatePath=storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404,'File Not Found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','dirCheck'])->name('viewUCDir');

Route::get('/view-sanction-file/{filename}',function($filename)
{
    $privatePath = storage_path('app/private/' . $filename);
    if(!file_exists($privatePath))
    {
        abort(404, 'File not found.');
    }
    return response()->file($privatePath);
})->middleware(['auth','web','distCheck'])->name('viewDistSanctionFile');

Route::fallback(function () {
    return view('errors/404');
});