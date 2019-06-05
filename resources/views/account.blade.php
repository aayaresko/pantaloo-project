@extends('layouts.app')

@section('title')
    {{translate('Withdraw')}}
@endsection


@section('content')

<div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-dark.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry cabinetMod">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">Account</h1>
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
                    <div class="withdraw-entry">
                        <div class="middle-block">
                            <div class="accountFormWrapper">
                                <form action="">
                                    <div class="halfCol">
                                    <label>First name:<span>*</span></label>
                                    <input type="text" name="first_name">
                                    </div>
                                    
                                    <div class="halfCol">
                                        <label>Last name:</label> 
                                        <input type="text" name="last_name">
                                    </div>
                                    <div class="fullCol">
                                        <label>Date of Birth: <span>*</span></label> 
                                        <select id="days" name="day"></select>
                                        <select id="months" name="month"></select>
                                        <select id="years" name="year"></select>
                                    </div>
                                    <div class="fullCol flexStart">
                                        
                                        <p class="genderTxt">Gender:</p>
                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="male" name="gender" id="gender1"> 
                                            <label for="gender1">Male</label>
                                        </div>

                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="female" name="gender" id="gender2"> 
                                            <label for="gender2">Female</label>
                                        </div>
                                    </div>
                                    <div class="halfCol">
                                        <label for="country">Country:<span>*</span></label>
                                        <!-- <select name="country" id="country">Country</select> -->
                                        <input type="text" id="country">
                                    </div>
                                    
                                    <div class="halfCol">
                                        <label for="city">City:</label> 
                                        <input type="text" name="city" id="city">
                                    </div>
                                    <div class="fullCol">
                                        <label for="">Email:</label>
                                        <div class="emailWrapper confirmd">
                                            <input type="email" name="email" placeholder="{{ trans('casino.email') }}" value="{{Auth::user()->email}}">         
                                            <a href="{{route('settings', ['lang' => $currentLang])}}" class="editEmailBtn">Edit</a>
                                        </div>
                                    </div>

                                    <button class="updateUserDataBtn">
                                        update
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('settings')
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


@section('js')



@endsection