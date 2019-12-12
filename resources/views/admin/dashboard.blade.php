@extends('layouts.admin')


@section('title')
    Dashboard
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">

                <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>
                <br><br>
                <form method="POST" enctype="multipart/form-data">
                  {{csrf_field()}}
                </form>
                
                <div class="row">

                <!--
                    <div class="col-lg-3 col-md-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">This Month Total</h4>

                            <div class="widget-box-2">
                                <div class="widget-detail-2">
                                    <span class="badge badge-success pull-left m-t-20">{{round($month_procent*100)}}% <i class="zmdi zmdi-trending-up"></i> </span>
                                    <h2 class="m-b-0"> {{round(-1*$month_total, 2)}} </h2>
                                    <p class="text-muted m-b-25">mBTC</p>
                                </div>
                                <div class="progress progress-bar-success-alt progress-sm m-b-0">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="{{round($month_length*100)}}" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{round($month_length*100)}}%;">
                                        <span class="sr-only">{{round($month_length*100)}}% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Today users</h4>

                            <div class="widget-box-2">
                                <div class="widget-detail-2">
                                    <span class="badge badge-success pull-left m-t-20">{{$users_procent}}% <i class="zmdi zmdi-trending-up"></i> </span>
                                    <h2 class="m-b-0">{{$today_users}}</h2>
                                    <p class="text-muted m-b-25">Total users: {{$total_users}}</p>
                                </div>
                                <div class="progress progress-bar-success-alt progress-sm m-b-0">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="{{round($today_length*100)}}" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{round($today_length*100)}}%;">
                                        <span class="sr-only">{{round($today_length*100)}}% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col-lg-3 col-md-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Today Total</h4>

                            <div class="widget-box-2">
                                <div class="widget-detail-2">
                                    <span class="badge badge-success pull-left m-t-20">{{round($today_procent*100)}}% <i class="zmdi zmdi-trending-up"></i> </span>
                                    <h2 class="m-b-0">{{round(-1*$today_total, 2)}}</h2>
                                    <p class="text-muted m-b-25">mBTC</p>
                                </div>
                                <div class="progress progress-bar-success-alt progress-sm m-b-0">
                                    <div class="progress-bar progress-bar-success" role="progressbar"
                                         aria-valuenow="{{round($today_length*100)}}" aria-valuemin="0" aria-valuemax="100"
                                         style="width: {{round($today_length*100)}}%;">
                                        <span class="sr-only">{{round($today_length*100)}}% Complete</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->


                    <!-- <div class="col-lg-3 col-md-6">
                        <div class="card-box">


                            <h4 class="header-title m-t-0 m-b-30">Pending Money</h4>

                            <div class="widget-chart-1">

                                <h2 class="p-t-10 m-b-0">{{round(-1*$pending_money, 2)}} mBTC</h2>
                                <p class="text-muted">Frozen sum: {{round(-1*$frozen_money, 2)}} mBTC</p>
                            </div>
                        </div>
                    </div>
                    -->
                    <!-- end col -->
                    
                     <div class="col-lg-3 col-md-6">
                        <div class="card-box">


                            <h4 class="header-title m-t-0 m-b-30">Deposits</h4>

                            <div class="widget-chart-1">

                                <h2 class="p-t-10 m-b-0">{{$deposit_sum}} mBTC</h2>
                                <p class="text-muted">Month Deposit: {{$deposit_sum}} mBTC</p>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                    <div class="col-lg-3 col-md-6">
                        <div class="card-box">
                            <h4 class="header-title m-t-0 m-b-30">Users Online</h4>

                            <div class="widget-chart-1">
                                <div class="widget-chart-box-1">
                                    <input data-plugin="knob" data-width="80" data-height="80" data-fgColor="#f05050 "
                                           data-bgColor="#F9B9B9" value="{{round($users_online/$total_users*100)}}"
                                           data-skin="tron" data-angleOffset="180" data-readOnly=true
                                           data-thickness=".15"/>
                                </div>

                                <div class="widget-detail-1">
                                    <h2 class="p-t-10 m-b-0"> {{$users_online}} </h2>
                                    <p class="text-muted">1 minute</p>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->

                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer text-right">
            2016 © Adminto.
        </footer>

    </div>
@endsection
