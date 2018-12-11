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
                            Available: <span class="label label-success">{{$available}} mBTC</span>
                            <br>
                            <form method="POST" action="">
                                {{csrf_field()}}
                                <h4>Bitcoin Address</h4> <input type="text" class="form-control" name="address" style="width:400px;">
                                <br>
                                <input type="submit" name="withdraw" value="Withdraw" class="btn btn-primary">
                            </form>
                            <br>
                            <h2>History</h2>

                            @if(count($payments) > 0)
                                <table class="table table-hover">
                                    <tr><th>Date</th><th>Sum, mBtc</th><th>Status</th></tr>
                                    @foreach($payments as $payment)
                                        <tr><td>{{$payment->created_at->format('d M Y H:i')}}</td><td>{{$payment->sum}}</td><td>{!! $payment->getStatus() !!}</td></tr>
                                    @endforeach
                                </table>
                            @else
                                <i>Payments not found</i>
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
