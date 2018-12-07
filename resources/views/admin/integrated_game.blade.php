@extends('layouts.admin')

@section('title')
    {{ucfirst($game->name)}}
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
                                        <td>Name</td>
                                        <td>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{$game->name}}" required></td>
                                    </tr>
                                    <tr>
                                        <td>Type</td>
                                        <td>
                                            <select name="type_id" class="form-control">
                                                @foreach($types as $type)
                                                    @if($game->type_id === $type->id)
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
                                        <td>Categoty</td>
                                        <td>
                                            <select name="category_id" class="form-control">
                                                @foreach($categories as $category)
                                                    @if($game->categoty_id === $category->id)
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
                                        <td>Image</td>
                                        <td>
                                            <img class="games-block__image show-animated"
                                                 @if(is_null($game->our_image))
                                                 src="{{$game->image_filled}}"
                                                 @else
                                                 src="{{$game->our_image}}"
                                                 @endif
                                                 onerror="handleImage(this);"/>
                                            <br>
                                            <span>Use Default Provider Image</span>
                                            <input type="checkbox" name="default_provider_image">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>New image</td>
                                        <td><input id="laodImage" type="file" name="image" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>Active</td>
                                        <td><input type="checkbox" name="active" @if($game->active == 1) checked @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mobile</td>
                                        <td><input type="checkbox" name="mobile" @if($game->mobile == 1) checked @endif>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Rating</td>
                                        <td><input type="number" name="rating" value="{{ $game->rating }}" min="0"></td>
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
        let dummy = "{{ $dummyPicture }}";
    </script>
    <script src="/adminPanel/js/page/gameAdminPanel.js?v={{time()}}"></script>
@endsection
