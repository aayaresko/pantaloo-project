@extends('layouts.app')

@section('title', trans('Deposit'))

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry cabinetMod">
            <div class="main-content">

                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.deposit'), MB_CASE_UPPER) }}</h1>
                </div>

                @include('main_parts.header_account')

                <div class="main-content-entry">
                    <div class="deposit-entry">

                        <div class="left-content">
                            <div class="text-block">
                                <p class="descr">{{ trans('casino.minimum_deposit') }}</p>
                                <p class="descr">(1 BTC = 1000 mBTC)</p>
                            </div>
                            <p class="descrTxt">{{ trans('casino.send_your_bitcoins') }}</p>
                            <div class="generated-key-wrapper">
                                <input type="text" class="generated-key" value="{{ $user->bitcoin_address }}">

                                <button id="btnKey"
                                        class="generated-key-btn">{{ trans('casino.deposit_copy') }}</button>
                                <div class="copied">{{ trans('casino.deposit_copied') }}</div>
                            </div>
                        </div>
                        <div class="qr-code">
                            <img class="rounded"
                                 src="https://chart.googleapis.com/chart?chs=201x204&cht=qr&chl={{ $user->bitcoin_address }}&choe=UTF-8"
                                 alt="qr">
                        </div>
                    </div>

                </div>
                <div class='tableTransactionsWrapper'>
                    <p class="descr">{{ trans('Your Deposits') }}</p>
                    <table id="transactionsTable" class="display">
                        <thead>
                        <tr>
                            <th>{{ trans('casino.deposit_data') }}</th>
                            <th>{{ trans('casino.transaction_id') }}</th>
                            <th>{{ trans('casino.transaction_status') }}</th>
                            <th>{{ trans('casino.transaction_amount') }}</th>
                        </tr>
                        </thead>
                    </table>
                    <button class="loadMoredataTableBtn">{{ trans('casino.transaction_more') }}</button>
                    <hr class="devider">
                </div>
            </div>
            @include('settings')
        </div>
    </div>

    @include('footer_main')
@endsection

@section('js')
    <script>
        let a;
        let paramsTable = {
            startItem: 0,
            stepItem: 10,
            getItem: 0,
            lang: '{{ $lang }}',
            order: ['id', 'desc']
        };

        class Table {
            constructor(params) {
                //init table
                this.params = params;
                this.column = [
                    {"data": "date"},
                    {"data": "id"},
                    {"data": "status"},
                    {"data": "amount"}
                ];
                this.table = $('#transactionsTable').DataTable({
                    "searching": false,
                    //"iDisplayLength": 1000,
                    "info": false,
                    "ordering": true,
                    "order": [],
                    createdRow: function (row, data, dataIndex) {
                        // Set the data-status attribute, and add a class
                        let tdStatus = $(row).find('td:eq(2)');
                        tdStatus.addClass('statustransAction');

                        if (data.status === 'Confirmed') {
                            tdStatus.addClass('confirm')
                        } else {
                            tdStatus.addClass('notConfirm')
                        }

                    },
                    "columns": this.column,
                    "columnDefs": [
                        {"orderable": false, "targets": 1},
                        {"orderable": false, "targets": 2}
                    ],
                });
                //init events
                this.events();
                this.getDeposits(this.params);
            }

            events() {
                $('.loadMoredataTableBtn').on('click', () => {
                    this.getDeposits(this.params);
                });

                this.table.on('click', 'th', (e) => {
                    e.preventDefault();
                    let sort = this.table.order();
                    let order = [this.column[sort[0][0]].data, sort[0][1]];
                    this.params.order = order;
                    //console.log(this.params);
                    //this.params.order = this.params
                    this.getDeposits(this.params, false);

                });
            }

            getDeposits(params, counter = true) {

                if (counter === true) {
                    this.params.getItem =  this.params.getItem + this.params.stepItem;
                }

                $.ajax({
                    type: 'get',
                    url: `/${params.lang}/getDeposits`,
                    data: params,
                    success: (data) => {
                        if (data.success === true) {
                            //clear all datatables
                            table.table.clear().draw();
                            if (data.countNext == 0) {
                                $('.loadMoredataTableBtn').hide();
                            }

                            this.table.rows.add(data.deposits).draw(true);
                        }
                    }
                });
            }
        }

        let table;
        (function () {
            table = new Table(paramsTable);
        })();

    </script>
@endsection
