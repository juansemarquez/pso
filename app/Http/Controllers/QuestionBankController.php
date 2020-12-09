<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\QuestionBank;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Teacher;
use Illuminate\Http\Request;

class QuestionBankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $banks = $teacher->questionBanks;
        return view('question_banks.index',compact('banks'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }
        return view('question_banks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }

        $request->validate([
            'name' => 'required',
        ]);
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $qb = new QuestionBank();
        $qb->name = $request['name'];
        if (isset($request['description']) && strlen($request['description'])>0 ) {
            $qb->description = $request['description'];
        }
        else {
            $qb->description = null; 
        }
        $qb->teacher()->associate($teacher);
        $qb->save();
        return redirect()->route('question_banks.show', $qb)
                        ->with('success','Question bank successfully created');
    }

    /**
     * Show the form for creating a new question for this QuestionBank.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function createQuestion($questionBankId)
    {
        if (! Auth::user()->hasRole('teacher') ) {
            abort(403, 'Only teachers can manage question banks.');
        }
        $questionBank = QuestionBank::findOrFail($questionBankId);
        if (!$this->isOwn($questionBank)) {
            abort(403, 'You\'re not allowed to modify this question bank.');
        }
        return view('question_banks.create_question', ['questionBank'=>$questionBank]);
    }
    /**
     * Store a newly created question and associates it to the question bank
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeQuestion(Request $request)
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }

        $request->validate([
            'question_bank_id' => 'required|numeric|exists:question_banks,id',
            'text' => 'required',
            'answerText' => 'required|array|min:2',
            'answerValue' => 'required|array|min:2',
            'answerText.*' => 'required',
            'answerValue.*' => 'required|numeric|min:0|max:100'
        ]);
        $qb = QuestionBank::findOrFail($request['question_bank_id']);
        
        // Is this teacher the owner of the Question Bank?
        if (!$this->isOwn($qb)) {
            abort(403, 'You\'re not allowed to modify this question bank.');
        }

        $q = new Question();
        $q->text = $request['text'];
        $q->question_bank()->associate($qb);
        $q->save();
        $i = 0;
        foreach ($request['answerText'] as $answer) {
            $a = new Answer();
            $a->text = $answer;
            $a->percentage_of_question = $request['answerValue'][$i];
            $a->question()->associate($q);
            $a->save();
            $i++;
        }
        return view('question_banks.show',['questionBank'=>$qb])
                         ->with('success','Question successfully created');
    }

    /**
     * Store a newly created answer and associates it to the question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeAnswer(Request $request)
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }

        $request->validate([
            'question_id' => 'required|numeric|exists:question',
            'text' => 'required',
            'percentage_of_question' => 'required|numeric|min:0|max:100'
        ]);
        
        $q = Question::findOrFail($request['question_id']);
        
        // Is this teacher the owner of the Question Bank?
        if (!$this->isOwn($q)) {
            abort(403, 'You\'re not allowed to modify this question bank.');
        }

        $a = new Answer();
        $a->text = $answer['text'];
        $a->percentage_of_question = $answer['percentage_of_question'];
        $a->question()->associate($q);
        $a->save();
        return redirect()->route('question_banks.show',
            ['questionBank'=>$a->question->question_bank])
            ->with('success','Question successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function show(QuestionBank $questionBank)
    {
        if (! Auth::user()->hasRole('teacher') || !$this->isOwn($questionBank)) {
            abort(403, 'Only teachers can manage question banks.');
        }
        $questionBank->load('questions.answers');
        return view('question_banks.show',compact('questionBank'));
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionBank $questionBank)
    {
        if (! Auth::user()->hasRole('teacher') || !$this->isOwn($questionBank) ) {
            abort(403, 'Only teachers can manage question banks.');
        }
        return view('question_banks.edit',compact('questionBank'));
    }

    /**
     * Update the question bank.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionBank $questionBank)
    {
        if (! Auth::user()->hasRole('teacher') || !$this->isOwn($questionBank)) {
            abort(403, 'Only teachers can manage question banks.');
        }

        $request->validate([
            'name' => 'required'
        ]);
        
        $d = isset($request['description']) && strlen($request['description'])>0 ? 
            $request['description']:null;
        $questionBank->update(['name'=>$request['name'], 'description'=>$d]);
        return view('question_banks.edit',compact('questionBank'));
        return redirect()->route('question_banks.edit', ['questionBank' => $questionBank])
                        ->with('success','Question bank name successfully updated');
    }

    /**
     * Show the form for editing the specified question.
     *
     * @param  \App\Models\QuestionBank  $question
     * @return \Illuminate\Http\Response
     */
    public function editQuestion($question)
    {        
        $question = Question::findOrFail($question);
        if (! Auth::user()->hasRole('teacher') || !$this->isOwn($question) ) {
            abort(403, 'Only teachers can manage question banks.');
        }
        return view('question_banks.edit_question',compact('question'));
    }

    /**
     * Update a question
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $question_id
     * @return \Illuminate\Http\Response
     */
    public function updateQuestion(Request $request, $question_id)
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }

        $request->validate([
            'question_id' => 'required|numeric|exists:questions,id',
            'text' => 'required',
            'answerText' => 'required|array|min:2',
            'answerValue' => 'required|array|min:2',
            'answerText.*' => 'required',
            'answerValue.*' => 'required|numeric|min:0|max:100'
        ]);
        
        $question = Question::findOrFail($question_id);
        
        // Is this teacher the owner of the Question Bank?
        if (!$this->isOwn($question)) {
            abort(403, 'You\'re not allowed to modify this question bank.');
        }

        $question->update(['text'=>$request['text']]);
        foreach ($question->answers as $answer) {
            $answer->question()->dissociate();
            $answer->delete();
        }
        $i = 0;
        foreach ($request['answerText'] as $ans) {
            $a = new Answer();
            $a->text = $ans;
            $a->percentage_of_question = $request['answerValue'][$i];
            $a->question()->associate($question);
            $a->save();
            $i++;
        }
                
        return redirect()->route('question_banks.show', $question->question_bank)
                        ->with('success','Question successfully updated');
    }

    /**
     * Update an answer
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $answer_id
     * @return \Illuminate\Http\Response
     */
    public function updateAnswer(Request $request, $answer_id)
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage question banks.');
        }

        $request->validate([
            'text' => 'required',
            'percentage_of_question' => 'required|numeric|min:0|max:100'
        ]);
        
        $answer = Answer::findOrFail($answer_id);
        
        // Is this teacher the owner of the Question Bank?
        if (! $this->isOwn($answer) ) {
            abort(403, 'You\'re not allowed to modify this question bank.');
        }

        $answer->update([
            'text'=>$request['text'],
            'percentage_of_question'=>$request['percentage_of_question']
        ]);
        
        return redirect()->route('question_banks.edit',
            ['questionBank'=>$answer->question->question_bank])
                         ->with('success','Answer successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuestionBank  $questionBank
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionBank $questionBank)
    {
        if (! Auth::user()->hasRole('teacher') || ! $this->isOwn($questionBank)) {
            abort(403, 'Only teachers can manage question banks.');
        }
        
        $questionBank->delete();

        return redirect()->route('question_banks.index')
                        ->with('success','Question bank deleted successfully.');
        
    }

    /**
     * Remove a question
     *
     * @param  int $question_id
     * @return \Illuminate\Http\Response
     */
    public function destroyQuestion($question_id)
    {
        $question = Question::findOrFail($question_id);
        if (! Auth::user()->hasRole('teacher') || ! $this->isOwn($question)) {
            abort(403, 'Only teachers can manage question banks.');
        }
        $bank = $question->question_bank; 
        $question->delete();

        return redirect()->route('question_banks.show', $bank)
                        ->with('success','Question deleted successfully.');
    }

    /**
     * Remove an answer
     *
     * @param  int $answer
     * @return \Illuminate\Http\Response
     */
    public function destroyAnswer($answer_id)
    {
        $answer = Answer::findOrFail($answer_id);
        if (! Auth::user()->hasRole('teacher') || ! $this->isOwn($answer)) {
            abort(403, 'Only teachers can manage question banks.');
        }
        $bank = $answer->question->question_bank; 
        $answer->delete();

        return redirect()->route('question_banks.show', ['questionBank'=>$bank])
                        ->with('success','Answer deleted successfully.');
    }

    /**
     * Returns whether the logged in teacher is the owner of the question bank
     *
     * @param  mixed  $qb
     * @return bool 
     */
    protected function isOwn($qb) {
        if (is_int($qb)) {
            $bank = QuestionBank::findOrFail($qb);
        }
        else {
          switch(get_class($qb)) {
            case 'App\Models\QuestionBank':
                $bank = $qb;
                break;
            case 'App\Models\Question':
                $bank = $qb->question_bank;
                break;
            case 'App\Models\Answer':
                $bank = $qb->question->question_bank;
                break;
            default:
                return false;
          }
        }
        
        if (! Auth::user()->hasRole('teacher')) { return false; }
       
        $teacher = Teacher::where('user_id', Auth::id())->first();
        return $bank->teacher->id === $teacher->id;
    }
}
