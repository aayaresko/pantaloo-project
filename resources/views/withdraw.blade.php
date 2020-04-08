@extends('layouts.app')

@section('title', trans('Withdraw'))

@section('content')
    <div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">

        <div class="cabinet-entry cabinetMod">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">{{ trans('casino.withdraw') }}</h1>
                </div>

                @include('main_parts.header_account')

                <div class="text-block-withdraw">
                    <p class="descr">{{ trans('casino.transfer_wallet_address') }}</p>
                    <p class="descr">
                        {{ trans('casino.have_millibitcoins', ['availableBalance' => $user->balance]) }}
                    </p>
                </div>

                <div class="main-content-entry">
                    <div class="withdraw-entry">
                        <div class="middle-block">
{{--                            <center>--}}
{{--                                @foreach($errors->all() as $error)--}}
{{--                                    {{$error}}<br>--}}
{{--                                @endforeach--}}
{{--                                <br><br>--}}
{{--                            </center>--}}
                            <br><br>
                            <form action="" method="POST">               
                                <span class="text">{{ trans('casino.your_bitcoin_address') }}</span>
                                <input type="text" name="address"  pattern="{{ \Helpers\GeneralHelper::getBTCAddressPattern() }}" required>
                                <span class="text">{{ trans('casino.amount_mbtc') }}</span>
                                <input type="text" name="sum" pattern="^([0-9]*[1-9][0-9]*(\.[0-9]+)?|[0]*\.[0-9]*[1-9][0-9]*)$" required>
                                <button class="withdraw">{{ trans('casino.withdraw') }}</button>
                                {{csrf_field()}}
                            </form>
                        </div>
                    </div>
                </div>

                <div class='tableTransactionsWrapper'>
                    <p class="descr">{{ trans('casino.your_withdraws') }}</p>
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
<script src="/vendors/dataTables.js"></script>
<script src="/vendors/dataTables_pageLoadMore.js"></script>
    <script>
        let paramsTable = {
            startItem: 0,
            stepItem: 10,
            getItem: 0,
            lang: '{{ $lang }}',
            timezoneOffset: new Date().getTimezoneOffset()
        };

        class Table {
            constructor(params) {
                //init table
                this.table = null;
                this.params = params;
                this.events();
                this.getDeposits(this.params, true);
            }

            getParams() {
                return this.params;
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
                        "url": `/${params.lang}/getWithdraws`,
                        "dataType": "json",
                        "type": "GET",
                        "data": this.params,
                    },
                    "createdRow": function (row, data, dataIndex) {
                        // Set the data-status attribute, and add a class
                        let tdStatus = $(row).find('td:eq(2)');
                        tdStatus.addClass('statustransAction');

                        switch (data.statusCode) {
                            case 1:
                                tdStatus.addClass('confirm');
                                break;
                            default:
                                tdStatus.addClass('notConfirm');
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
