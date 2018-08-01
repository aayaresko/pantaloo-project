@extends('layouts.admin')


@section('title')
    FAQ
@endsection

@section('content')
    @include('admin.questions', ['questions' => $questions])
@endsection