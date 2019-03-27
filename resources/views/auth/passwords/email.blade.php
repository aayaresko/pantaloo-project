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
                        <div class="setting-tabs">
                            <div id="tabs-1" class="ui-tabs-panel">

                                <form method="POST" action="{{ url("/{$currentLang}/password/email") }}">
                                    {{csrf_field()}}
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
                                                                        <input type="text" name="email" placeholder="{{translate('E-mail')}}">
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
