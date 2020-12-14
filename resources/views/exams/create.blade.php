@extends('exams.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <div class="pull-left">
                        <h2>Add new exam</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('exams.index') }}">Back</a>
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

<form action="{{ route('exams.store') }}" method="POST">
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Exam Name:</strong>
                <input type="text" name="name" class="form-control"
                 placeholder="Eg: End of semester">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Exam Descripton:</strong>
                <input type="text" class="form-control" name="explanatory_text"
                  placeholder="Eg: 8th grade - Math class - ABC School">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question bank:</strong>
                <select name="question_bank_id">
                    @foreach ($question_banks as $qb)
                    <option value="{{$qb->id}}">
                        {{$qb->name}} ({{$qb->questions()->count()}} questions)
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>From:</strong>
                <input type="date" class="form-control" name="date_from">
                <input type="time" class="form-control" name="time_from">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Until:</strong>
                <input type="date" class="form-control" name="date_until">
                <input type="time" class="form-control" name="time_until">
            </div>
        </div>

        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Time available:</strong>
                <input type="number" class="form-control" name="time_available" value="10"> minutes.
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Number of questions:</strong>
                <input type="number" min="1" class="form-control"
                 name="number_of_questions" value="10">
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
