<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Models\Group;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Auth::user()->hasRole('teacher')) {
            abort(403, 'Only teachers can manage groups.');
        } 
        $teacher = Teacher::where('user_id', Auth::id())->first();
        return view('groups.index',['groups' => $teacher->groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create',Group::class);
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Group::class);
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $teacher = Teacher::where('user_id',Auth::id())->first();
        $group = new Group();
        $group->name = $request['name'];
        $group->description = $request['description'];
        $group->teacher()->associate($teacher);
        $group->save();
        return redirect()->route('groups.index')
                        ->with('success','Group successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $this->authorize('view', $group);
        $teacher = Teacher::where('user_id',Auth::id())->first();
        $group->load('students');
        $studentsInOtherGroups = $group->otherStudents();
        return view('groups.show',['group'=>$group, 'otherStudents' => $studentsInOtherGroups]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $this->authorize('update', $group);
        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('update', $group);
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $group->update(['name' => $request['name'],
                        'description' =>$request['description']
                      ]);
        return redirect()->route('groups.index')
                 ->with('success','Group data successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        $this->authorize('forceDelete', $group);
        $group->delete();
        return redirect()->route('groups.index')
                 ->with('success','Group successfully deleted');

    }

    public function addStudent(Request $request) {
        return $this->addOrRemoveStudent($request);
    }

    public function removeStudent(Request $request) {
        return $this->addOrRemoveStudent($request, false);
    }

    public function addOrRemoveStudent(Request $request, $add = true) {
        $request->validate([
            'group_id' => 'required|int',
            'student_id' => 'required|int'
        ]);
        $teacher = Teacher::where('user_id', Auth::id())->first();
        $group = Group::findOrFail($request['group_id']);
        if (! $group->teacher->id === $teacher->id ) {
            abort(403, 'This group doesn\'t belong to you');
        }
        $student = Student::findOrFail($request['student_id']);
        if ($add) {
            $group->students()->attach($student);
        }
        else {
            $group->students()->detach($student);
        }
        $group->save();
        return $this->show($group);
    } 
    
}
