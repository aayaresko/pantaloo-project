@extends('layouts.admin')


@section('title')
    {{$page->title}} / {{$page_lang['version']}}
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                        <form method="POST" enctype="multipart/form-data">
                            <table class="table">
                                <tr><td>Short name</td><td><input type="text" name="short_name" value="{{$page->short_name}}" class="form-control"></td></tr>
                                @if($page->parent_id == 0) <tr><td>URL</td><td><input type="text" name="url" value="{{$page->url}}" class="form-control"></td></tr>
                                @else <input type="hidden" name="url" value="{{$page->url}}" class="form-control">
                                @endif
                                <tr><td>Title</td><td><input type="text" name="title" value="{{$page->title}}" class="form-control"></td></tr>
                                <tr><td>On main</td><td><input type="checkbox" name="is_main" class="form-control" @if($page->is_main) checked @endif></td></tr>
                                <tr><td>Contnet</td><td><textarea rows="5" name="body" class="form-control">{!! $page->body !!}</textarea></td></tr>
                                <tr><td>Extra content</td><td><textarea rows="5" name="extra_content" class="form-control">{!! $page->extra_content !!}</textarea></td></tr>
                                @if($page->parent_id == 0)
                                    <tr><td>Delete</td><td><a href="{{route('pages.delete', $page)}}" class="btn btn-danger">DELETE</a></td></tr>
                                @endif
                                <tr><td>{{$page_lang['link_title']}}</td><td><a href="{{$page_lang['link']}}" class="btn btn-info">Edit</a></td></tr>
                            </table>

                            <input type="submit" name="" value="Save" class="btn btn-success">
                            {{csrf_field()}}

                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection