<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage students.');
        }
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $teacher->load('groups.students');
        $students = [];
        foreach ($teacher->groups as $group) {
            foreach ($group->students as $student) {
                $students[] = $student;
            }
        }
        array_values(array_unique($students));
        return view('students.index',compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Student::class);
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $groups = \App\Models\Group::where('teacher_id',$teacher->id)->get(); 
        return view('students.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Student::class);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc',
            'password' => 'required|min:8',
            'password-repeat' => 'required|same:password',
            'group' => 'required|array|min:1'
        ]);
        
        $userName = strtolower(substr(trim($request['first_name']),0,1) .
                                 trim($request ['last_name']));
        $counter = 0;
        while ( true ) {
            if ( \App\Models\User::where('name',$userName)->count() == 0 ) {
                $counter = 0;
                break;
            }
            else {
                $counter++;
                $userName = $userName . $counter;
            }
        }

        $user = new \App\Models\User;
        $user->name = $userName;
        $user->password = bcrypt($request['password']);
        $user->email = trim($request['email']);
        $now = new \Datetime();
        $now->modify("+5 seconds");
        $user->email_verified_at = $now->format('Y-m-d H:i:s');
        $user->save();
        $user->roles()->attach(\App\Models\Role::where('description', 'student')->first());
        $user->save();
        $student = new Student;
        $student->first_name = $request['first_name'];
        $student->last_name = $request['last_name'];
        $student->user()->associate($user);
        $student->save();
        foreach ($request['group'] as $group_id) {
            $student->groups()->attach(\App\Models\Group::find($group_id));
        }
        $student->save();
        
        return redirect()->route('students.index')
                        ->with('success','Student successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $this->authorize('view', $student);
        $student->load('user');
        $students->load('groups');
        $student->user->load('roles');
        return view('students.show',compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        $this->authorize('update', $student);
        $student->load('user');
        return view('students.edit',['student'=>$student]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc'
        ]);
        
        $student->update(['first_name'=>$request['first_name'],
                          'last_name' => $request['last_name']
                         ]);

        $student->user->update(['email'=> $request['email']]);
        
        return redirect()->route('students.index')
                        ->with('success','Student\'s data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $this->authorize('delete', $student);
        $user = $student->user;
        $student->delete();
        $user->delete();
        return redirect()->route('students.index')
                        ->with('success','Student deleted successfully.');
    }
}
