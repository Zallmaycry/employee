<?php

use App\Http\Controllers\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test',[EmployeeController::class,'index'])->name('employee');
Route::post('/test',[EmployeeController::class,'store'])->name('employee.post');
Route::put('/test/{id}',[EmployeeController::class,'update'])->name('employee.update');
Route::delete('/test/{id}',[EmployeeController::class,'destroy'])->name('employee.delete');
Route::get('/employees', [EmployeeController::class, 'indexApi']);