@extends('layouts.admin')

@section('title')
    Edit question
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <form method="POST" action="{{route('faqUpdate', $question)}}">
                                {{csrf_field()}}
                                <h4>Question</h4>
                                <input type="text" name="question" class="form-control" value="{{$question->question}}">
                                <h4>Answer</h4>
                                <textarea id="textarea" name="answer" class="form-control" maxlength="1000">{{$question->answer}}</textarea>

                                <br>
                                <input type="submit" name="save" value="Update" class="btn btn-primary">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection