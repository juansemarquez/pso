<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Teacher;
use App\Models\Student;
use App\Models\User;
use App\Models\Exam;
use App\Models\ExamSheet;



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

    public function activeExams()
    {
        if (! Auth::user()->hasRole('student') ) {
            abort(403, 'Only students can see exams');
        }
        $student = Student::where('user_id', Auth::id())->first();
        $exams = $student->activeExams();
        return view('home_student.active',compact('exams'));
    }

    public function finishedExams()
    {
        if (! Auth::user()->hasRole('student') ) {
            abort(403, 'Only students can see exams');
        }
        $student = Student::where('user_id', Auth::id())->first();
        $exams = $student->finishedExams();
        return view('home_student.finished',compact('exams'));
    }

    public function futureExams()
    {
        if (! Auth::user()->hasRole('student') ) {
            abort(403, 'Only students can see exams');
        }
        $student = Student::where('user_id', Auth::id())->first();
        $exams = $student->futureExams();
        return view('home_student.future',compact('exams'));
    }

    public function solve($examSheet_id)
    {
        $examSheet = ExamSheet::findOrFail($examSheet_id);
        if (! Auth::user()->hasRole('student') ) {
            abort(403, 'Only students can see exams');
        }
        $student = Student::where('user_id', Auth::id())->first();
        if ($examSheet->student->id !== $student->id ) {
            abort(403, 'This exam is not assigned to the logged in student');
        }
        $deadline = $examSheet->startExam();

        return view('home_student.solve',
            ['examSheet'=>$examSheet, 'deadline'=>$deadline]);        

    }

    public function submitExam(Request $request, $examSheet_id)
    {
        $examSheet = ExamSheet::findOrFail($examSheet_id);
        if (! Auth::user()->hasRole('student') ) {
            abort(403, 'Only students can submit exams');
        }
        $student = Student::where('user_id', Auth::id())->first();
        if ($student->id !== $examSheet->student->id) {
            abort(403, 'Fatal error when submitting exam');
        }
        $request->validate([
            'time' => 'required'
        ]);
        
        $answers = isset($request['answer']) ? $request['answer'] : array();
        $result = $examSheet->finishExam($answers);
        if ($result === -1 ) {
            abort(403, 'Fatal error when submitting exam');
        }
        else {
        return redirect()->route('show_exam',$examSheet_id)
                        ->with('status',"Exam succesfully submitted");
        }
    }


    public function show($examSheet_id)
    {
        $examSheet = ExamSheet::findOrFail($examSheet_id);
        if (!(Auth::user()->hasRole('teacher') && 
            $examSheet->exam->questionBank->teacher->id === 
            Teacher::where('user_id', Auth::id())->first()->id )
            && !(Auth::user()->hasRole('student') && 
            $examSheet->student->id ===
            Student::where('user_id', Auth::id())->first()->id )
        ) {
            abort(403, 'User not authorized to see this exam');
        }
        $now = new \DateTime();
        if (! $examSheet->is_done() && $examSheet->exam->until > $now ) {
            abort(403, 'This exam is not finished yet');
        }
        $empty = is_null($examSheet->finished);
        if (! $empty && is_null($examSheet->result) ) {
            $examSheet->calculateResult();
        }
        $unanswered = $examSheet->unansweredQuestions();

        return view(
            'home_student.show', 
            [
                'examSheet'=>$examSheet,
                'empty'=>$empty, 
                'unanswered' => $unanswered
            ]
        );
    }
}
