@extends('layouts.admin')

@section('title')
    {{ucfirst($item->name)}}
@endsection

@section('preJs')
    <script>
        let maxSizeImage = "{{ $maxSizeImage }}";
        let typesImage = {!! json_encode($typesImage) !!};
    </script>
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>
    <script src="/adminPanel/js/front.js?v={{time()}}"></script>
    
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <input data-input="name, image" data-on="Edit" data-off="Default" type="checkbox" checked data-toggle="toggle" class="toggle-controll">
                            <form method="POST" enctype="multipart/form-data">
                                <table class="table table-hover">
                                    <tr>
                                        <td>Name</td>
                                        <td><input type="text" name="name" class="form-control"
                                                   value="{{$item->name}}"></td>
                                    </tr>
                                    <tr>
                                        <td>Image</td>
                                        <td>
                                            @if(is_null($item->image))
                                                Without Image
                                            @else
                                                <img style="max-width: 350px;" class="games-block__image show-animated"
                                                     src="{{ $item->image }}">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>New image</td>
                                        <td><input id="laodImage" type="file" name="image" class="form-control"></td>
                                    </tr>

                                    <tr>
                                        <td>Rating</td>
                                        <td>
                                            <input type="number" name="rating" value="{{ $item->rating }}" min="0">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>Status</td>
                                        <td>
                                            <input 
                                                data-on="On" 
                                                data-off="Off" 
                                                type="checkbox" 
                                                name="active" 
                                                data-toggle="toggle" 
                                                @if($item->active == 1) checked @endif>
                                        <!-- <input type="checkbox" name="active"
                                                   @if($item->active == 1) checked @endif> -->
                                                   </td>
                                    </tr>




                                    <!-- <tr>
                                        <td>Rating for all items</td>
                                        <td>
                                            <input type="number" name="ratingItems" value="" min="0">
                                        </td>
                                    </tr> -->

                                    <tr>
                                        <td><input type="submit" value="Save" class="btn btn-success"></td>
                                        <td>
                                            <a class="btn btn-primary" href="/admin/integratedTypes"
                                               role="button">Back</a>
                                        </td>
                                    </tr>
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

@section('js')
    <script src="/adminPanel/js/page/typeAdminPanel.js?v={{time()}}"></script>
@endsection
