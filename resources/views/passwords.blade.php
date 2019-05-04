@extends('layouts.app')

@section('title')
    {{ trans('casino.settings') }}
@endsection

@section('content')
<div class="cabinet-block 1" style="background: #000 url('/media/images/bg/deposit-bg-dark.jpg') center no-repeat; background-size: cover;">
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
            <div class="userBalanceWrap">
                    <i class="bitcoin-icon"></i>
                    <div class="userBalanceCol leftBorder">
                        <span class="userBalanceTxt">{{ trans('casino.balance') }}</span>
                        <p class="balancebox-getbalance">{{Auth::user()->getBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    <div class="userBalanceCol leftBorder">
                        <span class="userBalanceTxt">{{ trans('casino.real_balance') }}</span>
                        <p class="balancebox-getrealbalance">{{Auth::user()->getRealBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    
                    <div class="userBalanceCol">
                        <span class="userBalanceTxt">{{ trans('casino.bonus_balance') }}</span>
                        <p class="balancebox-getbonusbalance">{{Auth::user()->getBonusBalance()}} m{{strtoupper(Auth::user()->currency->title)}}</p>
                    </div>
                    <a class="add-credits-btn AddCreditBtn" href="{{route('deposit', ['lang' => $currentLang])}}"><span
                                        class="text">{{ trans('casino.add_credits') }}</span></a>
            </div>
            <div class="main-content-entry">
                <div class="setting-entry">
                    <div class="setting-tabs">
                        <ul>
                            <li><a href="#tabs-1">PIN-code</a></li>
                            <li><a href="#tabs-2">{{ trans('casino.change_password') }}</a></li>
                            <li><a href="#tabs-3">{{ trans('casino.confirm_email') }}</a></li>
                        </ul>
                        <div id="tabs-1">
                            <!-- form add pin code  -->
                            <div class="middle-block" style="display: none">
                                <form action="" method="POST">               
                                    <span class="text">Add pin code</span>
                                    <input type="text" name="#">  
                                
                                    <span class="text">Repeat pin code</span>
                                    <input type="text" name="#">

                                    <button class="pinCode">save</button>
                                </form>
                            </div>
                             <!-- end form add pin code  -->


                            <!-- form change pin code  -->
                            <div class="middle-block" style="display: block">
                                <form action="" method="POST">  
                                                 
                                    <span class="text">Current Pin code</span>
                                    <input type="text" name="#">
                                
                                    <span class="text">New pin code</span>
                                    <input type="text" name="#">

                                    <span class="text">Repeat pin code </span>
                                    <input type="text" name="#">

                                    <button class="pinCode">save</button>
                                </form>
                            </div>
                             <!-- form change pin code  -->
                        </div>
                        <div id="tabs-2">
                            <div class="middle-block">
                                <form action="{{ route('password')}} " method="POST">
                                    {{ csrf_field() }}
                               
                                        <span class="text">{{ trans('casino.old_password') }}</span>
                                                   
                                                              
                                        <input type="text" name="old_password">
                                          
                                        <span class="text">{{ trans('casino.new_password') }}</span>
                                                    
                                        <input type="text" name="password">
                                                     
                                        <span class="text">{{ trans('casino.confirm_password') }}</span>
                                                   
                                        <input type="text" name="password_confirmation">
                                                         
                                        <button class="pinCode">{{ mb_convert_case(trans('casino.update') , MB_CASE_UPPER) }}</button>
                                    
                                </form>
                            </div>
                        </div>

                        <div id="tabs-3">
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

<footer class="footer footer-home">
        <div class="bitcoin-block">
            <span class="bitcoin-msg"><i class="bitcoin-icon"></i> We work only with bitcoin</span>
        </div>
        <div class="msg-block">
            <span class="msg">{{ trans('casino.do_you_want_to_play') }}</span>
        </div>
        <div class="games-listing-block">
            <ul class="games-listing">
                @include('footer_links')
            </ul>
        </div>
        <div class="footer-copyrights">
            <ul class="footerLinks">
                <li class="rightReservedTxt">© All rights reserved</li>
                <li><a href="{{$partnerPage}}" class="afiliate" target="_blank">{{ trans('casino.affiliates') }}</a></li>
                <li><a target="_blank" href="{{route('support', ['lang' => $currentLang])}}" class="support">{{ trans('casino.frq') }}</a></li>
                <li><a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a></li>
                <li><a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a></li>
            </ul>
        </div>
        
    </footer>
     <div class="hidden">
        <div id="uls">
            {!! trans('casino.bonus.term') !!}
        </div>
    </div>

    <div class="hidden">
        <div id="reg-terms">
            {!! trans('casino.terms_conditions') !!}
        </div>
    </div>
@endsection