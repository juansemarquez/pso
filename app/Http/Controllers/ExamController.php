<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Exam;
use App\Models\ExamSheet;
use App\Models\QuestionBank;
use App\Models\Group;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage exams.');
        }
        $teacher = Teacher::where('user_id', Auth::id())->first();
        return view('exams.index', ['exams'=>$teacher->exams]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage exams.');
        }
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $qb = QuestionBank::where('teacher_id',$teacher->id)->get();
        $questionBanks = [];
        foreach ($qb as $q) {
            if ($q->check()[0]) {
                $questionBanks[] = $q;
            } 
        }
        if(count($questionBanks) === 0) {
            return redirect()->route('exams.index')->with('error',
                            'There are no question banks for creating exams');
        }

        return view('exams.create', ['question_banks' => $questionBanks]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'explanatory_text' => 'required',
            'question_bank_id' => 'required|numeric|exists:question_banks,id',
            'date_from' => 'required|date',
            'time_from' => 'required',
            'date_until' => 'required|date|after_or_equal:date_from',
            'time_until' => 'required',
            'time_available' => 'required|numeric',
            'number_of_questions' => 'required|numeric|min:1'
        ]);
        $from =  new \DateTime($request['date_from'] . ' ' . $request['time_from']);
        $until = new \DateTime($request['date_until'] . ' ' . $request['time_until']);
        if ($from >= $until) {
            return back()->withInput()->with('status', '"From" must be previous to "until"');
        }
        $qb = QuestionBank::findOrFail($request['question_bank_id']);
        $total_qb_questions = $qb->questions()->count();
        if ((int) $request['number_of_questions'] > $total_qb_questions ) {
            return back()->withInput()
                         ->with('status', "The question bank has only $total_qb_questions questions");
        }
        $e = new Exam();
        $e->name = $request['name'];
        $e->explanatory_text = $request['explanatory_text'];
        $e->from = $from;
        $e->until = $until;
        $e->time_available = $request['time_available'];
        $e->number_of_questions = $request['number_of_questions'];
        $e->questionBank()->associate($qb);
        $e->save();
        return redirect()->route('exams.show', $e)
                        ->with('success','Exam successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function show(Exam $exam)
    {
        if (! Auth::user()->hasRole('teacher') ) {
            abort(403, 'Only teachers can manage exams.');
        }        
        if (!$this->isOwn($exam)) {
            abort(403, 'You\'re not allowed to modify this exam.');
        }
        
        return view('exams.show', ['exam'=>$exam]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function edit(Exam $exam)
    {
        if (!$this->isOwn($exam)) {
            abort(403, 'You\'re not allowed to modify this exam.');
        }
        $qb = QuestionBank::where('teacher_id',1)->get();
        return view('exams.edit', ['exam'=>$exam, 'question_banks'=>$qb]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exam $exam)
    {
        if (!$this->isOwn($exam)) {
            abort(403, 'You\'re not allowed to modify this exam.');
        }
        $request->validate([
            'name' => 'required',
            'explanatory_text' => 'required',
            'question_bank_id' => 'required|numeric|exists:question_banks,id',
            'date_from' => 'required|date',
            'time_from' => 'required',
            'date_until' => 'required|date|after_or_equal:date_from',
            'time_until' => 'required',
            'time_available' => 'required|numeric',
            'number_of_questions' => 'required|numeric|min:1'
        ]);
        $from =  new \DateTime($request['date_from'] . ' ' . $request['time_from']);
        $until = new \DateTime($request['date_until'] . ' ' . $request['time_until']);
        if ($from >= $until) {
            return back()->withInput()->with('status', '"From" must be previous to "until"');
        }

        $exam->name = $request['name'];
        $exam->explanatory_text = $request['explanatory_text'];
        $exam->from = new \DateTime($request['date_from'] . " " . $request['time_from']);
        $exam->until = new \DateTime($request['date_until'] . " " . $request['time_until']);
        $exam->time_available = $request['time_available'];
        if ($request['question_bank_id'] != $exam->questionBank->id) {
            $qb = QuestionBank::findOrFail($request['question_bank_id']);
            $e->questionBank()->associate($qb);
            $total_qb_questions = $qb->questions()->count();
        }
        else {
            $total_qb_questions = $exam->questionBank->questions()->count();
            if ($request['number_of_questions'] > $total_qb_questions ) {
                return redirect()->route('exams.edit', $exam->id)->with('error', 
                   "The question bank has only $total_qb_questions questions");
            }
        $exam->number_of_questions = $request['number_of_questions'];
        $exam->save();
        return redirect()->route('exams.show', $exam)
                        ->with('success','Exam successfully updated');
        }   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exam  $exam
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exam $exam)
    {
        if (!$this->isOwn($exam)) {
            abort(403, 'You\'re not allowed to modify this exam.');
        }
        if ($exam->groups()->count() > 0) {
            return redirect()->route('exams.index')->with('error',
                'Exam can\'t be deleted, since it\'s assigned to groups');
        }
        $exam->delete();
        return redirect()->route('exams.index')
                 ->with('success','Exam successfully deleted');
    }

    public function addGroup(Request $request) {
        return $this->addOrRemoveGroup($request);
    }

    public function removeGroup(Request $request) {
        return $this->addOrRemoveGroup($request, false);
    }
    
    protected function addOrRemoveGroup(Request $request, $add = true) {
        $request->validate([
            'group_id' => 'required|int',
            'exam_id' => 'required|int'
        ]);
        if (! Auth::user()->hasRole('teacher') ){
            abort(403, 'Only teachers can manage exams');
        }
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $group = Group::findOrFail($request['group_id']);
        $exam = Exam::findOrFail($request['exam_id']);
        if (! $group->teacher->id === $teacher->id ) {
            abort(403, 'This group doesn\'t belong to you');
        }
        if (! $exam->questionBank->teacher->id === $teacher->id ) {
            abort(403, 'This exam doesn\'t belong to you');
        }
        if ($add) {
            $exam->groups()->attach($group);
            foreach ($group->students as $student) {
                $examSheet = new ExamSheet();
                $examSheet->started = null;
                $examSheet->finished = null;
                $examSheet->result = null;
                $examSheet->student()->associate($student);
                $examSheet->exam()->associate($exam);
                $examSheet->save();
            }
        }
        else {
            $exam->groups()->detach($group);
            $examSheets = [];
            foreach ($group->students as $student) {
                $examSheet = ExamSheet::where('student_id',$student->id)
                    ->where('exam_id',$exam->id)->first();
                if(!is_null($examSheet->started)) {
                    return redirect()->route('exams.show', $exam)
                                     ->with('error',
'Some students in this group have already sit for this exam, so you can\'t unassign this group'
                                     );
                }
                else {
                    $examSheets[] = $examSheet;
                }
            }
            foreach ($examSheets as $examSheet) {            
                if (is_null($examSheet->started)) {
                    $examSheet->exam()->dissociate();
                    $examSheet->delete();                    
                }
            }
        }
        $exam->save();
        return redirect()->route('exams.show', $exam)
                        ->with('success','Assignment successfully changed');
    } 

    protected function isOwn($exam) {
        if (is_int($exam)) {
            $exam = Exam::findOrFail($exam);
        }
        if (! Auth::user()->hasRole('teacher')) { return false; }
        $teacher = Teacher::where('user_id', Auth::id())->first();
        return $exam->questionBank->teacher->id === $teacher->id;
    }
}
