@extends('groups.layout')
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
            <div class="pull-left">
                <h2>PSO - Groups</h2>
            </div>
            @can('create',App\Models\Group::class)
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('groups.create') }}">Add new group</a>
            </div>
            @endcan
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>Group Name</th>
            <th>Group Description</th>
            <th width="280px">Action</th>
        </tr>
        @forelse ($groups as $group)
        <tr>
            <td>{{ $group->name }}</td>
            <td>{{ $group->description }}</td>
            <td>
            @can('delete',$group)
                <form action="{{ route('groups.destroy',$group->id) }}" method="POST">
            @endcan
            @can('view',$group)
                    <a class="btn btn-info" href="{{ route('groups.show',$group->id) }}">Show</a>
            @endcan
            @can('update',$group)
                    <a class="btn btn-primary" href="{{ route('groups.edit',$group->id) }}">Edit</a>
            @endcan
            @can('delete',$group)
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            @endcan
            </td>
        </tr>
        @empty
            <tr><td colspan="3" class="text-center">No groups yet!</td>
        @endforelse
    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
