@extends('layouts.app')

@section('title', trans('casino.bonuses'))



@section('content')
    <div class="cabinet-block act page-bonuses pageBonus"
         style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="actions">
            <div class="page-heading">
                <h1 class="page-title">{{ mb_convert_case(trans('casino.bonuses'), MB_CASE_UPPER) }}</h1>
            </div>
            <div class="container">
                <div class="flexContainer">


                    {{--                    @php--}}
                    {{--                        //fix this foreach!!!!!!!!!!!!--}}
                    {{--                        $bonusId1 = 1;--}}
                    {{--                        $bonus1 = route('bonus.activate', $bonusId1);--}}

                    {{--                        if (!is_null($activeBonus)) {--}}
                    {{--                            $activatedBonus1 = ($activeBonus->bonus_id == $bonusId1) ? 'activatedBonus' : '';--}}
                    {{--                        } else {--}}
                    {{--                            $activatedBonus1 = '';--}}
                    {{--                        }--}}

                    {{--                    @endphp--}}
                    {{--                    <div class="flexChild">--}}
                    {{--                        <section class="block-bonus block-bonus1 clearfix {{ $activatedBonus1 }}">--}}
                    {{--                            <div class="block-bonus-left">--}}
                    {{--                                <div class="block-bonus-image">--}}
                    {{--                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-1-box.jpg') }}" alt=""/>--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}

                    {{--                            <div class="block-bonus-right">--}}
                    {{--                                <div class="block-bonus-buttons">--}}
                    {{--                                    @if(Auth::check())--}}
                    {{--                                        <a href="#uls"--}}
                    {{--                                           class="btn-play-action usl-link"--}}
                    {{--                                           data-bonus-url='{{ $bonus1 }}'><span>{{ trans('casino.activate') }}</span></a>--}}
                    {{--                                    @else--}}
                    {{--                                        <a href="#"--}}
                    {{--                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>--}}
                    {{--                                    @endif--}}
                    {{--                                </div>--}}
                    {{--                            </div>--}}
                    {{--                        </section>--}}
                    {{--                        <!-- <div class="bonusOverlay unavailable">--}}
                    {{--                            <div class="icon"></div>--}}
                    {{--                            <h3>Temporarily unavailable</h3>--}}
                    {{--                        </div> -->--}}

                    {{--                        <div class="bonusOverlay activated">--}}
                    {{--                            <div class="icon"></div>--}}
                    {{--                            <h3>{{ trans('casino.bonus_status') }}</h3>--}}
                    {{--                        </div>--}}
                    {{--                    </div>--}}

                    @php
                        $bonusId2 = 2;
                        $bonus2 = route('bonus.activate', $bonusId2);
                        if (!is_null($activeBonus)) {
                            $activatedBonus2 = ($activeBonus->bonus_id == $bonusId2) ? 'activatedBonus' : '';
                        } else {
                            $activatedBonus2 = '';
                        }
                    @endphp
                    <div class="flexChild">
                        <section class="block-bonus block-bonus2 clearfix {{ $activatedBonus2 }}">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-2-box.jpg') }}" alt=""/>
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="#uls"
                                           class="btn-play-action usl-link"
                                           data-bonus-url='{{ $bonus2 }}'><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif

                                </div>
                            </div>
                        </section>
                        <!-- <div class="bonusOverlay unavailable">
                            <div class="icon"></div>
                            <h3>Temporarily unavailable</h3>
                        </div> -->

                        <div class="bonusOverlay activated">
                            <div class="icon"></div>
                            <h3>{{ trans('casino.bonus_status') }}</h3>
                        </div>
                    </div>

                    @php
                        $bonusId3 = 3;
                        $bonus3 = route('bonus.activate', $bonusId3);
                        if (!is_null($activeBonus)) {
                            $activatedBonus3 = ($activeBonus->bonus_id == $bonusId3) ? 'activatedBonus' : '';
                        } else {
                            $activatedBonus3 = '';
                        }
                    @endphp
                    <div class="flexChild">
                        <section class="block-bonus block-bonus3 clearfix {{ $activatedBonus3 }}">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-3-box.jpg') }}" alt=""/>
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="#uls"
                                           class="btn-play-action usl-link"
                                           data-bonus-url='{{ $bonus3 }}'><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                </div>
                            </div>
                        </section>
                        <!-- <div class="bonusOverlay unavailable">
                            <div class="icon"></div>
                            <h3>Temporarily unavailable</h3>
                        </div> -->

                        <div class="bonusOverlay activated">
                            <div class="icon"></div>
                            <h3>{{ trans('casino.bonus_status') }}</h3>
                        </div>
                    </div>







                    @php
                        $bonusId4 = 4;
                        $bonus4 = route('bonus.activate', $bonusId4);

                       if (!is_null($activeBonus)) {
                            $activatedBonus4 = ($activeBonus->bonus_id == $bonusId4) ? 'activatedBonus' : '';
                        } else {
                            $activatedBonus4 = '';
                        }
                    @endphp
                    <div class="flexChild">
                        <section class="block-bonus block-bonus4 clearfix {{ $activatedBonus4 }}">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-4-box.jpg') }}" alt=""/>
                                </div>
                            </div>

                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="#uls"
                                           class="btn-play-action usl-link"
                                           data-bonus-url='{{ $bonus4 }}'><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                </div>
                            </div>
                        </section>
                        <!-- <div class="bonusOverlay unavailable">
                            <div class="icon"></div>
                            <h3>Temporarily unavailable</h3>
                        </div> -->

                        <div class="bonusOverlay activated">
                            <div class="icon"></div>
                            <h3>{{ trans('casino.bonus_status') }}</h3>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="hidden">

        <div class='tempateBonusActive'>

            @if(Auth::check())
                <div class="popUpTermForm">
                    <input type="checkbox" id="terms">
                    <label for="terms"><span>I accept terms</span>
                        <p class="errorMessage">{{ trans('casino.error_msg') }}</p></label>
                    <a class='bonusActiveTerms popUpBtnBonus'
                       href="https://casinobit.io/bonus/1/activate">{{ trans('casino.activate') }}</a>
                </div>
            @else
                <div class="popUpTermForm" style="justify-content: flex-end;">
                    <a href="#"
                       class="joinNowBtnBonus mfp-close closeBtn">{{ trans('casino.join_now') }}</a>
                </div>
            @endif

        </div>

    </div>

    @include('footer_main')
@endsection

