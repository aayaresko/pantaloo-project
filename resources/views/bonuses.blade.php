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

                    @foreach($bonuses as $bonus)
                        @php
                            $bonusExtra = json_decode($bonus->extra, true);
                            $activatedBonus = '';
                            if (!is_null($activeBonus)) {
                                $activatedBonus = ($activeBonus->bonus_id == $bonus->id) ? 'activatedBonus' : '';
                            }
                        @endphp

                        @if ($bonus->public == 1 or $activatedBonus <> '')
                            <div class="flexChild">
                                <section class="block-bonus clearfix {{ $activatedBonus }}"
                                         style="background-image: url({{ $bonusExtra['mainPicture'] }});">
                                    <div class="block-bonus-left">
                                        <div class="block-bonus-image">
                                            <img src="{{ asset($bonusExtra['additionalPicture']) }}" alt=""/>
                                        </div>
                                    </div>

                                    <div class="block-bonus-right">
                                        <div class="block-bonus-buttons">
                                            @if(Auth::check())
                                                <a href="#uls"
                                                   class="btn-play-action usl-link"
                                                   data-bonus-url='{{ route('bonus.activate', $bonus->id) }}'>
                                                    <span>{{ trans('casino.activate') }}</span>
                                                </a>
                                            @else
                                                <a href="#"
                                                   class="btn-play-action reg-btn">
                                                    <span>{{ trans('casino.join_now') }}</span>
                                                </a>
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
                        @endif
                    @endforeach


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

@section('js')
    <script>

        $('#uls').on('click', '.popUpBtnBonus', function (e) {
            // let url = $(this).attr('href');
            // $(`<form action='${url}' method='post'></form>`).appendTo('body').submit();
        });
    </script>
@endsection


