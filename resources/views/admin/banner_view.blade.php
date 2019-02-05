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
                                        <td><a class = "showImage" href="#"><img src="{{$banner->url}}" style="max-height: 100px;"></a></td>
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


        <!-- Image preview -->
        <div class="modal fade" style = "text-align: center;" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="text-align: left; max-width: 100%; width: auto !important; display: inline-block;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Image preview</h4>
                    </div>
                    <div class="modal-body">
                        <img src="" id="imagepreview">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

