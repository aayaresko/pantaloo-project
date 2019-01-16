@extends('layouts.agent')

@section('title')
    Transactions
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="row">
                                <div class="col-sm-3">
                                    <select name="user_id" class="selectpicker" data-live-search="true">
                                        <option value="0" selected>Users / All</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->email}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <select name="category_id" class="selectpicker" data-live-search="true">
                                        <option value="0" selected>Providers / All</option>
                                        @foreach($gamesCategories as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <select name="type_id" class="selectpicker" data-live-search="true">
                                        @foreach($types as $key => $type)
                                            @if($type->filter === 1)
                                                @if ($key === 0)
                                                    <option value="{{$type->code}}" selected>{{$type->value}}</option>
                                                @else
                                                    <option value="{{$type->code}}">{{$type->value}}</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <select name="tracker_id" class="selectpicker" data-live-search="true">
                                        <option value="0" selected>Campaign name / All</option>
                                        @foreach($trackers as $tracker)
                                            <option value="{{$tracker->id}}">{{$tracker->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <br>
                                <div class="col-sm-12">
                                    <div class="table-wrap">
                                        <table class="table table-striped table-bordered dataTable no-footer datatable"
                                            role="/rid" aria-describedby="datatable_info">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                    colspan="1">User ID
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                    colspan="1">Date
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                    colspan="1">Description
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                    colspan="1">Amount
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                    colspan="1">Bonus
                                                </th>
                                                <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1"
                                                    colspan="1">Campaign Name
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
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
        let transactionRoute = "{{route('agent.filterTransactions')}}";
    </script>
    <script src="/partnerPanel/js/page/transactionPartnerPanel.js?v={{ time() }}"></script>
@endsection
