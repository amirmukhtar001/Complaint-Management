<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\UserController::class, 'index'])->name('home');

Route::get('/admin', [App\Http\Controllers\HomeController::class, 'adminLogin'])->name('adminLogin');


Route::group(['middleware' => ['auth', 'admin']], function () {
    Route::get('/admin/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/admin/complaints', [App\Http\Controllers\AdminController::class, 'adminComplaints'])->name('adminComplaints');
    Route::get('/admin/complaints/dataTables', [App\Http\Controllers\AdminController::class, 'comlaintsDataTable'])->name('comlaintsDataTable');
    Route::post('admin/ajax/status/update/{id}', [App\Http\Controllers\AdminController::class, 'updateStatus'])->name('updateStatus');
    Route::post('admin/bulk/unassign/', [App\Http\Controllers\AdminController::class, 'bulkunassign'])->name('bulk.unassign');
    Route::post('admin/bulk/active/', [App\Http\Controllers\AdminController::class, 'bulkactive'])->name('bulk.active');
    Route::post('admin/bulk/resolved/', [App\Http\Controllers\AdminController::class, 'bulkresolved'])->name('bulk.resolved');
});