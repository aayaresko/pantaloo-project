@extends('layouts.admin')

@section('title')
    {{ucfirst($slot->name)}}
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
                            <table class="table table-hover">
                                <tr><td>Image</td><td><img src="{{$slot->image}}?{{rand()}}" style="max-width: 300px;"></td></tr>
                                <tr><td>New image</td><td><input type="file" name="image" class="form-control"></td></tr>
                                <tr><td>Name</td><td><input type="text" name="display_name" class="form-control" value="{{$slot->display_name}}"></td></tr>
                                <tr><td>Raiting</td><td><input type="text" name="raiting" class="form-control" value="{{$slot->raiting}}"></td></tr>
                                <tr><td>Demo url</td><td><input type="text" name="demo_url" class="form-control" value="{{$slot->demo_url}}"></td></tr>
                                <tr><td>Is Mobile</td><td><input type="checkbox" name="is_mobile" @if($slot->is_mobile == 1) checked @endif></td></tr>
                                <tr><td>Is Bonus Game</td><td><input type="checkbox" name="is_bonus" @if($slot->is_bonus == 1) checked @endif></td></tr>
                                <tr><td>Type</td><td>{!! \App\Type::getSelect($slot->type_id) !!}</td></tr>
                                <tr><td>Status</td><td><input name="is_working" type="checkbox" @if($slot->is_working) checked="" @endif data-plugin="switchery" data-color="#00b19d" data-switchery="true"></td></tr>
                                <tr><td><input type="submit" name="save" value="Save" class="btn btn-success"></td><td></td></tr>
                            </table>

                            {{csrf_field()}}
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
