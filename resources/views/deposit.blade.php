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
        let paramsTable = {
            startItem: 0,
            stepItem: 10,
            getItem: 0,
            lang: '{{ $lang }}',
        };

        class Table {
            constructor(params) {
                //init table
                this.table = null;
                this.params = params;
                this.events();
                this.getDeposits(this.params, true);
            }

            events() {
                $('.loadMoredataTableBtn').on('click', () => {
                    this.getDeposits(this.params, true);
                });
            }

            getDeposits(params, counter = false) {
                let order = [0, "desc"];

                if (counter === true) {
                    this.params.getItem = this.params.getItem + this.params.stepItem;
                }

                if (this.table != null) {
                    order = this.table.order()[0];
                }

                $('#transactionsTable').DataTable().destroy();

                this.table = $('#transactionsTable').DataTable({
                    "searching": false,
                    "info": false,
                    "order": [order],
                    "columns": [
                        {"data": "date"},
                        {"data": "id"},
                        {"data": "status"},
                        {"data": "amount"}
                    ],
                    "columnDefs": [
                        {"orderable": false, "targets": 1},
                        {"orderable": false, "targets": 2}
                    ],
                    "serverSide": true,
                    'processing': true,
                    //to do preloader
                    'language': {
                        'loadingRecords': '&nbsp;',
                        'processing': 'Loading...'
                    },
                    "ajax": {
                        "url": `/${params.lang}/getDeposits`,
                        "dataType": "json",
                        "type": "GET",
                        "data": this.params,
                    },
                    "createdRow": function (row, data, dataIndex) {
                        // Set the data-status attribute, and add a class
                        let tdStatus = $(row).find('td:eq(2)');
                        tdStatus.addClass('statustransAction');

                        if (data.status === 'Confirmed') {
                            tdStatus.addClass('confirm')
                        } else {
                            tdStatus.addClass('notConfirm')
                        }
                    },
                    "initComplete": function (settings, data) {
                        if (data.status == true) {
                            if (data.nextCount == 0) {
                                $('.loadMoredataTableBtn').hide();
                            }
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
