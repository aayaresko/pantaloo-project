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
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-1-box.jpg') }}" alt=""/>
                                </div>
                            </div>
                            @php
                                $bonus1 = route('bonus.activate', '1');
                            @endphp
                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ $bonus1 }}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link"
                                       data-bonus-url='{{ $bonus1 }}'>{{ trans('casino.bonus_terms') }}</a>
                                </div>
                            </div>

                        </section>
                    </div>

                    <div class="col-md-6 col-sm-6 npr ac-wrap">
                        <section class="block-bonus block-bonus2 clearfix">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-2-box.jpg') }}" alt=""/>
                                </div>
                            </div>

                            @php
                                $bonus2 = route('bonus.activate', '2');
                            @endphp
                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ $bonus2 }}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link"
                                       data-bonus-url='{{ $bonus2 }}'>{{ trans('casino.bonus_terms') }}</a>
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
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-3-box.jpg') }}" alt=""/>
                                </div>
                            </div>

                            @php
                                $bonus3 = route('bonus.activate', '3');
                            @endphp
                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ $bonus3 }}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link"
                                       data-bonus-url='{{ $bonus3 }}'>{{ trans('casino.bonus_terms') }}</a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-6 col-sm-6 npr ac-wrap">
                        <section class="block-bonus block-bonus4 clearfix">
                            <div class="block-bonus-left">
                                <div class="block-bonus-image">
                                    <img src="{{ asset('assets/images/bonuses/bonus-blok-4-box.jpg') }}" alt=""/>
                                </div>
                            </div>

                            @php
                                $bonus4 = route('bonus.activate', '4');
                            @endphp
                            <div class="block-bonus-right">
                                <div class="block-bonus-buttons">
                                    @if(Auth::check())
                                        <a href="{{ $bonus4 }}"
                                           class="btn-play-action"><span>{{ trans('casino.activate') }}</span></a>
                                    @else
                                        <a href="#"
                                           class="btn-play-action reg-btn"><span>{{ trans('casino.join_now') }}</span></a>
                                    @endif
                                    <a href="#uls" class="usl-link"
                                       data-bonus-url='{{ $bonus4 }}'>{{ trans('casino.bonus_terms') }}</a>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class='tempateBonusActive' style="display:none">
        <div class="popUpTermForm" style="display: none">
            <input type="checkbox" id="terms">
            <label for="terms"><span>I accept terms</span>
                <p class="errorMessage">Error</p></label>
            @if(Auth::check())
                <a class='bonusActiveTerms' href="https://casinobit.io/bonus/1/activate"
                   class="popUpBtnBonus">{{ trans('casino.activate') }}</a>
            @else
                <a href="#"
                   class="popUpBtnBonus">{{ trans('casino.join_now') }}</a>
            @endif
        </div>
    </div>
    @include('footer_main')
@endsection

@section('js')
    <script>
        function bonusTerms() {
            $('.block-bonus-buttons .usl-link').on('click', function (e) {
                let linkBonus = $(this).attr('data-bonus-url');
                $('.tempateBonusActive .bonusActiveTerms').attr('href', linkBonus);

                let tempateBonusActive = $('.tempateBonusActive').html();
                $('#uls').append(tempateBonusActive);
            });

            $('.usl-link').on('mfpClose', function (e) {
                $("#uls .popUpTermForm").remove();
            });
        }
        bonusTerms();
    </script>
@endsection