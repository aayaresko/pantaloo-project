@extends('layouts.admin')

@section('title')
    Translations
@endsection

@section('preCss')
    <link href="/adminPanel/css/general.css" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table id='dateTable'
                                   class="table table-striped table-bordered dataTable no-footer datatable"
                                   aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th width="1%">
                                        №
                                    </th>
                                    <th width="1%">
                                        key
                                    </th>
                                    @if ($defaultLang <> $currentLang)
                                        <th>{{ $defaultLang }}</th>
                                    @endif
                                    <th>{{ $currentLang }}</th>
                                </tr>
                                </thead>

                                <tbody>
                                <tr role="row">
                                    <td></td>
                                    <td></td>
                                    @if ($defaultLang <> $currentLang)
                                        <td></td>
                                    @endif
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
    <script src="https://cdn.ckeditor.com/ckeditor5/12.1.0/inline/ckeditor.js"></script>
    <script>
        let editorArray = [];
        let key = '';
        $('body').on('click', '.ckEditor', function (e) {
            $('.panelEditor').hide();
            key = $(this).attr("data-group") + $(this).attr("data-item");
            if (!(key in editorArray)) {
                InlineEditor
                    .create(this, {
                        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo']
                    })
                    .then(newEditor => {
                        editorArray[key] = newEditor;
                        //show panel editor
                        $(this).trigger('focus');
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
            //show form
            $(this).next().show();
        });


        $('body').on('click', '.saveTrans', function (e) {
            let bottom = $(this);
            bottom.prop('disabled', true);
            let parent = $(this).parent();
            let group = parent.parent().find('.ckEditor').attr("data-group");
            let item = parent.parent().find('.ckEditor').attr("data-item");
            let key = group + item;
            let objectEditor = editorArray[key];
            console.log(objectEditor.getData());
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                url: '/admin/translations/save',
                data: {
                    currentLang: currentLang,
                    group: group,
                    item: item,
                    text: objectEditor.getData()
                },
                success: (json) => {
                    parent.find('.statusEditor').val(json.msg);
                    bottom.prop('disabled', false);

                },
                error: (json) => {
                    parent.find('.statusEditor').val('Some is wrong');
                    bottom.prop('disabled', false);
                }
            });
        });

        let currentLang = '{{ $currentLang }}';
        let defaultLang = '{{ $defaultLang }}';

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
                    {"orderable": false, "targets": 0},
                    {"orderable": false, "targets": 1},
                    {"orderable": false, "targets": 2},
                        @if ($defaultLang <> $currentLang)
                    {
                        "orderable": false, "targets": 3
                    },
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
                "drawCallback": function (settings) {
                    editorArray = [];
                },
                "columns": [
                    {"data": "key"},
                    {"data": "item"},
                        @if ($defaultLang <> $currentLang)
                    {
                        "data": "text"
                    },
                        @endif
                    {
                        "data": "cur_text"
                    },
                ],
            });

            globalTable = table;
        }

        $(function () {
            initDataTable();
        });
    </script>
@endsection
