@extends('layouts.agent')


@section('title')
    Users
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
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
                <div class="row">
                    <div class="col-md-12">
                        My current commission: {{$myKoef}}
                    </div>
                    <div class="col-lg-12">
                        <div class="card-box">
                            <table id="affiliateUsersTable" class="table table-striped table-bordered">
                                <thead>
                                <tr role="row">
                                    <th>ID</th>
                                    <th>Campaign name</th>
                                    <th>Country</th>
                                    <th>Created</th>
                                    <th>Today benefit</th>
                                    <th>Total Benefit</th>
                                    <th>Profit</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($users as $user)
                                    <tr role="row">
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->tracker ? $user->tracker->name : 'default'}}</td>
                                        <td>{{$user->countries ? $user->countries->name : $user->country}}</td>
                                        <td>{{$user->created_at->format('Y-m-d  H:i')}}</td>
                                        <td>{{$user->todayPlayerSum()}}</td>
                                        <td>{{$user->totalPlayerSum()}}</td>
                                        <td>{{$user->totalPlayerProfit()}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer text-right">
            2019 © Casinobit.
        </footer>

    </div>
@endsection

@section('js')
    <script src="/adminPanel/js/general.js?v={{time()}}"></script>

    <script type="text/javascript">
        var table = $('#affiliateUsersTable').DataTable({
            "order": [[0, "asc"]],
        });
        var globalStart = {};
        var globalEnd = {};

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            globalStart = start;
            globalEnd = end;

            table.draw();
        }

        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var min =  globalStart;
                var max  = globalEnd;
                var startDate = moment(data[3], 'YYYY-MM-DD HH');
                if (startDate.isBetween(min, max)) {
                    return true;
                }
                return false;
            }
        );

        function startDaterangepicker() {
            $('#reportrange').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function (start, end) {
                cb(start, end);
            });
        }

        startDaterangepicker();

        $(document).ready(function () {
            $('.toggle-change').click(function () {
                $(this).parent().find('form').toggle();
            });
        });
    </script>
@endsection
