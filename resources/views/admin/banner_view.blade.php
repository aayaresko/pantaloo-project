<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        @include('agent.errors')
                        <div class="table-wrap">
                            <table class="table table-hover">
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>File Info</th>
                                    <th>Actions</th>
                                </tr>

                                @foreach($banners as $banner)
                                    <tr>
                                        <td><img src="{{$banner->url}}" style="max-height: 100px;"></td>
                                        <td>
                                            <b>Size:</b> {{$banner->size}}<br>
                                            <b>Type:</b> {{$banner->type}}
                                        </td>
                                        <td>
                                            <a href="{{$banner->url}}" class="btn btn-success" download="{{$banner->id}}">Download</a>
                                            @if(Auth::user()->isAdmin())
                                                <a href="{{route('banners.delete', $banner)}}" class="btn btn-danger">DELETE</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>