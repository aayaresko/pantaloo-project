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
                    <form action="login/process" method="post" role="form" style="display: block;">
                        <div class="form-group">
                            <input type="email" name="email" tabindex="1" class="form-control" placeholder="Enter your email" value="">
                        </div>
                        <div class="form-group">
                            <input type="password" name="new_password" tabindex="1" class="form-control" placeholder="Enter the new password" value="">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="btn btn-custom btn-lg page-scroll" style="margin-top: 13px;padding: 5px 15px; width: 100%;" value="Restore">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection