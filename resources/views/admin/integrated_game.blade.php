@extends('layouts.admin')

@section('title')
    {{ucfirst($game->name)}}
@endsection

@section('preJs')
    <script>
        let game = {!! json_encode($game) !!};
        let dummy = "{{ $dummyPicture }}";
        let maxSizeImage = "{{ $maxSizeImage }}";
        let typesImage = {!! json_encode($typesImage) !!};
    </script>
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>
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
                                                   value="{{ $game->name }}" required>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-toggle-inner">
                                                <span>Type</span>
                                                <input
                                                        id="typeStatus"
                                                        data-on="Edit"
                                                        data-off="Default"
                                                        type="checkbox"
                                                        data-toggle="toggle"
                                                        checked>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="type_id[]" class="js-example-basic-multiple" multiple="multiple">
                                                @foreach($types as $type)
                                                    @if(in_array($type->id, $game->type_id))
                                                        <option value="{{ $type->id }}"
                                                                selected>{{ $type->name }}</option>
                                                    @else
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-toggle-inner">
                                                <span>Categoty</span>
                                                <input
                                                        id="categoryStatus"
                                                        data-on="Edit"
                                                        data-off="Default"
                                                        type="checkbox"
                                                        data-toggle="toggle"
                                                        checked>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="category_id" class="form-control">
                                                @foreach($categories as $category)
                                                    @if($game->category_id === $category->id)
                                                        <option value="{{ $category->id }}"
                                                                selected>{{ $category->name }}</option>
                                                    @else
                                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="table-toggle-inner">
                                                <span>Image</span>
                                                <input
                                                        id="imageStatus"
                                                        data-on="Edit"
                                                        data-off="Default"
                                                        type="checkbox"
                                                        data-toggle="toggle"
                                                        checked>
                                            </div>
                                        </td>
                                        <td>
                                            <img style="max-width: 350px;" class="games-block__image show-animated"
                                                 src="{{$game->image . '?v=' . time()}}"
                                                 onerror="handleImage(this);"/>
                                            <br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>New image
                                        </td>
                                        <td>
                                            <input id="laodImage" type="file" name="image" class="form-control">
                                            <input type="checkbox" name="default_provider_image" hidden>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rating</td>
                                        <td><input type="number" name="rating" value="{{ $game->rating }}" min="0"></td>
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
                                                    @if($game->active == 1) checked @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" value="Save" class="btn btn-success"></td>
                                        <td><a class="btn btn-primary" href="/admin/integratedGames"
                                               role="button">Back</a></td>
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
    <script>
        $('.js-example-basic-multiple').select2();
    </script>
    <script src="/adminPanel/js/page/gameAdminPanel.js?v={{time()}}"></script>
@endsection
