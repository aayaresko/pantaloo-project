@extends('layouts.admin')

@section('title')
    Create question
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <form method="POST" action="{{route('faqStore')}}">
                                {{csrf_field()}}
                                <h4>Question</h4>
                                <input type="text" name="question" class="form-control">
                                <h4>Answer</h4>
                                <textarea id="textarea" name="answer" class="form-control" maxlength="1000"></textarea>

                                <br>
                                <input type="submit" name="save" value="Create" class="btn btn-success">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection