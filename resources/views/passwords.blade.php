@extends('layouts.app')

@section('title')
    {{ trans('casino.settings') }}
@endsection

@section('content')
<div class="cabinet-block 1" style="background: #000 url('/media/images/bg/deposit-bg-light.jpg') center no-repeat; background-size: cover;">
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
                            <li><a href="#tabs-1">{{ trans('casino.pin_code') }}</a></li>
                            <li><a href="#tabs-2">{{ trans('casino.change_password') }}</a></li>
                            <li><a href="#tabs-3">{{ trans('casino.confirm_email') }}</a></li>
                        </ul>
                        <div id="tabs-1">
                            <!-- form add pin code  -->
                            <div class="middle-block" style="display: none">
                                <form action="#" method="POST">               
                                    <span class="text">{{ trans('casino.add_pin_code') }}</span>
                                    <input type="text" name="add_pin_code">  
                                
                                    <span class="text">{{ trans('casino.repeat_pin_code') }}</span>
                                    <input type="text" name="repeat_code">

                                    <button class="pinCode">{{ trans('casino.save_pin_code') }}</button>
                                </form>
                            </div>
                             <!-- end form add pin code  -->


                            <!-- form change pin code  -->
                            <div class="middle-block" style="display: block">
                                <form action="#" method="POST">  
                                                 
                                    <span class="text">{{ trans('casino.current_pin_code') }}</span>
                                    <input type="text" name="current_pin_code">
                                
                                    <span class="text">{{ trans('casino.new_pin_code') }}</span>
                                    <input type="text" name="new_pin_code">

                                    <span class="text">{{ trans('casino.repeat_pin_code') }}</span>
                                    <input type="text" name="repeat_code">

                                    <button class="pinCode">{{ trans('casino.save_pin_code') }}</button>
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
                            <div class="middle-block">
                            @if(Auth::user()->isConfirmed())
                                <!-- Email confirmed -->
                                <div class="emailWrapper confirmd">
                                    ivanov.alexander@gmail.com
                                </div>
                                <p class="emailConfInfo">
                                    {{ trans('casino.confirmed_email') }}
                                </p>
                                <p class="supportContact">
                                    {{ trans('casino.contact_support_intro') }}
                                    <a href="#">{{ trans('casino.contact_support') }}</a>
                                </p>

                               <!-- end Email confirmed -->
                            @else

                            <!-- Email not confirmed -->


                            <form method="POST" action="{{ route('email.confirm') }}">
                                    {{ csrf_field() }}
                                    <div class="emailWrapper notConfirmd">
                                        <input type="email" name="email" placeholder="{{ trans('casino.email') }}" value="{{Auth::user()->email}}">         
                                    </div>
                                    <p class="emailConfInfo">
                                    {{ trans('casino.not_confirmed_email') }} <span>{{ trans('casino.receive_email') }} ?</span><button class="update-btn">{{trans('casino.send_mail')}}</button>
                                    </p>
                                    <p class="supportContact">  {{ trans('casino.contact_support_intro') }}
                                        <a href="#">{{ trans('casino.contact_support') }}</a>
                                    </p>
                                </form>
    
                            <!-- end email not confirmed --> 
                                       
                            @endif
                      
                            </div>
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