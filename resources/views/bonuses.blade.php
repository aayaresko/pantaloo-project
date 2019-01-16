@extends('layouts.app')

@section('title')
    {{translate('Bonus')}}
@endsection

@section('content')
    <div class="cabinet-block act"
         style="background: #000 url('media/images/bg/content-bg.png') center no-repeat; background-size: cover;">
        <div class="actions">
            <div class="container">
                <div class="row">

                    <section class="bookof">
                        <div class="info-spin">
                            <span class="numb">50</span>
                            <span class="info-sub-text">
                                <span class="free">{{ mb_convert_case(trans('casino.free'), MB_CASE_TITLE) }}</span>
                                <span class="sub-text">{{ mb_convert_case(trans('casino.spins'), MB_CASE_TITLE) }}</span>
                            </span>
                            <br>
                            <br>
                            @if(Auth::check())
                                <a href="{{ route('deposit') }}" class="btn-play-action">
                                    <span>{{ trans('casino.deposit_space') }}</span>
                                </a>
                            @else
                                <a href="#"
                                   class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                            @endif
                            <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                        </div>

                        <div class="bonus bonus-{{ app()->getLocale() }}">
                            <div class="text">{{ mb_convert_case(trans('casino.welcome_bonus'), MB_CASE_UPPER) }}</div>
                        </div>
                    </section>

                </div>
            </div>

            <div class="container">
                <div class="row">

                    <div class="col-md-6 npl ac-wrap">
                        <section class="block-bonus block-bonus1 clearfix">
                            <div class="info-block clearfix">

                                <div class="block-money">
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <span class="big">200%</span>
                                </div>

                                <div class="number-block">
                                    <span class="number">1</span>
                                    <span class="lett">st</span>
                                    <span class="descr">Deposit</span>
                                    <div class="desc-bonus">Bonus</div>
                                </div>

                            </div>

                            <div class="btn-play-wrap">
                                @if(Auth::check())
                                    <a href="{{ route('deposit') }}"
                                       class="btn-play-action"><span>{{ trans('casino.deposit_space') }}</span></a>
                                @else
                                    <a href="#"
                                       class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                                @endif
                            </div>
                            <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                        </section>
                    </div>
                    <div class="col-md-6 npr ac-wrap">
                        <section class="block-bonus block-bonus2 clearfix">
                            <div class="info-block info-block2 clearfix">

                                <div class="block-money">
                                    <span class="big">100%</span>
                                </div>

                                <div class="number-block">
                                    {!! translate('
                                    <span class="number">2</span>
                                    <span class="lett">nd</span>
                                    <span class="descr">Deposit</span>
                                    <div class="desc-bonus">Bonus</div>') !!}
                                </div>


                            </div>

                            <div class="btn-play-wrap">
                                @if(Auth::check())
                                    <a href="{{ route('deposit') }}"
                                       class="btn-play-action"><span>{{ trans('casino.deposit_space') }}</span></a>
                                @else
                                    <a href="#"
                                       class="btn-play-action reg-btn"><span>{{ trans('casino.registration') }}</span></a>
                                @endif
                            </div>
                            <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                        </section>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="hidden">
        {!! trans('casino.bonus.term') !!}
    </div>

@endsection