@extends('layouts.app')

@section('title')
    {{ mb_convert_case(trans('casino.support'), MB_CASE_TITLE) }}
@endsection

@section('content')
    <div class="page-content-block"
         style="background: #000 url('/media/images/bg/content-bg.png') center no-repeat; background-size: cover;">
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
            </div>
        </div>
    </div>
@endsection


@section('js')
@endsection