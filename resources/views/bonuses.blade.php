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

                    @foreach($bonusForView as  $key => $bonus)
                        @php
                            $bonusExtra = json_decode($bonus->extra, true);
                        @endphp

                        <div class="flexChild">
                            <section class="block-bonus clearfix {{ (is_null($bonus->activeBonus) ? '' : 'activatedBonus') }}"
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


