@extends('layouts.admin')

@section('title')
    Banners
@endsection

@section('content')
    @include('admin.banner_view', ['banners' => $banners])
@endsection