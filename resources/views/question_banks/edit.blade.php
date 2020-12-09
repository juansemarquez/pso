@extends('groups.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
        <div class="pull-left">
            <h2>Edit question bank</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('question_banks.show', $questionBank) }}">Back</a>
        </div>
    </div>
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
<form action="{{ route('question_banks.update', $question_bank->id ) }}" method="POST">
    @csrf
    @method('PUT')

     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question Bank Name:</strong>
                <input type="text" name="name" class="form-control" value="{{$questionBank->name}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question Bank Description</strong>
                <input type="text" class="form-control" name="description" value="{{$questionBank->description}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Update</button>
        </div>

    </div>

</form>
                </div>
            </div>
        </div>
    </div>

@endsection
