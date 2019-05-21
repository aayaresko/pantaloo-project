@extends('layouts.admin')

@section('title')
    Affiliates
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
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                        <tr role="row">
                                            <th>ID</th>
                                            <th>Player email</th>
                                            <td>Campaign name</td>
                                            <td>Created</td>
                                            <th>Country</th>
                                            <th>Today benefit</th>
                                            <th>Total Benefit</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($users as $user)
                                            <tr role="row">
                                                <td>{{$user->id}}</td>
                                                <td>{{$user->email}}</td>
                                                <td>{{$user->tracker ? $user->tracker->name : 'default'}}</td>
                                                <td>{{$user->created_at->format('Y-m-d')}}</td>
                                                <td>{{$user->countries ? $user->countries->name : $user->country}}</td>
                                                <td>{{$user->todayPlayerSum()}}</td>
                                                <td>{{$user->totalPlayerSum()}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

