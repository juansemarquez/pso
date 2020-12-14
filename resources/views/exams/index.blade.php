@extends('exams.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
            <div class="pull-left">
                <h2>PSO - Exams</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('exams.create') }}">Add new exam</a>
            </div>
        </div>
    </div>
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ $message }} <a href="{{route('question_banks.index')}}">Question banks</a></p>
    </div>
@endif
<table class="table table-bordered">
    <tr>
        <th>Exam Name</th>
        <th>Exam Description</th>
        <th>From</th>
        <th>Until</th>
        <th>Question Bank</th>
        <th>Actions</th>
        </tr>
        @forelse ($exams as $exam)
        <tr>
            <td>{{$exam->name}}</td>
            <td>{{$exam->explanatory_text}}</td>
            <td>{{$exam->from}}</td>
            <td>{{$exam->until}}</td>
            <td>
                {{$exam->number_of_questions}} questions from 
                {{$exam->questionBank->name}} in {{$exam->time_available}} min
            </td>
            <td>
                    <a class="btn btn-info" href="{{ route('exams.show',$exam->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('exams.edit',$exam->id) }}">Edit</a>
                <form action="{{ route('exams.destroy',$exam->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @empty
            <tr><td colspan="6" class="text-center">No exams yet!</td>
        @endforelse
    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
