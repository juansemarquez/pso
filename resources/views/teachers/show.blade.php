@extends('teachers.layout')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Teachers</div>

                <div class="card-body">
            <div class="pull-left">
                <h2>Teacher's information</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('teacher.index') }}">Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>First Name:</strong> {{ $teacher->first_name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Last Name:</strong> {{ $teacher->last_name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Roles:</strong> <br>
                @foreach ($teacher->user->roles as $role)
                   {{ $role->description }}<br>
                @endforeach
            </div>
        </div>
        
    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
