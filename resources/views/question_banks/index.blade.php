@extends('groups.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
            <div class="pull-left">
                <h2>PSO - Question Banks</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('question_banks.create') }}">
                    Add new question bank
                </a>
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>Question Bank Name</th>
            <th>Description</th>
            <th>Status</th>
            <th width="280px">View</th>
        </tr>
        @forelse ($banks as $qb)
        <tr>
            <td>{{ $qb->name }}</td>
            <td>{{ $qb->description }}</td>
            @if ($qb->check()[0] === true)
            <td class="bg-success text-center"><dfn title="{{$qb->check()[1]}}">OK</td>
            @else
            <td class="bg-danger text-center"><dfn title="{{$qb->check()[1]}}">Error</td>
            @endif
            <td>
                <a class="btn btn-info" href="{{ route('question_banks.show',$qb->id) }}">View</a>
            </td>
        </tr>
        @empty
            <tr><td colspan="3" class="text-center">No question banks yet!</td>
        @endforelse
    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
