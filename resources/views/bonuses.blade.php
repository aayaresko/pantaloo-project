@extends('layouts.app')

@section('title')
    {{ trans('casino.bonuses') }}
@endsection


@section('content')
    <div class="cabinet-block act page-bonuses pageBonus"
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
                                        <a href="{{route('bonus.activate', '1')}}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.bonus_terms') }}</a>
                                </div>
                            </div>
                        </section>
                        <div class="bonusOverlay">
                            <div class="icon"></div>
                            <h3>Temporarily unavailable</h3>
                            <a href="#uls" class="usl-link">{{ trans('casino.bonus_terms') }}</a>
                        </div> 
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
                                        <a href="{{route('bonus.activate', '2')}}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.bonus_terms') }}</a>
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
                                        <a href="{{route('bonus.activate', '3')}}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.bonus_terms') }}</a>
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
                                        <a href="{{route('bonus.activate', '4')}}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link">{{ trans('casino.bonus_terms') }}</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @include('footer_main')
@endsection