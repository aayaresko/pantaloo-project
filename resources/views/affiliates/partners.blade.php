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
                        My current commission: {{$myKoef}}
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
                                    <th>actions</th>
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
                <div class="row">
                    <div class="col-md-12">
                        <h4>Profits table</h4>
                    </div>
                    <div class="col-lg-12">
                        <div class="card-box">
                            <table class="table table-bordered">
                                <thead>
                                <tr role="row">
                                    <th width="50">My Percent</th>
                                    <th width="150">From</th>
                                    <th></th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($from = 0)
                                @foreach(Auth::user()->allKoefs as $koef)
                                    @php($to = $from) @php($from = $koef->created_at)
                                    <tr role="row">
                                        <td>{{$koef->koef}}</td>
                                        <td>{{$koef->created_at->format('Y.m.d')}}</td>
                                        <td>
                                            <table class="table" >
                                                <tr>
                                                    <th>Partner</th>
                                                    <th></th>
                                                </tr>
                                                @foreach($affiliates as $affiliate)
                                                <tr>
                                                    <td>{{$affiliate->email}}</td>
                                                    <td>
                                                        <table class="table" >
                                                            <tr>
                                                                <th>Partner %</th>
                                                                <th>From - to</th>
                                                                <th>Benefit</th>
                                                                <th>Profit</th>
                                                            </tr>
                                                            @php($affiliateFrom = 0)
                                                            @foreach($affiliate->allKoefs as $partnerKoef)
                                                                @php($affiliateTo = $affiliateFrom) @php($affiliateFrom = $partnerKoef->created_at->format('Y-m-d'))
                                                                @if($affiliateTo and $affiliateTo < $from) @continue @endif
                                                                <tr>
                                                                    <td>{{$partnerKoef->koef}}</td>
                                                                    <td>{{$affiliateFrom}} - {{$affiliateTo ?: 'now'}}</td>
                                                                    <td>1000</td>
                                                                    <td>1000</td>
                                                                </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
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
        });
    </script>
@endsection