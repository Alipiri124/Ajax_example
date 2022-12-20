<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/',[StudentController::class,'index']);
Route::get('/student/all',[StudentController::class,'allData']);
Route::post('/student/store',[StudentController::class,'storeData']);
Route::get('/student/edit/{id}',[StudentController::class,'editData']);
Route::post('/student/update/{id}',[StudentController::class,'updateData']);
Route::get('/student/destroy/{id}',[StudentController::class,'deleteData']);