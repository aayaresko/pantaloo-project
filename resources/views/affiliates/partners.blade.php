@extends('layouts.agent')


@section('title')
    Partners
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        My current <a href="#" class="show-history_percent" title="percent history">commission:</a> {{$myKoef}}
                        <ul id="show-history_percent" style="display: none">
                            @foreach(Auth::user()->allKoefs as $koef)
                            <li>{{$koef->koef}} ({{$koef->created_at->format('d.m.Y')}})</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-box">
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
                                    <th>actions</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($affiliates as $affiliate)
                                    <tr role="row">
                                        <td>{{$affiliate->id}}</td>
                                        @if(auth()->user()->role == 3)
                                        <td><a href="{{route('agent.affiliates.show', $affiliate->id)}}">{{$affiliate->email}}</a></td>
                                        @else
                                        <td>{{$affiliate->email}}</td>
                                        @endif
                                        <td>{{$affiliate->koefs->koef}}</td>
                                        <td>{{$affiliate->playersCount()}}</td>
                                        <td>{{$affiliate->agentsCount()}}</td>
                                        <td>{{$affiliate->playersTotalCount()}}</td>
                                        <td>{{$affiliate->benefits->sum('total_sum')}}</td>
                                        <td>{{$affiliate->allBenefits()}}</td>
                                        <td>{{$affiliate->profit()}}</td>
                                        <td>{{$affiliate->totalProfit()}}</td>
                                        <td>
                                            <a href="#" class="toggle-change">change</a>
                                            <form action="{{route('agent.change.koef', $affiliate->id)}}" method="post" style="display:none">
                                                <input type="number" max="{{$myKoef}}" name="koef" value="{{$affiliate->koefs->koef}}">
                                                {{csrf_field()}}
                                                <button type="submit">Save</button>
                                            </form>
                                        </td>
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
            $('.show-history_percent').click(function () {
                $('#show-history_percent').toggle();
            })
        });
    </script>
@endsection