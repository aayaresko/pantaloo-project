@extends('layouts.admin')

@section('title')
    Affiliate: {{$partner->email}}
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="card">
                    <div class="card-block">
                        @if (!$partner->agent_id)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form action="{{route('admin.agents.makeSuper', $partner->id)}}" method="post" class="form-inline">
                                        <select name="country[]" id="select2Country" multiple="multiple">
                                            @foreach(App\Country::all() as $country)
                                                <option value="{{$country->id}}" @if(in_array($country->id, $countriesIds)) selected @endif>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-success">Make as superaffiliate</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if ($partner->role == 1)
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form action="{{route('admin.agents.setAffiliate', $partner->id)}}" method="post" class="form-inline">
                                        <select name="affiliate" class="form-control">
                                            <option value="">Select parent affiliate</option>
                                            @foreach($superAffiliates as $affiliate)
                                                <option value="{{$affiliate->id}}" @if($affiliate->id == $partner->agent_id)) selected @endif>{{$affiliate->email}}</option>
                                            @endforeach
                                        </select>
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-success">Set parent</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <form action="{{route('admin.agents.setPercent', $partner->id)}}" method="post" class="form-inline">
                                        <input type="number" class="form-control" name="koef" value="{{$partner->koefs->koef}}">
                                        {{csrf_field()}}
                                        <button type="submit" class="btn btn-success">Set percent</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card-box">
                                    <h3>User table</h3>
                                    <table class="table table-striped table-bordered" id="userTable">
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
@section('js')
    <script>
        $('#select2Country').select2({
            placeholder: 'Select countries'
        });
        $('#userTable').DataTable();
    </script>
@endsection
