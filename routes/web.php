<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\LCRequestController;
use App\Http\Controllers\ResetPaswordController;
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
   
    Route::post('/password/reset', [PasswordResetController::class, 'reset'])->name('login.password.reset');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/user_password.reset',[ResetPaswordController::class,'reset'])->name('user_password.reset');
      
    Route::get('/admin', [AdminController::class, 'index']);

    Route::group(['middleware' => ['role:SystemAdmin']], function () {

        Route::get('/user-management',[UserManagementController::class,'index'])->name('user.index');
        Route::get('/user-list',[UserManagementController::class,'list'])->name('user.list');
        Route::get('/user-add',[UserManagementController::class,'add'])->name('user.add');
        Route::post('/user-submit',[UserManagementController::class,'submit'])->name('user.submit');
        Route::get('/user/edit/{id}',[UserManagementController::class,'edit'])->name('user.edit');
        Route::put('/user/update/{id}',[UserManagementController::class,'update'])->name('user.update');

        Route::prefix('supplier')->name('supplier.')->group(function () {
            Route::get('/management',[SupplierController::class,'index'])->name('index');
            Route::get('/user-list',[SupplierController::class,'list'])->name('list');
            Route::get('/add',[SupplierController::class,'add'])->name('add');
            Route::post('/submit',[SupplierController::class,'submit'])->name('submit');
            Route::get('/edit/{id}',[SupplierController::class,'edit'])->name('edit');
            Route::put('/update/{id}',[SupplierController::class,'update'])->name('update');
        });
        
    });

    // for admin
    Route::group(['middleware' => ['role:CommercialOfficer|SystemAdmin']], function () {
        Route::get('/add',[LCRequestController::class,'add'])->name('lc_request.add');
        Route::post('/submit',[LCRequestController::class,'submit'])->name('lc_request.submit');
    });
    
    // Or with multiple roles
    Route::group(['middleware' => ['role:SystemAdmin|FinanceHead|CommercialHead|TreasuryOfficer|CommercialOfficer',]], function () 
    {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::prefix('lc_request')->name('lc_request.')->group(function () {
            Route::get('/',[LCRequestController::class,'index'])->name('index');
            Route::get('/list',[LCRequestController::class,'list'])->name('list');
            Route::get('/edit/{id}',[LCRequestController::class,'edit'])->name('edit');
            Route::put('/update/{id}',[LCRequestController::class,'update'])->name('update');
            Route::post('/reject/reason',[LCRequestController::class,'rejectReason'])->name('reject-reason');
            Route::post('/set-priority',[LCRequestController::class,'setPriority'])->name('set-priority');
        });
    
    });
    
});

require __DIR__.'/auth.php';
