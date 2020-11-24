<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', Teacher::class);
        $teachers = Teacher::with('user')->get();
        return view('teachers.index',compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Teacher::class);
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Teacher::class);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc',
            'password' => 'required|min:8',
            'password-repeat' => 'required|same:password'
        ]);
        $user = new \App\Models\User;
        $user->name = strtolower(substr(trim($request['first_name']),0,1) .
                                 trim($request ['last_name']));
        $user->password = bcrypt($request['password']);
        $user->email = trim($request['email']);
        $now = new \Datetime();
        $now->modify("+5 seconds");
        $user->email_verified_at = $now->format('Y-m-d H:i:s');
        $user->save();
        $user->roles()->attach(\App\Models\Role::where('description', 'teacher')->first());
        if (isset($request['is_admin'])) {
            $user->roles()->attach(\App\Models\Role::where('description', 'admin')->first());
        }
        $user->save();
        $teacher = new Teacher;
        $teacher->first_name = $request['first_name'];
        $teacher->last_name = $request['last_name'];
        $teacher->user()->associate($user);
        $teacher->save();
        
        return redirect()->route('teacher.index')
                        ->with('success','Teacher successfully created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function show(Teacher $teacher)
    {
        //$teacher = Docentes::find($teacher->id);
        $this->authorize('view', $teacher);
        $teacher->load('user');
        $teacher->user->load('roles');
        return view('teachers.show',compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function edit(Teacher $teacher)
    {
        //$teacher = Teacher::find($id);
        $this->authorize('update', $teacher);
        $teacher->load('user');
        return view('teachers.edit',['teacher'=>$teacher]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $this->authorize('update', $teacher);
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email:rfc',
        ]);
        $teacher->update(['first_name'=>$request['first_name'],
                           'last_name' => $request['last_name']
                          ]);
        $user = $teacher->user;
        $user->update(['email' => $request['email']]);
        if (isset($request['is_admin']) && !$user->hasRole('admin')) {
            $user->roles()->attach(\App\Models\Role::where('description', 'admin')->first());
        }
        if (!isset($request['is_admin']) && $user->hasRole('admin')) {
            $user->roles()->detach(\App\Models\Role::where('description', 'admin')->first());
        }

        return redirect()->route('teacher.index')
                 ->with('success','Teacher\'s data successfully updated');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        $this->authorize('delete', $teacher);
        $user = $teacher->user;
        $teacher->delete();
        $user->delete();
        return redirect()->route('teacher.index')
                        ->with('success','Teacher deleted successfully.');
    }
}
