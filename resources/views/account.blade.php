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
                    <h1 class="page-title">{{ trans('casino.account_title')}}</h1>
                </div>

                @include('main_parts.header_account')

                <div class="main-content-entry">
                    <div class="withdraw-entry">
                        <div class="middle-block">
                            <div class="accountFormWrapper">
                                <form id="userDataForm">
                                    <div class="halfCol">
                                    <label>{{ trans('casino.account_first_name') }}<span>*</span></label>
                                    <input type="text" name="first_name">
                                    </div>
                                    
                                    <div class="halfCol">
                                        <label>{{ trans('casino.account_last_name') }}</label> 
                                        <input type="text" name="last_name">
                                    </div>
                                    <div class="fullCol">
                                        <label>{{ trans('casino.date_of_birth') }}<span>*</span></label>  
                                        <select id="days" name="day"></select>
                                        <select id="months" name="month"></select>
                                        <select id="years" name="year"></select>
                                    </div>
                                    <div class="fullCol flexStart">
                                        <p class="genderTxt">{{ trans('casino.account_gender') }}</p>
                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="male" name="gender" id="gender1"> 
                                            <label for="gender1">{{ trans('casino.account_male') }}</label>
                                        </div>

                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="female" name="gender" id="gender2"> 
                                            <label for="gender2">{{ trans('casino.account_female') }}</label>
                                        </div>
                                    </div>
                                    <div class="halfCol">
                                        <label for="country">{{ trans('casino.account_country') }}:<span>*</span></label>
                                        <!-- <select name="country" id="country">Country</select> -->
                                        <input type="text" id="country">
                                    </div>
                                    
                                    <div class="halfCol">
                                        <label for="city">{{ trans('casino.account_city') }}:</label> 
                                        <input type="text" name="city" id="city">
                                    </div>
                                    <div class="fullCol">
                                        <label>{{ trans('casino.account_email') }}:</label>
                                        <div class="emailWrapper confirmd">
                                            <input type="email" name="email" placeholder="{{ trans('casino.email') }}" value="{{Auth::user()->email}}">         
                                            <a href="{{route('settings', ['lang' => $currentLang])}}" class="editEmailBtn">{{ trans('casino.account_edit') }}</a>
                                        </div>
                                    </div>

                                    <button class="updateUserDataBtn">
                                    {{ trans('casino.account_update') }}
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

    <div class="alertWrapper">
        <div class="alertText">Text from response</div>
    </div>

    <!-- <div class="alertWrapper error">
        <div class="alertText">Message text info</div>
    </div> -->

@endsection


@section('js')



@endsection