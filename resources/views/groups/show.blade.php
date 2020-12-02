@extends('groups.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Groups</div>

                <div class="card-body">
            <div class="pull-left">
                <h2>Group information</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('groups.index') }}">Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Group Name:</strong> {{ $group->name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Group Description:</strong> {{ $group->description }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Students:</strong><br>
                <table class="table table-bordered table-striped">
                    <tr> <th>Name</th><th>Email</th><th>Remove</th></tr>
                    @forelse ($group->students as $s)
                    <tr>
                        <td>{{$s->last_name}}, {{$s->first_name}}</td>
                        <td>{{$s->user->email}}</td>
                        <td>
                            <form method="post" action="{{ route('removeStudent') }}">
                            @csrf
                            <input type="hidden" name="group_id" value="{{$group->id}}">
                            <input type="hidden" name="student_id" value="{{$s->id}}">
                            <input type="submit" value="Remove" class="btn btn-danger">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2">No students in this group</td></tr>
                    @endforelse
                </table>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Other Students:</strong><br>
                <table class="table table-bordered table-striped">
                    <tr> <th>Name</th><th>Email</th><th>Add to {{$group->name}}</th></tr>
                    @forelse ($otherStudents as $s)
                    <tr>
                        <td>{{$s->last_name}}, {{$s->first_name}}</td>
                        <td>{{$s->user->email}}</td>
                        <td>
                            <form method="post" action="{{ route('addStudent') }}">
                            @csrf
                            <input type="hidden" name="group_id" value="{{$group->id}}">
                            <input type="hidden" name="student_id" value="{{$s->id}}">
                            <input type="submit" value="Add to {{$group->name}}" class="btn btn-success">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="2">All your students are in this group</td></tr>
                    @endforelse
                </table>
            </div>
        </div>
        
    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
