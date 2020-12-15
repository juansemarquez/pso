<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;


use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin') && Auth::user()->hasRole('teacher')) {
            $teacher = Teacher::where('user_id', Auth::id())->first();
            return view('dashboard_teacher',['teacher' => $teacher, 'admin' => true]);
        }
        elseif (Auth::user()->hasRole('admin')) {
            $user = Auth::user();
            return view('dashboard_admin',['user' => $user]);
        }
        elseif (Auth::user()->hasRole('teacher')) {
            $teacher = Teacher::where('user_id', Auth::id())->first();
            return view('dashboard_teacher',['teacher' => $teacher, 'admin' => false]);
        }
        elseif (Auth::user()->hasRole('student')) {
            $student = Student::where('user_id', Auth::id())->first();
            return view('dashboard_student', ['student' => $student ]);
        }
        else {
            abort(403, 'Authentication error');
        }
    }
}
