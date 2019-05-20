@extends('layouts.agent')


@section('title')
    Partner: <i>{{$partner->email}}</i>
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    @if($affiliates->count())
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h3>Afiliate table</h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr role="row">
                                    <th>ID</th>
                                    <th>Agent email</th>
                                    <th>Commission</th>
                                    <th>Players count</th>
                                    <th>Agents count</th>
                                    <th>Players total count</th>
                                    <th>Agent benefit</th>
                                    <th>Agent total benefits</th>
                                    <th>Agent profit</th>
                                    <th>Agent total profit</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($affiliates as $affiliate)
                                    <tr role="row">
                                        <td>{{$affiliate->id}}</td>
                                        <td>{{$affiliate->email}}</td>
                                        <td>{{$affiliate->koefs->koef}}</td>
                                        <td>{{$affiliate->playersCount()}}</td>
                                        <td>{{$affiliate->agentsCount()}}</td>
                                        <td>{{$affiliate->playersTotalCount()}}</td>
                                        <td>{{$affiliate->benefits->sum('total_sum')}}</td>
                                        <td>{{$affiliate->allBenefits()}}</td>
                                        <td>{{$affiliate->profit()}}</td>
                                        <td>{{$affiliate->totalProfit()}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    <div class="col-lg-12">
                        <div class="card-box">
                            <h3>User table</h3>
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr role="row">
                                    <th>ID</th>
                                    <th>Player email</th>
                                    <th>Country</th>
                                    <th>Today benefit</th>
                                    <th>Total Benefit</th>
                                    <th>Profit</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($users as $user)
                                    <tr role="row">
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->countries ? $user->countries->name : $user->country}}</td>
                                        <td>{{$user->todayPlayerSum()}}</td>
                                        <td>{{$user->totalPlayerSum()}}</td>
                                        <td>{{$user->totalPlayerProfit()}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div> <!-- container -->

        </div> <!-- content -->

        <footer class="footer text-right">
            2019 © Casinobit.
        </footer>

    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.toggle-change').click(function () {
                $(this).parent().find('form').toggle();
            });
        });
    </script>
@endsection