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

Route::resource('teachers','\App\Http\Controllers\TeacherController')->middleware('auth');
Route::resource('groups','\App\Http\Controllers\GroupController')->middleware('auth');

Route::resource('students','\App\Http\Controllers\StudentController')->middleware('auth');
Route::post('group/addstudent','\App\Http\Controllers\GroupController@addStudent')->middleware('auth')->name('addStudent');
Route::post('group/removestudent','\App\Http\Controllers\GroupController@removeStudent')->middleware('auth')->name('removeStudent');

Route::resource('question_banks','\App\Http\Controllers\QuestionBankController')->middleware('auth');
Route::get('question_banks/{id}/create/','\App\Http\Controllers\QuestionBankController@createQuestion')->middleware('auth')->name('create_question');
Route::post('questions','\App\Http\Controllers\QuestionBankController@storeQuestion')->middleware('auth')->name('store_question');
Route::get('questions/{id}/edit/','\App\Http\Controllers\QuestionBankController@editQuestion')->middleware('auth')->name('edit_question');
Route::put('questions/{id}','\App\Http\Controllers\QuestionBankController@updateQuestion')->middleware('auth')->name('update_question');
Route::delete('questions/{question}','\App\Http\Controllers\QuestionBankController@destroyQuestion')->middleware('auth')->name('delete_question');

Route::resource('exams','\App\Http\Controllers\ExamController')->middleware('auth');
Route::post('exams.add_group', '\App\Http\Controllers\ExamController@addGroup')
    ->middleware('auth')->name('assign_to_group');
Route::post('exams.remove_group', '\App\Http\Controllers\ExamController@removeGroup')
    ->middleware('auth')->name('unassign_group');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/', '\App\Http\Controllers\HomeController@index')->name('dashboard');

Route::get('active','\App\Http\Controllers\HomeController@activeExams')
    ->middleware('auth')->name('exam_list');
Route::get('finished','\App\Http\Controllers\HomeController@finishedExams')
    ->middleware('auth')->name('solved_exams');
Route::get('future','\App\Http\Controllers\HomeController@futureExams')
    ->middleware('auth')->name('future_exams');


Route::get('solve_exam/{id}','\App\Http\Controllers\HomeController@solve')
    ->middleware('auth')->name('solve_exam');
Route::get('show_exam/{id}','\App\Http\Controllers\HomeController@show')
    ->middleware('auth')->name('show_exam');
Route::post('submit_exam/{id}','\App\Http\Controllers\HomeController@submitExam')
    ->middleware('auth')->name('submit_exam');
