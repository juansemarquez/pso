<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg text-center">
                <h1>Future exams</h1>
                <div class="container col-12 col-md-8 offset-md-2 mx-auto">
                  <table class="table table-bordered">
                    <tr>
                      <th rowspan="2" class="align-middle">Name</th>
                      <th rowspan="2" class="align-middle">Question Bank</th>
                      <th colspan="2">Available</th>
                    </tr>
                    <tr>
                        <th>From</th><th>Until</th>
                    </tr>
                      @forelse ($exams as $exam)
                      <tr>                     
                          <td>{{$exam->exam->name}}</td>
                          <td>{{$exam->exam->questionBank}}</td>
                          <td>{{$exam->exam->from}}</td>
                          <td>{{$exam->exam->until}}</td>
                      </tr>   
                      @empty
                      <tr>   
                        <td colspan="4">There are no active exams at the moment</td>
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
<script>
var elems = document.getElementsByClassName('askBefore');
var confirmation = function (e) {
    if (!confirm('Solve it now? (You won\'t be able to retry later)')) {
        e.preventDefault();
    }
};
for (var i = 0, l = elems.length; i < l; i++) {
    elems[i].addEventListener('click', confirmation, false);
}
</script> 
</x-app-layout>
