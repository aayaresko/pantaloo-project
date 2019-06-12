@extends('layouts.app')

@section('title', trans('casino.frq'))

@section('content')
    <div class="page-content-block"
         style="background: #000 url('/media/images/bg/content-bg-light.jpg') center no-repeat; background-size: cover;">
        <div class="page-content-container">
            <div class="page-content-entry">
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.frq'), MB_CASE_UPPER) }}</h1>
                </div>
                <div class="page-entry">
                    <div class="row">
                        <h4>{{ trans('casino.support_most_popular') }}</h4>
                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_how_do_create_my_account') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_how_do_create_my_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_what_should_i_do_account') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_what_should_i_do_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_account_where_can_i_purchase_bitcoins') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_can_i_purchase_bitcoins_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support__games_fair') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support__games_fair_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_what_currencies_cryptocurrencies') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_what_currencies_cryptocurrencies_value') }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>{{ mb_convert_case(trans('casino.support_how_start'), MB_CASE_UPPER) }}</h4>
                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_how_do_create_my_account') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_how_do_create_my_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_i_havent_receved_confirmation') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_i_havent_receved_confirmation_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_what_is_age_allowed') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_what_is_age_allowed_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_how_to_make_deposit') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_how_to_make_deposit_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_what_currencies_cryptocurrencies') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_we_accept_only_bitcoin') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_what_is_bitcoin') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_what_is_bitcoin_value') }}
                            </p>
                        </div>
                    </div>


                    <div class="row">
                        <h4>{{ mb_convert_case(trans('casino.account'), MB_CASE_TITLE) }}</h4>
                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_can_i_have_account') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_can_i_have_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_what_should_i_do_account') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_what_should_i_do_account_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_can_i_change_email') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_can_i_change_email_value') }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>{{ mb_convert_case(trans('casino.support_deposit'), MB_CASE_TITLE) }}</h4>
                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_deposit_what_happens') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_deposit_what_happens_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_how_long') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_how_long_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_what_are_the_rules') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_what_are_the_rules_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_is_there_a_maximum_withdrawal_rule') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_is_there_a_maximum_withdrawal_rule_value') }}
                            </p>
                        </div>


                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_account_what_are_deposits') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_account_what_are_deposits_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_account_what_your_minimums_deposits') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_account_what_your_minimums_deposits_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_account_how_long_will_request_withdrawal') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_account_how_long_will_request_withdrawal_value') }}
                            </p>
                        </div>
                        <div class="col-sm-12">
                            <h5>
                                {{ trans('casino.support_account_where_can_i_purchase_bitcoins') }}
                            </h5>
                            <p class="text">
                                {{ trans('casino.support_account_where_can_i_purchase_bitcoins_value') }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>{{ mb_convert_case(trans('casino.the_casino'), MB_CASE_TITLE) }}</h4>
                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support__games_fair') }}</h5>
                            <p class="text">
                                {{ trans('casino.support__games_fair_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_what_happens') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_what_happens_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_i_cant_get_the_casino_game') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_i_cant_get_the_casino_game_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_an_error_or_technical_issue') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_an_error_or_technical_issue_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_how_can_i_take_a_screenshot') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_how_can_i_take_a_screenshot_value') }}
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <h4>{{ mb_convert_case(trans('casino.security'), MB_CASE_TITLE) }}</h4>
                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_am_i_required_to_upload_documents_and_why') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_am_i_required_to_upload_documents_and_why_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_is_all_my_information_secure_on_casinobit') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_is_all_my_information_secure_on_casinobit_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_are_my_bitcoins_secure_on_casinobit') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_are_my_bitcoins_secure_on_casinobit_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_how_does_your_kyc_process_work') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_how_does_your_kyc_process_work_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_can_my_documents_be_refused') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_can_my_documents_be_refused_value') }}
                            </p>
                        </div>

                        <div class="col-sm-12">
                            <h5>{{ trans('casino.support_how_can_i_make_sure_my_account_is_fully_protected') }}</h5>
                            <p class="text">
                                {{ trans('casino.support_how_can_i_make_sure_my_account_is_fully_protected_value') }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

     @include('footer_main')
@endsection


@section('js')
@endsection