@extends('layouts.agent')


@section('title')
    Users
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        My current commission: {{$myKoef}}
                    </div>
                    <div class="col-lg-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr role="row">
                                    <th>User email</th>
                                    <th>User benefit</th>
                                    <th>User profit</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($users as $user)
                                    <tr role="row">
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->totalSum()}}</td>
                                        <td>{{$user->totalProfit()}}</td>
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