<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
                <h1>Your finished exams</h1>
                <div class="container col-12 col-md-8 offset-md-2 mx-auto">
                  <table class="table table-striped table-bordered">
                    <tr>
                      <th>Name</th><th>Question Bank</th><th>Finished</th><th>See results</th>
                    </tr>
                    <tr>
                      @forelse ($exams as $exam)
                      <td>{{$exam->exam->name}}</td>
                      <td>{{$exam->exam->questionBank}}</td>
                      <td>{{$exam->finished}}</td>
                      <td>
                        <a href="{{route('show_exam',$exam->id)}}" class="btn btn-primary">
                            See results
                        </a>
                      </td>
                    </tr>
                      @empty
                    <tr>
                      <td colspan="4">There are no finished exams yet</td>
                    </tr>
                      @endforelse
                  </table>
                  <p class="text-center">
                    <a href="{{route('dashboard')}}">Back</a>
                  </p>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
