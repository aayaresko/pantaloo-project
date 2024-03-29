@extends('layouts.admin')

@section('title')
    Translations
@endsection

@section('preCss')
    <link href="/adminPanel/css/general.css" rel="stylesheet" type="text/css" />
    <link href="/adminPanel/lib/bootstrap-wysihtml/css/bootstrap-wysihtml5-0.0.3.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered dataTable no-footer datatable" role="/rid"
                                   aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        №
                                    </th>
                                    @if ($defaultLang <> $currentLang)
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                            colspan="1">{{ $defaultLang }}</th>
                                    @endif
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                        colspan="1">{{ $currentLang }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php
                                    $index = 0;
                                @endphp
                                @foreach($translationsCurrent as $key => $translationCurrent)
                                    <tr role="row" id="row_{{ $key }}">
                                        <td>{{ ++$index }}</td>
                                        @if ($defaultLang <> $currentLang)
                                            <td>{!! $translationsDefault[$key] !!}</td>
                                        @endif
                                        <td>
                                            <div href="#" class="editable" style="cursor: pointer"
                                               data-pk="{{ $key }}">{!! $translationCurrent !!}</div>
                                        </td>
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

    <script src="/adminPanel/lib/bootstrap-wysihtml/js/wysihtml5-0.3.0.min.js"></script>
    <script src="/adminPanel/lib/bootstrap-wysihtml/js/bootstrap-wysihtml5-0.0.3.min.js"></script>
    <script src="/adminPanel/lib/bootstrap-wysihtml/js/wysihtml5-0.0.3.js"></script>

    <script>
        $.fn.editable.defaults.params = function (params) {
            params._token = '{{ csrf_token() }}';
            return params;
        };
        $.fn.editable.defaults.mode = 'inline';

        var dataTable = $('.datatable').DataTable({
            "fnDrawCallback": function (oSettings) {
                $('.editable').editable({
                    type: 'wysihtml5',
                    name: '{{ $currentLang }}',
                    pk: $(this).data('pk'),
                    url: '{{ route('translations.save') }}',
                    title: 'Enter translation',
                    inputclass: 'editableStyle',
                    wysihtml5: {
                        html: true,
                        image: false,
                    },
                    placement: 'bottom',
                    success: function (data) {
                        if(data.success == true) {
                            //alert('Ok');
                        } else {
                            //alert('False');
                            return 'Something went wrong';
                        }
                    }
                });
            }
        });
    </script>
    <style>
        .wysihtml5-sandbox{
            width: 900px!important;
            height: 500px!important;
        }
    </style>
@endsection
