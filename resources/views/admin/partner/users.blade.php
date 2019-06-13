@extends('layouts.admin')

@section('title')
    Users without affiliate
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <h3>User table</h3>
                                    <table class="table table-striped table-bordered" id="userTable">
                                        <thead>
                                        <tr role="row">
                                            <th>ID</th>
                                            <th>Player email</th>
                                            <td>Created</td>
                                            <th>Country</th>
                                            <th>Deposit</th>
                                            <th>Today benefit</th>
                                            <th>Total Benefit</th>
                                            <th>Withdraw</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>

                            </div>
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

        $('#userTable').DataTable({
            "order": [[0, "asc"]],
            "columnDefs": [
                {"orderable": false, "targets": [3, 4, 5, 6, 7]},
            ],
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": '/admin/globalAffiliate/getUsersTable',
                "dataType": "json",
                "type": "GET",
                "data": options,
                "error": function (xhr, error, thrown) {
                    console.log('error');
                }
            },
            "columns": [
                {"data": "id"},
                {"data": "email"},
                {"data": "created_at"},
                {"data": "country"},
                {"data": "deposit"},
                {"data": "today_benefit"},
                {"data": "total_benefit"},
                {"data": "withdraw"},
            ],
        });
    </script>
@endsection