@extends('layouts.admin')

@section('title')
    Transfers
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">

                            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                            </div>
                            <br><br>
                            <form method="POST" enctype="multipart/form-data">
                                {{csrf_field()}}
                            </form>

                            <ul class="list-group">
                                <li class="list-group-item">
                                    Deposit
                                    <span class="badge badge-success">{{$deposit_sum}} mBTC</span>
                                </li>
                                <li class="list-group-item">
                                    Withdraw
                                    <span class="badge badge-danger">{{abs($withdraw_sum)}} mBTC</span>
                                </li>
                                <li class="list-group-item">
                                    Pending
                                    <span class="badge badge-warning">{{abs($pending_sum)}} mBTC</span>
                                </li>
                            </ul>

                            @if(count($transfers) > 0)
                                <table class="table table-hover">
                                    <tr><th>#</th><th>Sum</th><th>Type</th><th>Status</th><th>Date</th><th>User</th><th>Transaction id</th></tr>
                                @foreach($transfers as $transfer)
                                    <tr><td>{{$transfer->id}}</td><td>{{$transfer->getSum()}} mBTC</td><td>@if($transfer->type == 3) Deposit @else Withdraw @endif</td><td>{{$transfer->getStatus()}}</td><td>{{$transfer->created_at->format('d.m.Y')}}</td><td><a href="#">{{$transfer->user->email}}</a> | {{ $link }}</td><td><input type="text" class="form-control" value="{{$transfer->ext_id}}"></td></tr>
                                @endforeach
                                </table>
                            @endif

                            {{$transfers->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
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

