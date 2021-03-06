@extends('layouts.app')

@section('title', trans('casino.bonuses'))

@section('content')
    <div class="cabinet-block act page-bonuses pageBonus"
         style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="block-heading">
        <h1 class="page-title">{{ mb_convert_case(trans('casino.bonuses'), MB_CASE_UPPER) }}</h1>
            <div class="breadcrumbs">
                <a href="/">CasinoBit</a>
                <span class="bredDelim">/</span>
                <span class="lastBred">{{trans('casino.bonuses')}}</span>
            </div>  
        </div>
         <div class="actions">
            <div class="container">
                <div class="flexContainer">                
                    @foreach($bonusForView as  $key => $bonus)
                        @php
                            $bonusExtra = json_decode($bonus->extra, true);

                        $bonusStatus = '';

                        if ($bonus->notAvailable === false) {
                               $bonusStatus = 'unavailableBonus';
                        }

                        if (!is_null($bonus->activeBonus)) {
                               $bonusStatus = 'activatedBonus';
                        }

                        @endphp

                        <div class="flexChild">
{{--                            <section class="block-bonus clearfix {{ (is_null($bonus->activeBonus) ? '' : 'activatedBonus') }}"--}}
                            <section class="block-bonus clearfix {{ $bonusStatus }}"
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
                                               class="regBtn usl-link"
                                               data-bonus-url='{{ route('bonus.activate', $bonus->id) }}'>
                                                <span>{{ trans('casino.activate') }}</span>
                                            </a>
                                        @else
                                            <a href="#"
                                               class="regBtn reg-btn">
                                                <span>{{ trans('casino.join_now') }}</span>
                                            </a>
                                        @endif

                                    </div>
                                </div>
                            </section>
                            <div class="bonusOverlay unavailable">
                                <div class="hideBonus">
                                <div class="icon"></div>
                                <h3>Unavailable</h3>
                                <p class="unavailInfo">Expired!
                                    <button class="popUpBonus">
                                        <span class="infoTxt">info</span>
                                    </button>
                                </p>
                                <a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a>
                                </div>
                            </div>

                            <div class="bonusOverlay activated">
                                <div class="icon"></div>
                                <h3>{{ trans('casino.bonus_status') }}</h3>
                            </div>

                            <div class="popUpBonusUnavail">
                                <h3>Unavailable <span class="popUpHideBtn"></span></h3>
                                <p>Bonus expired. Under the terms, you can not use it to run games and get free new bonuses.
                                    Under the terms, you can not use it to run games and get free new bonuses.
                                </p>
                            </div>
                        </div>

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

                    <form action='#' id = 'formSendBonus' method='post' style="display: none">
                        {{csrf_field()}}
                    </form>
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

        function bonusTerms() {
            //click active first step
            $('.block-bonus-buttons .usl-link').on('click', function (e) {
                let linkBonus = $(this).attr('data-bonus-url');
                $('.tempateBonusActive .bonusActiveTerms').attr('href', linkBonus);
                //new
                $('#formSendBonus').attr('action', linkBonus);

                let tempateBonusActive = $('.tempateBonusActive').html();
                $('#uls').append(tempateBonusActive);
            });

            //click second step
            $('#uls').on('click','.popUpBtnBonus', function(e) {
                e.preventDefault();
                if($('#terms').prop('checked') == false){

                    $(".errorMessage").addClass("showErrorMsg");

                    $(this).prev().addClass("showErrorMsg");

                } else {
                    $('#formSendBonus').submit();
                }

            });

            //close and delete link (form)
            $('.usl-link').on('mfpClose', function (e) {
                $("#uls .popUpTermForm").remove();
            });

            //delete message errors
            $("#uls").on("click", '.mfp-close' ,function(){

                $(".errorMessage").removeClass('showErrorMsg');

            });
        }

        bonusTerms();

    </script>
@endsection


