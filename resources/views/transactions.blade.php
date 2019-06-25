@if(count($transactions) > 0)
    <table class="withdraws" data-max_id="{{$transactions[0]->id}}">
        <thead>
        <tr>
            <th>{{translate('Date and Time')}}</th>
            <th>{{translate('Transaction ID')}}</th>
            <th>{{translate('Status')}}</th>
            <th>{{translate('Amount mBTC')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            <tr id="txid_{{$transaction->id}}">
                <td>{{$transaction->created_at->format(trans('date.action_deposit'))}}</td>
                <td>{{$transaction->id}}</td>
                <td>{{translate($transaction->getStatus())}}</td>
                <td>{{$transaction->getSum()}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif