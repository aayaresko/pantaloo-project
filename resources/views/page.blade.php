@extends('layouts.app')

@section('title')
    {{translate($page->title)}}
@endsection


@section('content')
<div class="page-content-block" style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
    <div class="page-content-container">
        <div class="page-content-entry">
            <div class="btn-block mobile">
                <a href="#" class="live-btn">Open Menu</a>
            </div>
            <div class="page-content-navigation mobile">
                <ul class="side-nav-listing">
                    @include('page_links', ['is_main' => 0])
                </ul>
            </div>
            <div class="page-heading">
                <h1 class="page-title">{{$page->title}}</h1>
                <p class="descr">{!! $page->body !!}</p>
            </div>
            <div class="page-entry">
                {!! $page->extra_content !!}
            </div>
        </div>
        <div class="page-content-navigation">
            <ul class="side-nav-listing">
                @include('page_links', ['is_main' => 0])
            </ul>
        </div>
    </div>
</div>
@endsection