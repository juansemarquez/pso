@extends('question_banks.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Question Bank</div>

                <div class="card-body">
            <div class="pull-left">
                <h2>Question Bank</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('question_banks.index') }}">Back</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group py-3">
                <a href="{{route('create_question',$questionBank->id)}}" class="btn btn-success">Add new question</a>
                <form action="{{route('question_banks.destroy', $questionBank)}}" method="post" class="my-3">
                @method('delete')
                @csrf
                <input type="submit" class="btn btn-danger" value="Delete bank">
                </form>
            </div>
        </div>
        <hr>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question Bank Name:</strong> {{ $questionBank->name }}<br>
                <strong>Question Bank Description:</strong> {{ $questionBank->description }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question Bank Status:<br>
                @if ($questionBank->check()[0])
                <span class="bg-success">
                @else
                <span class="bg-danger">
                @endif
                {{$questionBank->check()[1]}}</span>
                </strong> 
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Questions:</strong><br>
                @forelse ($questionBank->questions as $question)
                    <div class="question">                    
                    <p>{{$question->text}}</p>
                      <ul>
                      @forelse ($question->answers as $answer)
                      <div class="answer">
                        @if ($answer->percentage_of_question == 100)
                          <li class="text-success">
                        @elseif ($answer->percentage_of_question == 0)
                          <li class="text-warning">
                        @else
                          <li class="text-muted">
                        @endif                        
                        {{$answer->text}} ({{$answer->percentage_of_question}}%)
                      </li>
                      </div> <!-- Answer -->
                      @empty
                        <li class="text-danger">¡This question has no answers!</li>
                      @endforelse
                      </ul>
                      <a href="{{route('edit_question', $question->id)}}" class="btn btn-primary">
                          Edit this question</a>
                      <form action="{{route('delete_question', $question->id)}}" 
                        method ="post">
                        @method('delete')
                        @csrf
                        <input type="submit" value="Delete question" class="btn btn-danger">
                      </form>
                        
                    
                      </div> <!-- Question -->
                      <hr>
                @empty
                    <p class="text-danger">This question bank has no questions</p>
                @endforelse
                        
            </div>
        </div>
        
    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
