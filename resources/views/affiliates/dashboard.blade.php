@extends('layouts.agent')


@section('title')
    Dashboard
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <div id="reportrange" class="" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-6">
                        <div class="card-box widget-user">
                            <div class="text-center">
                                <h2 class="text-custom">{{$deposit_total}} mBtc</h2>
                                <h5>DEPOSITS</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <div class="card-box widget-user">
                            <div class="text-center">
                                <h2 class="text-warning">{{$bonus_total}} mBtc</h2>
                                <h5>BONUS COST</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <div class="card-box widget-user">
                            <div class="text-center">
                                <h2 class="text-info">{{$revenue_total}} mBtc</h2>
                                <h5>REVENUE</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <div class="card-box widget-user">
                            <div class="text-center">
                                <h2 class="text-success">{{$profit_total}} mBtc</h2>
                                <h5>PROFIT</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6">
                        <div class="card-box widget-user">
                            <div class="text-center">
                                <h2 class="text-success">{{$cpa_total}}</h2>
                                <h5>CPA</h5>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- end row -->

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active">
                                    <a href="#home1" role="tab" data-toggle="tab" aria-expanded="false">Users</a>
                                </li>
                                <li role="presentation" class="">
                                    <a href="#profile1" role="tab" data-toggle="tab" aria-expanded="true">Links</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="home1">

                                    <table class="table table-striped table-bordered dataTable no-footer datatable" role="/rid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">User ID</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Deposits</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">CPA</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Pending CPA</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Bets</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending">Bet count</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Avg bet</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Wins</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Revenue</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Bonus</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">My profit</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($users as $stat)
                                            <tr role="row">
                                                <td><span class="label label-default">{{$stat['user']->id}}</span></td>
                                                <td>{{$stat['deposits']}}</td>
                                                <td>{{$stat['cpa']}}</td>
                                                <td>{{$stat['cpaPending']}}</td>
                                                <td>{{$stat['bets']}}</td>
                                                <td>{{$stat['bet_count']}}</td>
                                                <td>{{$stat['avg_bet']}}</td>
                                                <td>{{$stat['wins']}}</td>
                                                <td>{{$stat['revenue']}}</td>
                                                <td>{{$stat['bonus']}}</td>
                                                <td>{{$stat['profit']}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="profile1">
                                    <table class="table table-striped table-bordered dataTable no-footer datatable" role="/rid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Campaign Name</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Clicks</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Registrations</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Deposits</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Revenue</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Bonus</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">My profit</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($trackers as $stat)
                                            <tr role="row">
                                                <td><span class="label label-default">{{$stat['tracker']}}</span></td>
                                                <td>{{$stat['enters']}}</td>
                                                <td>{{$stat['registrations']}}</td>
                                                <td>{{$stat['deposits']}}</td>
                                                <td>{{$stat['revenue']}}</td>
                                                <td>{{$stat['bonus']}}</td>
                                                <td>{{$stat['profit']}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer text-right">
            2016 © Casinobit.
        </footer>

    </div>
@endsection

@section('js')
    <script>
        $('.datatable').dataTable();
    </script>

    <script type="text/javascript">
        $(function() {

            var QueryString = function () {
                // This function is anonymous, is executed immediately and
                // the return value is assigned to QueryString!
                var query_string = {};
                var query = window.location.search.substring(1);
                var vars = query.split("&");
                for (var i=0;i<vars.length;i++) {
                    var pair = vars[i].split("=");
                    // If first entry with this name
                    if (typeof query_string[pair[0]] === "undefined") {
                        query_string[pair[0]] = decodeURIComponent(pair[1]);
                        // If second entry with this name
                    } else if (typeof query_string[pair[0]] === "string") {
                        var arr = [ query_string[pair[0]],decodeURIComponent(pair[1]) ];
                        query_string[pair[0]] = arr;
                        // If third or later entry with this name
                    } else {
                        query_string[pair[0]].push(decodeURIComponent(pair[1]));
                    }
                }
                return query_string;
            }();

            var start = moment(QueryString.start);
            var end = moment(QueryString.end);

            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, function (start, end) {
                window.location = location.protocol + '//' + location.host + location.pathname + '?start=' + start.format('YYYY-MM-DD') + '&end=' + end.format('YYYY-MM-DD');
            });

            cb(start, end);

        });
    </script>
@endsection