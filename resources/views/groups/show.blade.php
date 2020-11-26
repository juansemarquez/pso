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

        
    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
