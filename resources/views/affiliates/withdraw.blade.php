@extends('layouts.agent')

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
                            Available: <span class="label label-success">{{ $available }} mBTC</span>
                            <br>
                            <form method="POST" action="">
                                {{csrf_field()}}
                                <h4>Bitcoin Address</h4> <input type="text" class="form-control" name="address"
                                                                style="width:400px;">
                                <br>
                                <input type="submit" name="withdraw" value="Withdraw" class="btn btn-primary">
                            </form>
                            <br>
                            <h2>History</h2>

                            @if(count($transactions) > 0)
                                <div class="table-wrap">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>Date</th>
                                            <th>Sum, mBtc</th>
                                            <th>Status</th>
                                        </tr>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format(trans('dateformat.date_format')) }}</td>
                                                <td>{{ $transaction->sum }}</td>
                                                @php
                                                    $status = $statusPayment[$transaction->withdraw_status];
                                                @endphp
                                                <td>
                                                    @if ((int)$status < 0)
                                                        <span class="label label-warning">{{ $status }}</span>
                                                    @else
                                                        <span class="label label-success">{{ $status }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @else
                                <i>Transactions not found</i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

@endsection
