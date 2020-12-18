<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
                <h1>Exam results</h1>
                <h2>Exam: {{$examSheet->exam->name}}</h2>
                <p>Student: {{$examSheet->student->first_name}}
                {{$examSheet->student->last_name}}
                ({{$examSheet->student->user->email}})
                </p>
                @if ($empty)
                <p>The student missed this exam.</p>
                @else
                <p>Finished: {{$examSheet->finished}}</p>
                <p><strong>Result: {{$examSheet->result}}%</strong></p>

                <div class="container col-12 col-md-8 offset-md-2 mx-auto">
                  <h3>Your answers:</h3>
                  @foreach ($examSheet->answers as $answer) 
                  <div>
                  <p><strong>Question:</strong> {{$answer->question->text}}</p>
                  <p><strong>Answer:</strong> {{$answer->text}}</p>
                  <p><strong>Result:</strong>
                  @if ($answer->percentage_of_question === 100)
                    <span class="text-success">Correct (100%)</span>
                  @elseif ($answer->percentage_of_question === 0)
                    <span class="text-danger">Incorrect (0%)</span>
                  @else
                    <span class="text-warning bd-dark">Partially correct 
                                ({{$answer->percentage_of_question}}%)</span>
                  @endif           
                  </p>  
                  </div>
                  <hr>
                  @endforeach                  
                  <h3>Unanswered questions:</h3>
                  <ul>
                  @forelse ($unanswered as $u)
                    <li>{{$u->text}}</li>
                  @empty
                    <li>All the questions where answered</li>
                  @endforelse
                  </ul>
                </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
