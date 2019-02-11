@extends('layouts.admin')

@section('title')
    Affiliates
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered dataTable no-footer datatable" role="grid" aria-describedby="datatable_info">
                                <thead>
                                <tr role="row">
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">#</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Affiliate</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Users</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Procent</th>
                                    <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Next payment</th>
                                    <th>Edit</th>
                                </tr>
                                </thead>

                                <tbody>
                                @foreach($agents as $agent)
                                    <tr role="row">
                                        <td>{{$agent['agent']->id}}</td>
                                        <td class="">{{$agent['agent']->email}}</td>
                                        <td class=""><span class="label label-success">{{$agent['users']}}</span></td>
                                        <td class="">{{$agent['agent']->commission}} %</td>
                                        <td>{{round($agent['available'], 2)}} mBtc</td>
                                        <td class="">
                                            <a class="modal_href btn btn-primary waves-effect waves-light m-r-5 m-b-10 btn-xs" data-modal_id="{{$agent['agent']->id}}">Edit</a>
                                            <div id="modal_{{$agent['agent']->id}}" class="modal-demo">
                                                <button type="button" class="close" onclick="Custombox.close();">
                                                    <span>&times;</span><span class="sr-only">Close</span>
                                                </button>
                                                <h4 class="custom-modal-title">Edit</h4>
                                                <div class="custom-modal-text">
                                                    <table class="table table-striped m-0">
                                                        <form method="POST" action="{{route('admin.agentCommission', $agent['agent'])}}">
                                                            {{csrf_field()}}
                                                            <h4>Affiliate</h4>
                                                            {{$agent['agent']->email}}
                                                            <h4>Commission</h4>
                                                            <input type="text" name="commission" value="{{$agent['agent']->commission}}" class="form-control" style="text-align: center;">
                                                            <br>
                                                            <input type="submit" name="save" value="Save" class="btn btn-success">
                                                        </form>
                                                    </table>
                                                </div>
                                            </div>
                                        </td>
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
@endsection

@section('js')
    <script>
        $('.datatable').dataTable();

        $(function() {
            $('body').on('click', '.modal_href',function( e ) {

                Custombox.open({
                    target: '#modal_' + $(this).data('modal_id'),
                    effect: 'fadein'
                });
                e.preventDefault();
            });
        });
    </script>
@endsection