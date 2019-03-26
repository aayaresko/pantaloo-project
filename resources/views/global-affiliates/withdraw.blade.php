@extends('layouts.admin')

@section('title')
    Withdraw
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <ul class="nav nav-tabs navtab-wizard nav-justified bg-muted">
                                <li class="active"><a href="#pending" data-toggle="tab" aria-expanded="true">Pending
                                        <span class="badge badge-warning">{{$pending->count()}}</span></a></li>

                                <li class=""><a href="#frozen" data-toggle="tab" aria-expanded="false">Frozen <span
                                                class="badge badge-primary">{{$frozen->count()}}</span></a></li>

                                <li class=""><a href="#queue" data-toggle="tab" aria-expanded="false">Queue <span
                                                class="badge badge-info">{{$queue->count()}}</span></a></li>

                                <li class=""><a href="#errors" data-toggle="tab" aria-expanded="false">Errors <span
                                                class="badge badge-danger">{{$failed->count()}}</span></a></li>

                                <li class=""><a href="#approved" data-toggle="tab" aria-expanded="false">Approved <span
                                                class="badge badge-success">{{$approved->count()}}</span></a></li>
                            </ul>
                            <div class="tab-content b-0 m-b-0">
                                <div class="tab-pane m-t-10 fade active in" id="pending">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            @include('global-affiliates.transactions', ['transactions' => $pending])
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane m-t-10 fade" id="frozen">
                                    <div class="row">
                                        @include('global-affiliates.transactions', ['transactions' => $frozen])
                                    </div>
                                </div>
                                <div class="tab-pane m-t-10 fade" id="errors">
                                    <div class="row">
                                        @include('global-affiliates.transactions', ['transactions' => $failed])
                                    </div>
                                </div>
                                <div class="tab-pane m-t-10 fade" id="approved">
                                    <div class="row">
                                        @include('global-affiliates.transactions', ['transactions' => $approved])
                                    </div>
                                </div>
                                <div class="tab-pane m-t-10 fade" id="queue">
                                    <div class="row">
                                        @include('global-affiliates.transactions', ['transactions' => $queue])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approveTransaction" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Transactions №<span class='insertId'></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div style="text-align: center;">
                        <h4>Are you sure to <span class='insertActLow'></span> withdrawal ?</h4>
                        <br>
                        <a class="btn btn-success finalApproveTransaction"><span class='insertAct'></span></a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('.datatable').dataTable();

        $(function () {
            $('body').on('click', '.modal_href', function (e) {

                Custombox.open({
                    target: '#modal_' + $(this).data('modal_id'),
                    effect: 'fadein'
                });
                e.preventDefault();
            });
        });

        $('body').on('click', '.actTransaction', function (e) {
            e.preventDefault();
            //insert
            let link = $(this).attr('href');
            let act = $(this).attr('data-act');
            let transactionId = $(this).attr('data-transaction');
            $('#approveTransaction .insertId').text(transactionId);
            $('#approveTransaction .insertAct').text(act);
            $('#approveTransaction .insertActLow').text(act.toLowerCase());
            $('.finalApproveTransaction').attr('href', link);
            //show
            $('#approveTransaction').modal('show');
        });
    </script>
@endsection