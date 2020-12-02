@extends('students.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Students</div>

                <div class="card-body">
            <div class="pull-left">
                <h2>Student's information</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('students.index') }}">Back</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>First Name:</strong> {{ $student->first_name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Last Name:</strong> {{ $student->last_name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong> {{ $student->user->email}}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <strong>Groups</strong>
            <ul>
            @forelse ($student->groups as $group)
                @can('update',$group)
                <li><a href="{{route('groups.show',$group->id)}}">{{$group->name}}
                    ({{$group->description}})</a></li>
                @else
                <li>{{$group->name}} ({{$group->description}})</a></li>
                @endcan
            @empty
                 <li>This student is not included in any group.</li>
            @endforelse
            </ul>
        </div>

        
    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
