@extends('exams.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Exams</div>

                <div class="card-body">
            <div class="pull-left">
                <h2>Exam information</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('exams.index') }}">Back</a>
            </div>
        </div>
    <div class="row">
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif
@if ($message = Session::get('error'))
    <div class="alert alert-danger">
        <p>{{ $message }}</p>
    </div>
@endif
        <div class="col-xs-12 col-sm-12 col-md-12">
            <p><strong>Exam Name:</strong> {{ $exam->name }}</p>
            <p><strong>Explanatory text:</strong> {{ $exam->explanatory_text }}</p>
            <p><strong>From:</strong> {{ $exam->from}}</p>
            <p><strong>Until:</strong> {{ $exam->until}}</p>
            <p><strong>Time available:</strong> {{ $exam->time_available}} minutes</p>
            <p><strong>Number of questions:</strong> {{ $exam->number_of_questions}}</p>
            <p><strong>Question Bank:</strong> 
                <a href="{{route('question_banks.show', $exam->questionBank->id)}}">
                    {{$exam->questionBank->name}}
                </a>
            </p>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <h3>Groups</h3>
            <table class="table table-bordered table-striped">
            <tr><th>Name</th><th>Description</th><th>Unassign exam {{$exam->name}}</th></tr>
            @forelse ($exam->groups as $group)
                <tr>
                    <td><a href="{{route('groups.show', $group->id)}}">
                        {{$group->name}} 
                    </a></td>
                    <td>{{$group->description}}</td>
                    <td>
                    <form method="post" action="{{ route('unassign_group') }}">
                    @csrf
                    <input type="hidden" name="group_id" value="{{$group->id}}">
                    <input type="hidden" name="exam_id" value="{{$exam->id}}">
                    <input type="submit" value="Remove" class="btn btn-danger">
                    </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3">This exam hasn't been assigned to any groups yet.</td></li>
            @endforelse                
            </ul>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Other Groups:</strong><br>
                <table class="table table-bordered table-striped">
                    <tr> <th>Name</th><th>Description</th><th>Assign exam {{$exam->name}}</th></tr>
                    @forelse ($exam->otherGroups() as $g)
                    <tr>
                        <td><a href="{{route('groups.show', $g->id)}}">
                            {{$g->name}} 
                        </a></td>
                        <td>{{$g->description}}</td>
                        <td>
                            <form method="post" action="{{ route('assign_to_group') }}">
                            @csrf
                            <input type="hidden" name="group_id" value="{{$g->id}}">
                            <input type="hidden" name="exam_id" value="{{$exam->id}}">
                            <input type="submit" value="Asign exam {{$exam->name}}" class="btn btn-success">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2">This exam is assigned to all your groups</td></tr>
                    @endforelse
                </table>
            </div>

        </div>
        
    </div>
            <div class="text-center">
                <a class="btn btn-primary my-2" href="{{ route('exams.edit',$exam->id) }}">Edit</a>
                <form action="{{ route('exams.destroy',$exam->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
