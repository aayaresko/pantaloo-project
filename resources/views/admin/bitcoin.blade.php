@extends('layouts.admin')

@section('title')
    Bitcoin
@endsection

@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        Balance: <b>{{$balance}}</b> BTC

                        <h3>Withdraw</h3>

                        <form method="POST" enctype="multipart/form-data">
                            <table class="table">
                                <tr><td>Sum: </td><td><input type="text" class="form-control" name="sum" value="{{$balance}}"></td></tr>
                                <tr><td>Bitcoin address: </td><td><input type="text" class="form-control" name="bitcoin_address" value=""></td></tr>
                                <tr><td><input type="submit" name="send" value="Send" class="btn btn-success"></td><td></td></tr>
                            </table>
                            {{csrf_field()}}
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection