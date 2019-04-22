@extends('layouts.app')


@section('title')
    {{translate('Reset password')}}
@endsection


@section('content')
    <div class="cabinet-block" style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry">
            <div class="main-content">

                <div class="page-heading unbordered">
                    <h1 class="page-title">{{translate('Reset password')}}</h1>
                </div>
                <div class="main-content-entry">
                    <div class="setting-entry">
                        <div class="setting-tabs needShow">
                            <div id="tabs-1" class="ui-tabs-panel">

                                <form method="POST" action="{{ url('/password/reset') }}">
                                    {{csrf_field()}}

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="row">
                                        <div class="col-sm-12">

                                            <div class="single-section">
                                                <h3 class="section-title">{{translate('Reset password')}}</h3>
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td><span class="text">{{translate('E-mail')}}</span></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="field-block">
                                                                        <input type="text" name="email" placeholder="{{translate('E-mail')}}"  value="{{ $email or old('email') }}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="text">{{translate('Password')}}</span></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="field-block">
                                                                        <input type="text" name="password" placeholder="{{translate('Password')}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="text">{{translate('Password confirmation')}}</span></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="field-block">
                                                                        <input type="text" name="password_confirmation" placeholder="{{translate('Password confirmation')}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="btn-block">
                                                <button class="update-btn">{{translate('RESTORE')}}</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="setting-accordion">
                            <h3 class="setting-title">{{translate('Change Password')}}</h3>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-refresh"></i> Reset Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
