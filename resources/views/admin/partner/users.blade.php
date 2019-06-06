@extends('layouts.admin')

@section('title')
    Users without affiliate
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="card">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <h3>User table</h3>
                                    <table class="table table-striped table-bordered" id="userTable">
                                        <thead>
                                        <tr role="row">
                                            <th>ID</th>
                                            <th>Player email</th>
                                            <td>Created</td>
                                            <th>Country</th>
                                            <th>Deposit</th>
                                            <th>Today benefit</th>
                                            <th>Total Benefit</th>
                                            <th>Withdraw</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($players as $user)
                                            <tr role="row">
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->created_at->format('Y-m-d')}}</td>
                                                <td>{{$user->countries ? $user->countries->name : $user->country}}</td>
                                                <td>{{$user->deposit() ?: 0}}</td>
                                                <td>{{$user->todayPlayerSum()}}</td>
                                                <td>{{$user->totalPlayerSum()}}</td>
                                                <td>{{$user->withdraw()}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{$players->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection