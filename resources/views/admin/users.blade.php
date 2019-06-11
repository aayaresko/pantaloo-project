@extends('layouts.admin')

@section('title')
    Users
@endsection

@section('content')
    <div class="content-page">
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <form action="" class="form-inline">
                        <div class="col-sm-4">
                            @php
                                $emailDefault = isset($filterData['email']) ? $filterData['email'] : '';
                                $roleDefault = isset($filterData['role']) ? $filterData['role'] : null;
                            @endphp
                            <select name="role" class="selectpicker" data-live-search="true">
                                <option name="0" value= 'all'>All Types</option>
                                <option name="0" value= 'allTest'>All Test Types</option>
                                @foreach($userTypes as $userType)
                                    <option value="{{ $userType['key'] }}" {{ $roleDefault ===  $userType['key'] ? 'selected' : ''}}>
                                        {{ $userType['name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <input type="text" name="email" class="form-control" placeholder="email filter" value="{{ $emailDefault }}">
                            <button type="submit" class="btn btn-success">Search</button>
                        </div>

                    </form>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <table class="table table-striped table-bordered dataTable no-footer datatable" role="/rid" aria-describedby="datatable_info">
                                <thead>
                                    <tr role="row">
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">#</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">E-mail</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Country</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Role</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Status</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Activity</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Balance, mBtc</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Date</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Transactions</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Bonus</th>
                                        <th class="sorting" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->getCountry()}}</td>
                                            <td>
                                                @if($user->role == 0)
                                                    <span class="label label-primary">User</span>
                                                @elseif($user->role == 1)
                                                    <span class="label label-warning">Affiliate</span>
                                                @else
                                                    <span class="label label-danger">Admin</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->isOnline()) <span class="label label-success">Online</span>
                                                @else <span class="label label-danger">Offline</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->last_activity)
                                                    <span style="display: none;">{{$user->last_activity->format('U')}}</span>
                                                    {{$user->last_activity->diffForHumans()}}
                                                    @else
                                                    Never
                                                @endif
                                            </td>
                                            <td>{{$user->getBalance()}}</td>
                                            <td>{{$user->created_at->format('d.m.Y H:i')}}</td>
                                            <td><a href="{{route('admin.transactions', ['user_id' => $user->id])}}" class="btn btn-warning">Transactions</a></td>
                                            <td><a href="{{route('admin.bonuses', $user)}}" class="btn btn-info">Bonus</a></td>
                                            <td>

                                                <a class="modal_href btn btn-primary waves-effect waves-light m-r-5 m-b-10 btn-xs" data-modal_id="{{$user->id}}">Edit</a>
                                                <div id="modal_{{$user->id}}" class="modal-demo">
                                                    <button type="button" class="close" onclick="Custombox.close();">
                                                        <span>&times;</span><span class="sr-only">Close</span>
                                                    </button>
                                                    <h4 class="custom-modal-title">Edit</h4>
                                                    <div class="custom-modal-text">
                                                        <table class="table table-striped m-0">
                                                            <form method="POST" action="{{route('user.update', $user)}}">
                                                                {{csrf_field()}}
                                                                <h4>User</h4>
                                                                {{$user->email}}
                                                                <h4>Confirmation required</h4>
                                                                <select name="confirmation_required" class="form-control">
                                                                    <option value="0" @if($user->confirmation_required == 0) selected @endif>No</option>
                                                                    <option value="1" @if($user->confirmation_required == 1) selected @endif>Yes</option>
                                                                </select>
                                                                <h4>Role</h4>
                                                                @if($user->role == 2)
                                                                    <span class="label label-danger">Admin</span>
                                                                    <br>
                                                                @else
                                                                    <select name="role" class="form-control">
                                                                        @foreach($userTypes as $role)
                                                                            @if($role['key'] == $user->role) <option value="{{$role['key']}}" selected>{{$role['name']}}</option>
                                                                            @else <option value="{{$role['key']}}">{{$role['name']}}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>

                                                                    <h4>Commission</h4>
                                                                    <input type="text" name="commission" value="{{$user->commission}}" class="form-control" style="text-align: center;">

                                                                    <h4>Confirm Email
                                                                        <input type="checkbox" name="email_confirmed" value = 1 {{ ($user->email_confirmed > 0 ) ? 'checked="checked' : '' }}>
                                                                    </h4>

                                                                    <h4>Block User
                                                                        <input type="checkbox" name="block" value = 1 {{ ($user->block > 0 ) ? 'checked="checked' : '' }}>
                                                                    </h4>
                                                                @endif
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
                    <div class="col-sm-12">{{$users->links()}}</div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('js')

    <script>
        // $('.datatable').dataTable({
        //     "order": [[ 0, "desc" ]],
        //     "pageLength": 100
        // });

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
