@extends('layouts.admin')

@section('title')
    New page
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
                                <tr><td>Short name</td><td><input type="text" name="short_name" value="" class="form-control"></td></tr>
                                <tr><td>URL</td><td><input type="text" name="url" value="" class="form-control"></td></tr>
                                <tr><td>Title</td><td><input type="text" name="title" value="" class="form-control"></td></tr>
                                <tr><td>On main</td><td><input type="checkbox" name="is_main" class="form-control"></td></tr>
                                <tr><td>Contnet</td><td><textarea rows="5" name="body" class="form-control"></textarea></td></tr>
                                <tr><td>Extra content</td><td><textarea rows="5" name="extra_content" class="form-control"></textarea></td></tr>
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