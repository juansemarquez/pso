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

Route::resource('question_banks','\App\Http\Controllers\QuestionBankController')->middleware('auth');
Route::post('questions','\App\Http\Controllers\QuestionBankController@storeQuestion')->middleware('auth');
Route::post('answers','\App\Http\Controllers\QuestionBankController@storeAnswer')->middleware('auth');
Route::put('questions','\App\Http\Controllers\QuestionBankController@updateQuestion')->middleware('auth');
Route::put('answers','\App\Http\Controllers\QuestionBankController@updateAnswer')->middleware('auth');
Route::delete('questions','\App\Http\Controllers\QuestionBankController@destroyQuestion')->middleware('auth');
Route::delete('answers','\App\Http\Controllers\QuestionBankController@destroyAnswer')->middleware('auth');


Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
