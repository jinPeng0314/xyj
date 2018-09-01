@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach($questions as $question)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span><a href="/ask/{{$question['id']}}">{{$question['content']}}</a></span>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ask/{{$question['id']}}">{{ $question['reply'][0]['content'] }}</a></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                @foreach($newQuestions as $newQuestion)
                    @foreach($newReplies as $newReply)
                        @if($newQuestion[0]['id'] == $newReply['question_id'])
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <span><a href="/ask/{{$newQuestion[0]['id']}}" style="color: red">{{$newQuestion[0]['content']}}</a></span>
                                    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ask/{{$newQuestion[0]['id']}}" style="color: red">{{$newReply['content']}}</a></p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection