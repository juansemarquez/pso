@extends('groups.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <div class="pull-left">
                        <h2>Add new group</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('groups.index') }}">Back</a>
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

<form action="{{ route('groups.store') }}" method="POST">
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Group Name:</strong>
                <input type="text" name="name" class="form-control" placeholder="Eg: 8th grade">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Group Descripton:</strong>
                <input type="text" class="form-control" name="description" placeholder="Eg: 8th grade - Math class - ABC School">
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
