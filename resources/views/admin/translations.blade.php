@extends('layouts.admin')

@section('title')
    Translations
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
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">English</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Russian</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Delete</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($translations as $translation)
                                    <tr role="row" id="row_{{$translation->id}}">
                                        <td style="max-width: 50%;">{{$translation->id}}</td>
                                        <td>{{$translation->eng}}</td>
                                        <td><a href="#" class="editable" data-pk="{{$translation->id}}">{{$translation->rus}}</a></td>
                                        <td><a href="#" class="btn btn-danger btn-sm delete" data-pk="{{$translation->id}}">Delete</a></td>
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

        $.fn.editable.defaults.params = function (params) {
            params._token = '{{csrf_token()}}';
            return params;
        };

        var data_table = $('.datatable').DataTable({
            "fnDrawCallback": function( oSettings ) {
                $('.editable').editable({
                    type: 'text',
                    pk: $(this).data('pk'),
                    url: '{{route('translations.save')}}',
                    title: 'Enter username'
                });
            }
        });

        $(document).ready(function() {

            $('body').on('click', '.delete', function () {

                var delete_row = '#row_' + $(this).data('pk');

                $.ajax({
                    type : 'POST',
                    url: '{{route('translations.delete')}}',
                    data: {id: $(this).data('pk')},
                    success: function () {
                        console.log(delete_row);
                        data_table.row(delete_row).remove().draw(false);
                    }
                });
            });
        });
    </script>
@endsection
