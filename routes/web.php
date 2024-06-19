<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;

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

Route::get('/', function () {
    return redirect()->route('login');
});


// Route::get('/admin', [AdminController::class, 'index']);

Route::middleware('auth')->group(function () {
   


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/admin', [AdminController::class, 'index']);

    Route::group(['middleware' => ['role:SystemAdmin']], function () {
      
        Route::get('/user-management',[UserManagementController::class,'index'])->name('user.index');
        Route::get('/user-list',[UserManagementController::class,'list'])->name('user.list');
        Route::get('/user-add',[UserManagementController::class,'add'])->name('user.add');
        Route::post('/user-submit',[UserManagementController::class,'submit'])->name('user.submit');
        Route::get('/user/edit/{id}',[UserManagementController::class,'edit'])->name('user.edit');
        Route::put('/user/update/{id}',[UserManagementController::class,'update'])->name('user.update');
    });
    
    // for admin
    Route::group(['middleware' => ['role:FinanceHead']], function () {
        //Route::get('/user-management',[UserManagementController::class,'index'])->name('user.index');
    });
    
    // Or with multiple roles
    Route::group(['middleware' => ['role:SystemAdmin|FinanceHead']], function () 
    {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
    
    });
    
});

require __DIR__.'/auth.php';
