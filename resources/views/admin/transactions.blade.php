@if(count($transactions) > 0)
    <table class="table table-striped table-bordered dataTable no-footer datatable" role="grid" aria-describedby="datatable_info">
        <thead>
        <tr role="row">
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">#</th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Email</th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Reg. date</th>
            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" aria-sort="ascending">Sum</th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Date</th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Balance</th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Transactions</th>
            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Action</th>
        </tr>
        </thead>

        <tbody>
        @foreach($transactions as $transaction)
            <tr role="row">
                <td>{{$transaction->id}}</td>
                <td class="">@if($transaction->email_confirm == 0) <span class="label label-warning">{{$transaction->user->email}}</span>@else {{$transaction->user->email}} @endif</td>
                <td class="">{{$transaction->user->created_at->format('d M Y H:i')}}</td>
                <td class="sorting_1">{{$transaction->sum*(-1)}}</td>
                <td>{{$transaction->created_at->format('d M Y H:i')}}</td>
                <td class="">{{$transaction->user->getBalance()}}</td>
                <td style="">

                    <a class="modal_href btn btn-primary waves-effect waves-light m-r-5 m-b-10 btn-xs" data-modal_id="{{$transaction->id}}" style="margin:0px;padding:0px;">Transactions</a>

                    <div id="modal_{{$transaction->id}}" class="modal-demo">
                        <button type="button" class="close" onclick="Custombox.close();">
                            <span>&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="custom-modal-title">Latest transactions</h4>
                        <div class="custom-modal-text">
                            <table class="table table-striped m-0">
                                <thead>
                                <tr><th>Date</th><th>Sum (mBTC)</th><th>Type</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                @foreach($transaction->user->transactions()->whereIn('type', [3, 4])->orderBy('id', 'DESC')->limit(10)->get() as $user_trans)
                                    <tr><td>{{$user_trans->created_at->format('d M Y H:i')}}</td><td>{{$user_trans->getSum()}}</td><td>{{$user_trans->getType()}}</td><td>{{$user_trans->getAdminStatus()}}</td></tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </td>
                <td>
                    @if($transaction->withdraw_status == 0)
                        <div class="btn-group dropdown">
                            <a type="button" class="btn btn-success waves-effect waves-light btn-xs" href="{{route('aprove', $transaction)}}">Aprove</a>
                            <button type="button" class="btn btn-success dropdown-toggle waves-effect waves-light btn-xs" data-toggle="dropdown" aria-expanded="false"><i class="caret"></i></button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{route('aprove', $transaction)}}">Aprove</a></li>
                                <li><a href="{{route('freeze', $transaction)}}">Freeze</a></li>
                            </ul>
                        </div>
                    @elseif($transaction->withdraw_status == -2)
                        <span class="label label-danger">Error</span>
                    @elseif($transaction->withdraw_status == 1)
                        <span class="label label-success">Complete</span>
                    @elseif($transaction->withdraw_status == -1)
                        <a href="{{route('unfreeze', $transaction)}}" class="btn btn-info waves-effect waves-light btn-xs">Unfreeze</a>
                    @elseif($transaction->withdraw_status == 3)
                        <a href="{{route('cancel', $transaction)}}" class="btn btn-warning waves-effect waves-light btn-xs">Cancel</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <i>Transactions not found</i>
@endif