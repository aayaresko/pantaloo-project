@extends('layouts.admin')


@section('title')
    Pages
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                        <table class="table table-hover">
                            <tr><th>#</th><th>Short name</th><th>Title</th><th>English</th><th>Russian</th></tr>

                            @foreach($pages as $page)
                                <tr><td>{{$page->id}}</td><td>{{$page->short_name}}</td><td>{{$page->title}}</td><td><a href="{{route('pages.edit', $page)}}" class="btn btn-primary">English</a></td><td><a href="{{route('pages.edit', \App\Page::where('parent_id', $page->id)->first())}}" class="btn btn-success">Russian</a></td></tr>
                            @endforeach
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection