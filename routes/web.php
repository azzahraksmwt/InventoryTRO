<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\LoginController;
//use App\Models\Inventory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/main', function () {
    return view('layouts.main');
});


//route login & logout
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login-proses', [LoginController::class, 'login_proses'])->name('login-proses');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

//route setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    Route::get('/profile', [UserController::class, 'indexProfile'])->name('profile.index');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update/{id}', [UserController::class, 'updateProfile'])->name('profile.update');

    // ROUTE UNTUK ADMIN
    Route::middleware(['auth', 'checkRole:Admin'])->group(function () {
        //ROUTE LECTURERS
        Route::get('/users/lecturers', [UserController::class, 'index_lecturers'])->name('users.index_lecturers');
        Route::get('/users/lecturers/create', [UserController::class, 'create_lecturers'])->name('users.create_lecturers');
        Route::post('/users/lecturers', [UserController::class, 'store_lecturers'])->name('users.store_lecturers');
        Route::put('/users/lecturers/{id}', [UserController::class, 'update_lecturers'])->name('users.update_lecturers');
        Route::get('/users/lecturers/{id}', [UserController::class, 'show_lecturers'])->name('users.show_lecturers');
        Route::get('/users/lecturers/{id}/edit_lecturers', [UserController::class, 'edit_lecturers'])->name('users.edit_lecturers');
        Route::delete('/users/lecturers/{id}', [UserController::class, 'destroy_lecturers'])->name('users.destroy_lecturers');
        //ROUTE STUDENTS
        Route::get('/users/students', [UserController::class, 'index_students'])->name('users.index_students');
        Route::get('/users/students/create', [UserController::class, 'create_students'])->name('users.create_students');
        Route::post('/users/students', [UserController::class, 'store_students'])->name('users.store_students');
        Route::put('/users/students/{id}', [UserController::class, 'update_students'])->name('users.update_students');
        Route::get('/users/students/{id}', [UserController::class, 'show_students'])->name('users.show_students');
        Route::get('/users/students/{id}/edit_students', [UserController::class, 'edit_students'])->name('users.edit_students');
        Route::delete('/users/students/{id}', [UserController::class, 'destroy_students'])->name('users.destroy_students');
        //ROUTE INVENTORYS
        Route::get('/admin/inventorys', [InventoryController::class, 'index_admin'])->name('inventorys.index_admin');
        Route::get('/admin/inventorys/add', [InventoryController::class, 'create_admin'])->name('inventorys.create_admin');
        Route::post('/admin/inventorys/store', [InventoryController::class, 'store_admin'])->name('inventorys.store_admin');
        Route::put('/admin/inventorys/{id}', [InventoryController::class, 'update_admin'])->name('inventorys.update_admin');
        Route::get('/admin/inventorys/{id}', [InventoryController::class, 'show_admin'])->name('inventorys.show_admin');
        Route::get('/admin/inventorys/{id}', [InventoryController::class, 'edit_admin'])->name('inventorys.edit_admin');
        Route::delete('/admin/inventorys/{id}', [InventoryController::class, 'destroy_admin'])->name('inventorys.destroy_admin');
        // ROUTE USAGE 
        Route::get('/usages', [UsageController::class, 'index'])->name('usages.index');
        Route::get('/usages/create', [UsageController::class, 'create'])->name('usages.create');
        Route::post('/usages/store', [UsageController::class, 'store'])->name('usages.store');
        Route::get('/usages/{usage}', [UsageController::class, 'show'])->name('usages.show');
        Route::put('/usages/{usage}', [UsageController::class, 'update'])->name('usages.update');
        Route::delete('/usages/truncate', [UsageController::class, 'truncate'])->name('usages.truncate');
        //Validasi Pemakaian
        Route::get('/usages_validation', [UsageController::class, 'index_validation'])->name('usages.index_validation');
        Route::get('/usages_validation/{usage}', [UsageController::class, 'show_validation'])->name('usages_validation.show');
        Route::get('/usages/{usage}/edit', [UsageController::class, 'edit'])->name('usages.edit');
        //validasi pengembalian
        Route::get('/usages_returnValidation/{usage}/edit', [UsageController::class, 'edit_returnValidation'])->name('usages.edit_returnValidation');
        Route::get('/usages_returnValidation', [UsageController::class, 'index_returnValidation'])->name('usages.index_returnValidation');
        Route::get('/usages_returnValidation/{usage}', [UsageController::class, 'show_returnValidation'])->name('usages_returnValidation.show');

        Route::get('/usages_returnGoods/{usage}/edit', [UsageController::class, 'edit_returnGoods'])->name('usages.edit_returnGoods');
        //Laporan
        Route::get('/report', [UsageController::class, 'index_report'])->name('reports.index');
        Route::get('/reports/cetak', [UsageController::class, 'cetak_laporan'])->name('reports.cetak');
    });

    // ROUTE UNTUK DOSEN
    Route::middleware(['auth', 'checkRole:Dosen'])->group(function () {
        //ROUTE INVENTORYS
        Route::get('/dosen/inventorys', [InventoryController::class, 'index'])->name('inventorys.index');
        Route::get('/dosen/inventorys/add', [InventoryController::class, 'create'])->name('inventorys.create');
        Route::post('/dosen/inventorys/store', [InventoryController::class, 'store'])->name('inventorys.store');
        Route::put('/dosen/inventorys/{id}', [InventoryController::class, 'update'])->name('inventorys.update');
        Route::get('/dosen/inventorys/{id}', [InventoryController::class, 'show'])->name('inventorys.show');
        Route::get('/dosen/inventorys/{id}', [InventoryController::class, 'edit'])->name('inventorys.edit');
        Route::delete('/dosen/inventorys/{id}', [InventoryController::class, 'destroy'])->name('inventorys.destroy');
        // ROUTE USAGE 
        Route::get('/usages_lecturer', [UsageController::class, 'index_lecturer'])->name('usages.index_lecturer');
        Route::get('/usages_lecturer/{usage}', [UsageController::class, 'show_lecturer'])->name('usages.show_lecturer');
        Route::put('/usages_lecturer/{usage}', [UsageController::class, 'update_lecturer'])->name('usages.update_lecturer');
        Route::delete('/usages_lecturer/truncate', [UsageController::class, 'truncate_lecturer'])->name('usages.truncate_lecturer');
        //Validasi Pemakaian
        Route::get('/usages_validation_lecturer', [UsageController::class, 'index_validation_lecturer'])->name('usages.index_validation_lecturer');
        Route::get('/usages_validation_lecturer/{usage}', [UsageController::class, 'show_validation_lecturer'])->name('usages_validation.show_lecturer');
        Route::get('/usages_lecturer/{usage}/edit', [UsageController::class, 'edit_lecturer'])->name('usages.edit_lecturer');
        //validasi pengembalian
        Route::get('/usages_returnValidation_lecturer/{usage}/edit', [UsageController::class, 'edit_returnValidation_lecturer'])->name('usages.edit_returnValidation_lecturer');
        Route::get('/usages_returnValidation_lecturer', [UsageController::class, 'index_returnValidation_lecturer'])->name('usages.index_returnValidation_lecturer');
        Route::get('/usages_returnValidation_lecturer/{usage}_lecturer', [UsageController::class, 'show_returnValidation_lecturer'])->name('usages_returnValidation.show_lecturer');

        Route::get('/report_lecturer', [UsageController::class, 'index_report_lecturer'])->name('reports.index_lecturer');
        Route::get('/reports_lecturer/cetak', [UsageController::class, 'cetak_laporan_lecturer'])->name('reports.cetak_lecturer');
    });



    Route::middleware(['auth', 'checkRole:Mahasiswa'])->group(function () {
        // ROUTE USAGE 
        Route::get('/usages_student', [UsageController::class, 'index_student'])->name('usages.index_student');
        Route::get('/usages_student/create', [UsageController::class, 'create_student'])->name('usages.create_student');
        Route::post('/usages_student/store', [UsageController::class, 'store_student'])->name('usages.store_student');
        Route::get('/usages_student/{usage}', [UsageController::class, 'show_student'])->name('usages.show_student');
        Route::put('/usages_student/{usage}', [UsageController::class, 'update_student'])->name('usages.update_student');
        Route::delete('/usages_student/{usage}', [UsageController::class, 'destroy_student'])->name('usages.destroy_student');

        Route::get('/usages_returnGoods_student/{usage}/edit', [UsageController::class, 'edit_returnGoods_student'])->name('usages.edit_returnGoods_student');
    });
});
