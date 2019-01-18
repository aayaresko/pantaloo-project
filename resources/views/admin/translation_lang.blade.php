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
                                            <td>{{ $translationsDefault[$key]}}</td>
                                        @endif
                                        <td><a href="#" class="editable"
                                               data-pk="{{ $key }}">{{ $translationCurrent }}</a></td>
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
            params._token = '{{ csrf_token() }}';
            return params;
        };

        var dataTable = $('.datatable').DataTable({
            "fnDrawCallback": function (oSettings) {

                $('.editable').editable({
                    type: 'text',
                    name: '{{ $currentLang }}',
                    pk: $(this).data('pk'),
                    url: '{{ route('translations.save') }}',
                    title: 'Enter translation',
                    success: function (data) {
                        if(data.success == true) {
                            alert('Ok');
                        } else {
                            alert('False');
                        }
                    },
                });
            }
        });

    </script>
@endsection
