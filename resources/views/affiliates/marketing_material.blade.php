@extends('layouts.agent')

@section('title')
    Banners View
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
                                        №
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Link
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Html
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        View
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($banners as $key => $item)
                                    @php
                                        $index = $key + 1;
                                    @endphp
                                    <tr role="row">
                                        <td>{{ $index }}</td>
                                        <td>{{ $item->link }}</td>
                                        <td>
                                            <div class="copyHtml{{ $index }}">{{ $item->html }}</div>
                                            <a href="$item->link" class="btn btn-purple btn-xs copy-letter-button"
                                               data-clipboard-action="copy"
                                               data-clipboard-target=".copyHtml{{ $index }}">Copy</a>
                                        </td>
                                        <td>{!! $item->htmlView !!}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <a href="{{ url()->previous() }}" class="btn btn-primary btn-lg">BACK</a>

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

        var clipboard = new Clipboard('.copy-letter-button');
    </script>
@endsection