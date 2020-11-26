@extends('teachers.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <div class="pull-left">
                        <h2>Add new teacher</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('teachers.index') }}">Back</a>
                    </div>
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>¡Oops!</strong>There are errors on input data.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('teachers.store') }}" method="POST">
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>First Name:</strong>
                <input type="text" name="first_name" class="form-control" placeholder="First Name">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Last Name:</strong>
                <input type="text" class="form-control" name="last_name" placeholder="Last Name">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="is_admin">
                <strong>Admin permissions?</strong>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Confirm password:</strong>
                <input type="password" class="form-control" name="password-repeat" placeholder="Confirm password">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Send</button>
        </div>

    </div>

</form>
                </div>
            </div>
        </div>
    </div>


@endsection
