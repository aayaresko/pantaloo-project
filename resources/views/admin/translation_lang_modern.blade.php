@extends('layouts.admin')

@section('title')
    Translations
@endsection

@section('preCss')
    <link href="/adminPanel/css/general.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table id = 'dateTable' class="table table-striped table-bordered dataTable no-footer datatable" aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        №
                                    </th>
                                    @if ($defaultLang <> $currentLang)
                                        <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">{{ $defaultLang }}</th>
                                    @endif
                                    <th tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">{{ $currentLang }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr role="row">
                                    <td></td>
                                    @if ($defaultLang <> $currentLang)
                                        <td width="50%"></td>
                                    @endif
                                    <td width="50%"></td>
                                </tr>

                                    {{--@php--}}
                                        {{--$index = 0;--}}
                                    {{--@endphp--}}
                                    {{--@foreach($translationsCurrent as $key => $translationCurrent)--}}
                                        {{--<tr role="row" id="row_{{ $key }}">--}}
                                            {{--<td>{{ ++$index }}</td>--}}
                                            {{--@if ($defaultLang <> $currentLang)--}}
                                                {{--<td>{!! $translationsDefault[$key] !!}</td>--}}
                                            {{--@endif--}}
                                            {{--<td width="50%">hgf1</td>--}}
                                        {{--</tr>--}}
                                    {{--@endforeach--}}
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
    <script src="https://cdn.ckeditor.com/ckeditor5/12.1.0/inline/ckeditor.js"></script>

    <script>

        // let editor;
        //
        // InlineEditor
        //     .create( document.querySelector( '.editor' ))
        //     .then( newEditor => {
        //         editor = newEditor;
        //     } )
        //     .catch( error => {
        //         console.error( error );
        //     } );
        //
        // $('.editor').each(function () {
        //
        // });


        {{--$.fn.editable.defaults.params = function (params) {--}}
            {{--params._token = '{{ csrf_token() }}';--}}
            {{--return params;--}}
        {{--};--}}
        {{--$.fn.editable.defaults.mode = 'inline';--}}

        {{--var dataTable = $('.datatable').DataTable({--}}
            {{--"fnDrawCallback": function (oSettings) {--}}
                {{--$('.editable').editable({--}}
                    {{--type: 'wysihtml5',--}}
                    {{--name: '{{ $currentLang }}',--}}
                    {{--pk: $(this).data('pk'),--}}
                    {{--url: '{{ route('translations.save') }}',--}}
                    {{--title: 'Enter translation',--}}
                    {{--inputclass: 'editableStyle',--}}
                    {{--wysihtml5: {--}}
                        {{--html: true,--}}
                        {{--image: false,--}}
                    {{--},--}}
                    {{--placement: 'bottom',--}}
                    {{--success: function (data) {--}}
                        {{--if(data.success == true) {--}}
                            {{--//alert('Ok');--}}
                        {{--} else {--}}
                            {{--//alert('False');--}}
                            {{--return 'Something went wrong';--}}
                        {{--}--}}
                    {{--}--}}
                {{--});--}}
            {{--}--}}
        {{--});--}}



        let currentLang = '{{ $defaultLang }}';
        let defaultLang = '{{ $currentLang }}';

        let globalTable;
        let optionsDefault = {currentLang: currentLang, defaultLang: defaultLang};
        let options = JSON.parse(JSON.stringify(optionsDefault));

        function initDataTable() {
            //destroy
            $('#dateTable').DataTable().destroy();
            //draw new
            options['_token'] = '{{ csrf_token() }}';

            let table = $('#dateTable').DataTable({
                "order": [[0, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": 1},
                    @if ($defaultLang <> $currentLang)
                        {"orderable": false, "targets": 2},
                    @endif
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '/admin/translation/getTransactions',
                    "dataType": "json",
                    "type": "GET",
                    "data": options,
                    "error": function (xhr, error, thrown) {
                        console.log('problem with dataTables');
                    }
                },
                "columns": [
                    {"data": "id"},
                    {"data": "default_lang"},
                    @if ($defaultLang <> $currentLang)
                        {"data": "current_lang"},
                    @endif
                ],
            });

            globalTable = table;
        }

        $(function () {
            initDataTable();
        });
    </script>
@endsection
