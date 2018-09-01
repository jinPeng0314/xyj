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
                @foreach($newReplies as $newReply)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <span><a href="/ask/{{$newReply['question']['id']}}" style="color: red">{{$newReply['question']['content']}}</a></span>
                            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/ask/{{$newReply['question']['id']}}" style="color: red">{{$newReply['content']}}</a></p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection