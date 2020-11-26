@extends('teachers.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
            <div class="pull-left">
                <h2>PSO - Teachers</h2>
            </div>
            @can('create',App\Models\Teacher::class)
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('teachers.create') }}">Add new teacher</a>
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
        @foreach ($teachers as $teacher)
        <tr>
            <td>{{ $teacher->first_name }}</td>
            <td>{{ $teacher->last_name }}</td>
            <td>{{ $teacher->user->email }}</td>
            <td>
            @can('delete',$teacher)
                <form action="{{ route('teachers.destroy',$teacher->id) }}" method="POST">
            @endcan
            @can('view',$teacher)
                    <a class="btn btn-info" href="{{ route('teachers.show',$teacher->id) }}">Show</a>
            @endcan
            @can('update',$teacher)
                    <a class="btn btn-primary" href="{{ route('teachers.edit',$teacher->id) }}">Edit</a>
            @endcan
            @can('delete',$teacher)
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
