<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
                <h1>Solving: {{$examSheet->exam->name}}</h1>
            <div id="clock" class="alert alert-success" style="font-size: 5em">
                
            </div>
            <form action="{{route('submit_exam', $examSheet->id)}}" method="post" id="form"
                onsubmit="return confirm('Submit now? (Retries are not allowed)');">
                @csrf
                <input type="hidden" name="time" value="false">
                @foreach ($examSheet->questions as $question) 
                    <div class="form-group">
                    <p>{{$question->text}}</p>        
                    @foreach ($question->answers as $answer) 
                        <div class="form-group text-left">
                        <input type='radio' name='answer[{{$question->id}}]'
                        value='{{$answer->id}}' class='form-radio'>
                        {{$answer->text}}                        
                        </div>
                    @endforeach
                    </div><hr>
                @endforeach
                <div class="form-group">
                    <input type="submit" value="Submit Exam" class="btn btn-success">
                </div>
            </form>
      </div> 
                </div>
            </div>
        </div>
    </div>
    <script>
// Define up to when are we counting
var deadline = new Date("{{$deadline}}").getTime()  ;
var green = true;
// Update the clock every sencond:
var x = setInterval(function() {

  var now = new Date().getTime();
  // Distance between deadline and now (in miliseconds)
  var distance = deadline - now;

  // Calculate minutes and seconds:
  var minutes = Math.floor(distance / (1000 * 60));
  if (minutes == 0 && green) {
      document.getElementById("clock").classList.remove("alert-success");
      document.getElementById("clock").classList.add("alert-danger");
      green = false;
  }
  var seconds = Math.floor(distance % (1000*60) / 1000);

  // Show results:
  var separator = seconds < 10 ? ":0" : ":";
  document.getElementById("clock").innerHTML = minutes + separator + seconds;

  // If the countdown is finished, submit right now
  if (distance < 0) {
    clearInterval(x);
    document.getElementById("clock").innerHTML = "0:00";
    var f = document.getElementById("form");
    f.time.value = true;
    f.submit();
  }
}, 1000);
</script>
</x-app-layout>
