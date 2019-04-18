@extends('layouts.app')

@section('title')
    {{ trans('casino.bonuses') }}
@endsection


@section('content')
    <div class="cabinet-block act page-bonuses"
         style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="actions">

            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-sm-6 npl ac-wrap">
                        <section class="block-bonus block-bonus1 clearfix">

                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-1-box.jpg') }}" alt="" />
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ route('deposit', ['lang' => $currentLang]) }}"
                                           class="btn-play-action"><span>{{ trans('casino.deposit_space') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.bonus_terms') }}</a>
                                </div>
                            </div>

                        </section>
                    </div>

                    <div class="col-md-6 col-sm-6 npr ac-wrap">
                        <section class="block-bonus block-bonus2 clearfix">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-2-box.jpg') }}" alt="" />
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ route('deposit', ['lang' => $currentLang]) }}"
                                           class="btn-play-action"><span>{{ trans('casino.deposit_space') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-6 npl ac-wrap">
                        <section class="block-bonus block-bonus3 clearfix">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-3-box.jpg') }}" alt="" />
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ route('deposit', ['lang' => $currentLang]) }}"
                                           class="btn-play-action"><span>{{ trans('casino.deposit_space') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6 col-sm-6 npr ac-wrap">
                        <section class="block-bonus block-bonus4 clearfix">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-4-box.jpg') }}" alt="" />
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ route('deposit', ['lang' => $currentLang]) }}"
                                           class="btn-play-action"><span>{{ trans('casino.deposit_space') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
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