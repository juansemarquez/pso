@extends('question_banks.layout')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-body">
                    <div class="pull-left">
                        <h2>New question</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('question_banks.show', $questionBank) }}">Back</a>
                    </div>
@if ($errors->any())
    <div class="alert alert-danger">
        <strong>¡Oops!</strong>There are errors on input data.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question Bank Name:</strong> {{ $questionBank->name }}
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Question Bank Description:</strong> {{ $questionBank->description }}
            </div>
        </div>
    </div>
<form action="{{ route('store_question') }}" method="POST">
    @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group question">
                <input type="hidden" name="question_bank_id" value="{{$questionBank->id}}">
                <strong>Question Text:</strong>
                <input type="text" name="text" class="form-control" placeholder="Eg: Which planet is closest to the sun?">
            </div><hr>
            <div class="form-group" id="answers">
                <strong>Answers:</strong>
                <div class="answer" data-order="0">
                    <input type="text" class="form-control" name="answerText[0]"
                                                     placeholder="Eg: Mars">
                    <input type="number" class="form-control" min="0" max="100"
                              name="answerValue[0]" placeholder="Eg: 0">
                    <button type="button" class="btn btn-danger"
                                    onclick="removeAnswer(this.parentElement)";>
                        Remove Answer
                    </button>
                </div><hr>
                <div class="answer" data-order="1">
                    <input type="text" class="form-control" name="answerText[1]"
                                               placeholder="Eg: Mercury">
                    <input type="number" class="form-control" min="0" max="100"
                            name="answerValue[1]" placeholder="Eg: 100">
                    <button type="button" class="btn btn-danger"
                                    onclick="removeAnswer(this.parentElement)";>
                        Remove Answer
                    </button>
                </div><hr>
                <button type="button" class="btn btn-success" onclick="addAnswer(this)";>
                    Add Answer
                </button>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Save Question</button>
        </div>
    </div>
</form>
                </div>
            </div>
        </div>
    </div>
<script>
function addAnswer(button) {
    var nextNumber = document.getElementsByClassName("answer").length;

    var a = document.createElement("div");
    a.classList.add("answer");
    a.dataset.order = nextNumber.toString();

    var i1 = document.createElement("input");
    i1.type = "text";
    i1.classList.add("form-control");
    i1.name = "answerText["+nextNumber.toString()+"]";
    i1.placeholder = "Text of the answer";

    var i2 = document.createElement("input");
    i2.type = "number";
    i2.classList.add("form-control");
    i2.name = "answerValue["+nextNumber.toString()+"]";
    i2.placeholder = "%";

    var btn = document.createElement("button");
    btn.type = "button";
    btn.classList.add("btn");
    btn.classList.add("btn-danger");
    btn.addEventListener("click", function() { removeAnswer(this.parentElement);});
    btn.innerHTML = "Remove Answer";

    document.getElementById("answers").insertBefore(a, button);
    a.appendChild(i1);
    a.appendChild(i2);
    a.appendChild(btn);

    var hr = document.createElement('hr');
    document.getElementById("answers").insertBefore(hr, button);
    
}

function removeAnswer(ans) { 
    var removedElement = parseInt(ans.dataset.order);
    ans.remove();
    var answers = document.getElementsByClassName('answer');
    var newOrder = removedElement;
    var j;
    for (var i= 0; i<answers.length; i++) {
        if ( parseInt(answers[i].dataset.order) > removedElement) {
            answers[i].dataset.order = newOrder.toString();
            for (j=0; j<answers[i].children.length; j++) {
                if (answers[i].children[j].tagName == "INPUT") {
                    if(answers[i].children[j].type == "text") {
                        answers[i].children[j].name = "answerText["+newOrder.toString()+"]";
                    }
                    else if(answers[i].children[j].type == "number") {
                        answers[i].children[j].name =  "answerValue["+newOrder.toString()+"]";
                    }
                }
            }
            newOrder++;
        }
    }
}
</script>

@endsection
