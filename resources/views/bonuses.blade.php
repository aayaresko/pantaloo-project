@extends('layouts.app')

@section('title')
    {{ trans('casino.bonuses') }}
@endsection


@section('content')
    <div class="cabinet-block act page-bonuses"
         style="background: #000 url('/media/images/bg/content-bg.png') center no-repeat; background-size: cover;">
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
                                    <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
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

    <div class="hidden">
        <div id="uls">
            {!! trans('casino.bonus.term') !!}
        </div>
    </div>

@endsection