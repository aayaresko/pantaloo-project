@extends('layouts.admin')

@section('title')
    Admin
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered dataTable no-footer datatable" role="grid" aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">#</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Image</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Category</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Raiting</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Status</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Edit</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($gameList as $game)
                                    <tr role="row">
                                        <td>{{$game->id}}</td>
                                        {{--<td><img src="{{$game->image_filled}}" width="100"></td>--}}
                                        <td>{{$game->provider_id}}</td>
                                        <td>{{$game->category_id}}</td>
                                        <td>{{$game->type_id}}</td>
                                        <td>{{$game->name}}</td>
                                        <td>{{$game->raiting}}</td>
                                        <td>@if($game->active == 1) <span class="label label-success">ON</span> @else <span class="label label-danger">OFF</span> @endif </td>
                                        <td>@if($game->mobile == 1) <span class="label label-success">ON</span> @else <span class="label label-danger">OFF</span> @endif </td>
                                        {{--<td><a href="{{route('admin.slot', $slot)}}" class="btn btn-primary">Edit</a></td>--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('.datatable').dataTable();
    </script>
@endsection