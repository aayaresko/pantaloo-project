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
                        <form method="POST" enctype="multipart/form-data">

                            {{csrf_field()}}
                        </form>
                        <table class="table">
                            <tr><td>Deposit</td><td>{{$deposit_sum}} mBTC</td></tr>
                            <tr><td>Withdraw</td><td>{{$withdraw_sum}} mBTC</td></tr>
                            <tr><td>Total</td><td>{{($deposit_sum + $withdraw_sum)}} mBTC</td></tr>
                        </table>

                        @if(count($transfers) > 0)
                            <table class="table table-hover">
                                <tr><th>#</th><th>Sum</th><th>Type</th><th>Status</th><th>User</th><th>Transaction id</th></tr>
                                @foreach($transfers as $transfer)
                                    <tr><td>{{$transfer->id}}</td><td>{{$transfer->getSum()}} mBTC</td><td>@if($transfer->type == 3) Deposit @else Withdraw @endif</td><td>{{$transfer->getStatus()}}</td><td><a href="#">{{$transfer->user->email}}</a></td><td><input type="text" class="form-control" value="{{$transfer->ext_id}}"></td></tr>
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
