@extends('layouts.app')

@section('title')
    {{ mb_convert_case(trans('casino.support'), MB_CASE_TITLE) }}
@endsection

@section('content')
    <div class="page-content-block"
         style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="page-content-container">
            <div class="page-content-entry">
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.support'), MB_CASE_TITLE) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support.create_account') }}</h4>
                            <p class="text">
                                {{ trans('casino.support.create_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support.forgotten_password') }}</h4>
                            <p class="text">
                                {{ trans('casino.support.forgotten_password_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support.buy_bitcoins') }}</h4>
                            <p class="text">
                                {{ trans('casino.support.buy_bitcoins_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support.games_fair') }}</h4>
                            <p class="text">
                                {{ trans('casino.support.games_fair_value') }}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.account'), MB_CASE_TITLE) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_can_i_have_account') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_can_i_have_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_what_should_i_do_account') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_what_should_i_do_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_can_i_change_email') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_can_i_change_email_value') }}
                            </p>
                        </div>

                        

                    </div>
                </div>

                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.support_deposit'), MB_CASE_TITLE) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_deposit_what_happens') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_deposit_what_happens_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_how_long') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_how_long_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_what_are_the_rules') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_what_are_the_rules_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_is_there_a_maximum_withdrawal_rule') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_is_there_a_maximum_withdrawal_rule_value') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.the_casino'), MB_CASE_TITLE) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_are_the_games_fair') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_are_the_games_fair_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_what_happens') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_what_happens_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_i_cant_get_the_casino_game') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_i_cant_get_the_casino_game_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_an_error_or_technical_issue') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_an_error_or_technical_issue_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_how_can_i_take_a_screenshot') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_how_can_i_take_a_screenshot_value') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.security'), MB_CASE_TITLE) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_am_i_required_to_upload_documents_and_why') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_am_i_required_to_upload_documents_and_why_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_is_all_my_information_secure_on_casinobit') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_is_all_my_information_secure_on_casinobit_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_are_my_bitcoins_secure_on_casinobit') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_are_my_bitcoins_secure_on_casinobit_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_how_does_your_kyc_process_work') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_how_does_your_kyc_process_work_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_can_my_documents_be_refused') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_can_my_documents_be_refused_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h4>{{ trans('casino.support_how_can_i_make_sure_my_account_is_fully_protected') }}</h4>
                            <p class="text">
                                {{ trans('casino.support_how_can_i_make_sure_my_account_is_fully_protected_value') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <footer class="footer footer-static">
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
            <p>© All rights reserved</p>
        </div>
    </footer>
@endsection


@section('js')
@endsection