@extends('layouts.admin')

@section('title')
    Finance
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
                                <div class="col-sm-3">
                                    <div class="card-box">
                                        <div id="reportrange" class=""
                                             style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                            <span></span> <b class="caret"></b>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <br>

                            <table id="tableOrder"
                                   class="table table-striped table-bordered dataTable no-footer datatable" role="grid"
                                   aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Id
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Email
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Deposits
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Pending Deposits
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Bonus cost
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Revenue
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        Profit
                                    </th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">
                                        CPA
                                    </th>
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
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>

    <script type="text/javascript">
        let dateStart = moment().startOf('day');
        let dateEnd = moment().endOf('day');
        let globalTable;
        let optionsDefault = {};
        let options = JSON.parse(JSON.stringify(optionsDefault));

        function setDate(dateStart, dateEnd) {
            options.dateStart = dateStart.unix();
            options.endStart = dateEnd.unix();
        }

        function initDataTable() {
            //destroy
            $('#tableOrder').DataTable().destroy();
            //draw new
            options['_token'] = getToken();

            let searchValue = '';
            if (typeof globalTable === 'object') {
                searchValue = globalTable.search();
            }

            let table = $('#tableOrder').DataTable({
                "order": [[0, "asc"]],
                "columnDefs": [
                    {"orderable": false, "targets": [2, 3, 4, 5, 6, 7]},
                ],
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": '/admin/globalAffiliate/getFinance',
                    "dataType": "json",
                    "type": "GET",
                    "data": options,
                    "error": function (xhr, error, thrown) {
                        console.log('error');
                    }
                },
                "oSearch": { "sSearch": searchValue },
                "columns": [
                    {"data": "id"},
                    {"data": "email"},
                    {"data": "confirmDeposits"},
                    {"data": "pendingDeposits"},
                    {"data": "bonus"},
                    {"data": "revenue"},
                    {"data": "profit"},
                    {"data": "cpa"},
                ],
            });
            globalTable = table;
        }

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }

        $(function () {
            setDate(dateStart, dateEnd);
            initDataTable();
        });

        function startDaterangepicker() {
            $('#reportrange').daterangepicker({
                startDate: dateStart,
                endDate: dateEnd,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function (start, end) {
                setDate(start, end);
                cb(start, end);
                initDataTable();
            });
        }

        cb(dateStart, dateEnd);
        startDaterangepicker();

        $('.selectpicker').change(function () {
            initDataTable();
        });

        $(document).ready(function () {
            //something
        });
    </script>
@endsection