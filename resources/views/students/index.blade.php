@extends('students.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
            <div class="pull-left">
                <h2>PSO - Students</h2>
            </div>
            @can('create',App\Models\Student::class)
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('students.create') }}">Add new student</a>
            </div>
            @endcan
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($students as $student)
        <tr>
            <td>{{ $student->first_name }}</td>
            <td>{{ $student->last_name }}</td>
            <td>{{ $student->user->email }}</td>
            <td>
            @can('delete',$student)
                <form action="{{ route('students.destroy',$student->id) }}" method="POST">
            @endcan
            @can('view',$student)
                    <a class="btn btn-info" href="{{ route('students.show',$student->id) }}">Show</a>
            @endcan
            @can('update',$student)
                    <a class="btn btn-primary" href="{{ route('students.edit',$student->id) }}">Edit</a>
            @endcan
            @can('delete',$student)
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endcan
            </td>
        </tr>
        @endforeach
    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
