@extends('layouts.partner')

@section('title')
    Reset Password
@endsection

@section('content')
    <div class="reset_password">
        <div class="container">
            <div class="section-title">
                <h2>Reset password</h2>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <form id = "reset-password-finish-form" action="/affiliates/password/reset" method="post" role="form" style="display: block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="form-group">
                            <input type="email" name="email" tabindex="1" class="form-control" placeholder="Enter your email" value="{{ $email ?? old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" tabindex="1" class="form-control" placeholder="Enter the new password" value="" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password_confirmation" tabindex="1" class="form-control" placeholder="Confirm Password" value="" required>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input type="submit" name="login-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;" value="Restore">
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="error-lists" style="display: none">
                        <ul class="error-lists">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection