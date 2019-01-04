@extends('layouts.admin')

@section('title')
    {{ucfirst($item->name)}}
@endsection

@section('preJs')
    <script>
        let item = {!! json_encode($item) !!};
        let maxSizeImage = "{{ $maxSizeImage }}";
        let typesImage = {!! json_encode($typesImage) !!};
    </script>
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>
    <script src="/adminPanel/js/front.js?v={{time()}}"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
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
                                    <tr>
                                        <td>
                                            <div class="table-toggle-inner">
                                                <span>Name</span>
                                                <input
                                                        id="nameStatus"
                                                        data-on="Edit"
                                                        data-off="Default"
                                                        type="checkbox"
                                                        data-toggle="toggle"
                                                        checked>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{$item->name}}">
                                        </td>
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
                                        <td>Rating for all items</td>
                                        <td>
                                            <input type="number" name="ratingItems" value="" min="0">
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

                                    <tr>
                                        <td>
                                            <div class="table-toggle-inner">
                                                <span>To type games</span>
                                                <input
                                                        id="toTypeStatus"
                                                        data-on="Edit"
                                                        data-off="Default"
                                                        type="checkbox"
                                                        data-toggle="toggle"
                                                        checked>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="toType_id" class="form-control">
                                                <option value="0" selected>Do not change</option>
                                                @foreach($defaultItems as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <p>SET ALL GAMES</p>
                                            <p>DEFAULT TYPE (DEVELOP)</p>
                                        </td>
                                        <td>
                                            <input
                                                    data-on="On"
                                                    data-off="Off"
                                                    type="checkbox"
                                                    name="defaultAll"
                                                    data-toggle="toggle">
                                        </td>
                                    </tr>


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
