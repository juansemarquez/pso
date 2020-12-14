@extends('exams.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
        <div class="pull-left">
            <h2>Edit Exam</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('exams.index') }}">Back</a>
        </div>
    </div>

@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ $message }}</p>
    </div>
@endif
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
<form action="{{ route('exams.update', $exam->id ) }}" method="POST">
    @csrf
    @method('PUT')

     <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Exam Name:</strong>
                <input type="text" name="name" class="form-control" value="{{$exam->name}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Exam Descripton:</strong>
                <input type="text" class="form-control" name="explanatory_text"
                  value="{{$exam->explanatory_text}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question bank:</strong>
                <select name="question_bank_id">
                    @foreach ($question_banks as $qb)
                    <option value="{{$qb->id}}"
                    @if ($qb->id === $exam->questionBank->id)
                    selected>{{$qb->name}}</option>
                    @else
                    >{{$qb->name}}</option>
                    @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>From:</strong>
                <input type="date" class="form-control" name="date_from"
                value="{{substr($exam->from,0,10)}}">
                <input type="time" class="form-control" name="time_from"
                value="{{substr($exam->from,11)}}">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Until:</strong>
                <input type="date" class="form-control" name="date_until"
                value="{{substr($exam->until,0,10)}}">
                <input type="time" class="form-control" name="time_until"
                value="{{substr($exam->until,11)}}">
            </div>
        </div>

        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Time available (minutes):</strong>
                <input type="number" class="form-control" name="time_available"
                value="{{$exam->time_available}}">
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Number of questions:</strong>
                <input type="number" min="1" class="form-control"
                 name="number_of_questions" value="{{$exam->number_of_questions}}">
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

</div>
@endsection
