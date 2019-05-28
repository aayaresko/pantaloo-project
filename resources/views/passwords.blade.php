@extends('layouts.app')

@section('title', trans('casino.settings'))


@section('content')
<div class="cabinet-block" style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
    <div class="cabinet-entry">
        <div class="main-content">
            <div class="credits-block">
                <i class="bitcoin-icon"></i>
                <span class="balance"><span class="value">{{Auth::user()->getBalance()}}</span> {{ trans('casino.credits') }}</span>
                <a class="add-credits-btn" href="{{ route('deposit', ['lang' => $currentLang]) }}"><span class="text">{{ trans('casino.add_credits') }}</span></a>
            </div>
            <div class="page-heading unbordered">
                <h1 class="page-title">{{ trans('casino.settings') }}</h1>
            </div>
            <div class="main-content-entry">
                <div class="setting-entry">
                    <div class="setting-tabs">
                        <ul>
                            <li><a href="#tabs-1">{{ trans('casino.change_password') }}</a></li>
                            <li><a href="#tabs-2">{{ trans('casino.confirm_email') }}</a></li>
                        </ul>
                        <div id="tabs-1">
                            <form action="{{ route('password')}} " method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="single-section">
                                            <h3 class="section-title">{{ trans('casino.change_password') }}</h3>
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td><span class="text">{{ trans('casino.old_password') }}</span></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="field-block">
                                                                    <input type="text" name="old_password" placeholder="{{ trans('casino.old_password') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text">{{ trans('casino.new_password') }}</span></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="field-block">
                                                                    <input type="text" name="password" placeholder="{{ trans('casino.password') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text">{{ trans('casino.confirm_password') }}</span></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="field-block">
                                                                    <input type="text" name="password_confirmation" placeholder="{{ trans('casino.confirmation') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="btn-block">
                                            <button class="update-btn">{{ mb_convert_case(trans('casino.update') , MB_CASE_UPPER) }}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div id="tabs-2">
                            @if(Auth::user()->isConfirmed())
                                Email confirmed
                            @else
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="single-section">
                                            <h3 class="section-title">{{ trans('casino.email_confirmation') }}</h3>
                                            <form method="POST" action="{{ route('email.confirm') }}">
                                                {{ csrf_field() }}
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td><span class="text">{{trans('casino.email')}}</span></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="field-block email">
                                                                        <input type="email" name="email" placeholder="{{ trans('casino.email') }}" value="{{Auth::user()->email}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="btn-block">
                                                    <button class="update-btn">{{trans('casino.send_mail')}}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="setting-accordion">
                        <h3 class="setting-title">{{ trans('casino.change_password') }}</h3>
                        <div>
                            <form action="{{ route('password') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="single-section">
                                            <h3 class="section-title">{{ trans('casino.change_password') }}</h3>
                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td><span class="text">{{ trans('casino.old_password') }}</span></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="field-block">
                                                                    <input type="text" name="old_password" placeholder="{{ trans('casino.old_password') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text">{{ trans('casino.new_password') }}</span></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="field-block">
                                                                    <input type="text" name="password" placeholder="{{ trans('casino.password') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><span class="text">{{ trans('casino.confirm_password') }}</span></td>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="field-block">
                                                                    <input type="text" name="password_confirmation" placeholder="{{ trans('casino.confirmation') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="btn-block">
                                            <button class="update-btn">{{trans('UPDATE')}}</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <h3 class="setting-title">{{trans('Confirm email')}}</h3>
                        <div>
                            @if(Auth::user()->isConfirmed())
                                Email confirmed
                            @else
                                <div class="row">
                                    <div class="col-sm-12">

                                        <div class="single-section">
                                            <h3 class="section-title">{{trans('Email confirmation')}}</h3>
                                            <form method="POST" action="{{route('email.confirm')}}">
                                                {{csrf_field()}}
                                                <table>
                                                    <tbody>
                                                    <tr>
                                                        <td><span class="text">{{trans('Email')}}</span></td>
                                                        <td>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="field-block email">
                                                                        <input type="email" name="email" placeholder="{{trans('Email')}}" value="{{Auth::user()->email}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                <br>
                                                <div class="btn-block">
                                                    <button class="update-btn">{{ mb_convert_case(trans('casino.update') , MB_CASE_UPPER) }}</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('settings')
    </div>
</div>
</div>

 @include('footer_main')
@endsection