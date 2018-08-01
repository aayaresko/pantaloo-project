@extends('layouts.admin')

@section('title')
    User {{$user->email}} / Bonuses
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered dataTable no-footer datatable" role="/rid" aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">#</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Description</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Info</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($active_bonus)
                                    <tr>
                                        <td>{{$active_bonus->bonus->id}}</td>
                                        <td>{{$active_bonus->bonus->name}}</td>
                                        <td>{{$active_bonus->bonus->descr}}</td>
                                        <td>Percent: {{$bonus_obj->getPercent()}} %<br>Wagered sum: {{$bonus_obj->getPlayedSum()}} mBtc</td>
                                        <td><a href="{{route('admin.bonusCancel', $user)}}" class="btn btn-danger">Cancel</a></td>
                                    </tr>
                                @else
                                    @foreach($bonuses as $bonus)
                                        <tr>
                                            <td>{{$bonus->id}}</td>
                                            <td>{{$bonus->name}}</td>
                                            <td>{{$bonus->descr}}</td>
                                            <td></td>
                                            <td><a href="{{route('admin.bonusActivate', [$user, $bonus])}}" class="btn btn-success">Activate</a></td>
                                        </tr>
                                    @endforeach
                                @endif
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

@endsection
