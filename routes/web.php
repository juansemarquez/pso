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
Route::resource('teachers','\App\Http\Controllers\TeacherController')->middleware('auth');
Route::resource('groups','\App\Http\Controllers\GroupController')->middleware('auth');
Route::resource('students','\App\Http\Controllers\StudentController')->middleware('auth');
Route::post('/group/addstudent','\App\Http\Controllers\GroupController@addStudent')->middleware('auth')->name('addStudent');
Route::post('/group/removestudent','\App\Http\Controllers\GroupController@removeStudent')->middleware('auth')->name('removeStudent');
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
