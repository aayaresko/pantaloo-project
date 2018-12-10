@extends('layouts.admin')

@section('title')
    Integrated Types
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered dataTable no-footer datatable" role="grid"
                                   aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        id
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        code
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        name
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        image
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        active
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        rating
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        created_at
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        updated_at
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Edit
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($gamesTypes as $item)
                                    <tr role="row">
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->code }}</td>
                                        <td>{{ $item->name }}</td>
                                        @if(is_null($item->image))
                                            <td>Without Image</td>
                                        @else
                                            <td><img src="{{ $item->image }}" width="100"></td>
                                        @endif
                                        <td>@if($item->active == 1) <span class="label label-success">ON</span> @else
                                                <span class="label label-danger">OFF</span> @endif </td>
                                        <td>{{ $item->rating }}</td>
                                        <td>{{ $item->updated_at }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td><a href="/admin/integratedType/{{ $item->id }}"
                                               class="btn btn-primary">Edit</a></td>
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