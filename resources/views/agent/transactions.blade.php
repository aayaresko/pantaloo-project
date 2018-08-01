@extends($is_admin ? 'layouts.admin':'layouts.agent')

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
                                <div class="col-sm-4">
                                <select name="user_id" class="selectpicker" data-live-search="true">
                                    <option name="0">Users / All</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->email}}</option>
                                    @endforeach
                                </select>
                                </div>

                                <div class="col-sm-4">
                                    <select name="category_id" class="selectpicker" data-live-search="true">
                                        <option name="0">Providers / All</option>
                                        @foreach(\App\Category::all() as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-sm-4">
                                    <select name="type_id" class="selectpicker" data-live-search="true">
                                        <option value="0">Transaction type / All</option>
                                        <option value="-1">Bet + Win</option>
                                        <option value="1">Bet</option>
                                        <option value="2">Win</option>
                                        <option value="3">Deposit</option>
                                        <option value="4">Withdraw</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <br>
                                <div class="col-sm-12">
                                    <table class="table table-striped table-bordered dataTable no-footer datatable" role="/rid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">User</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Date</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Description</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Amount</th>
                                            <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Bonus</th>
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
@endsection

@section('js')
<script>
$(document).ready(function () {
    //$('.selectpicker').selectpicker();

    var user_id = getUrlParameter('user_id');

    if(user_id)
        $('select[name="user_id"]').val(user_id);

    var oTable = $('.datatable').dataTable( {
        "bProcessing": true,
        "bServerSide": true,
        "searching": false,
        "sAjaxSource": "@if($is_admin){{route('admin.filterTransactions')}}@else{{route('agent.filterTransactions')}}@endif",
        "fnServerParams": function (aoData) {
            aoData.push( {name: "user_id", value: $('select[name="user_id"]').val() } );
            aoData.push( {name: "category_id", value: $('select[name="category_id"]').val() } );
            aoData.push( {name: "type_id", value: $('select[name="type_id"]').val() } );
        }
    });

    $('.selectpicker').change(function () {
        oTable.fnDraw();
    });
});

</script>

@endsection
