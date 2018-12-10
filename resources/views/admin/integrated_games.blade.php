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
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table id = "tableOrder" class="table table-striped table-bordered dataTable no-footer datatable" role="grid" aria-describedby="datatable_info">
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
@endsection

@section('js')
    <script src="/adminPanel/js/page/gamesAdminPanel.js?v={{time()}}"></script>
@endsection