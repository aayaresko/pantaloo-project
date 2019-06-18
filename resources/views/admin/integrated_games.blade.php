@extends('layouts.admin')

@section('title')
    Admin
@endsection

@section('preJs')
    <script>
        let dummy = "{{ $dummyPicture }}";
    </script>
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container game-list">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <div class="row">
                                <div class="col-sm-2">
                                    <select name="type_id" class="selectpicker selectType" data-live-search="true">
                                        <option value="0" selected>Type / All</option>
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                        <option value="-1">Type / New</option>
                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <select name="category_id" class="selectpicker selectCategory" data-live-search="true">
                                        <option value="0" selected>Category / All</option>
                                        @foreach($category as $categorys)
                                            <option value="{{$categorys->id}}">{{$categorys->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="provider_id" class="selectpicker selectProvider" data-live-search="true">
                                        <option value="0" selected>Provider / All</option>
                                        <option value="1">Pantallo</option>
                                    </select>
                                </div>

                                <div class="col-sm-2">
                                    <select name="mobile" class="selectpicker selectMobile" data-live-search="true">
                                        <option value="3" selected>Mobile / All</option>
                                        <option value="1">On</option>
                                        <option value="0">Off</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="active" class="selectpicker selectActive" data-live-search="true">
                                        <option value="3" selected>Active / All</option>
                                        <option value="1">On</option>
                                        <option value="0">Off</option>
                                    </select>
                                </div>

                            </div>

                            <br>
                            {{--<div style="min-height: 1000px">--}}
                            <div>
                            <table id = "tableOrder" class="table table-striped table-bordered dataTable no-footer datatable"  role="grid" aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Id</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Name</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Provider</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Type</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Category</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Image</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Raiting</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Status</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Mobile</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Edit</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr role="row">
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="/adminPanel/js/page/gamesAdminPanel.js?v={{time()}}"></script>
@endsection