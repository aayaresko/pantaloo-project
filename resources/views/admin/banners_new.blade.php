@extends('layouts.admin')

@section('title')
    Create  banner
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <form method="POST" action="{{route('banners.store')}}" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="file" name="image" class="dropify" data-height="200">
                                <br>
                                <input type="submit" name="upload" value="Upload" class="btn btn-success">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
