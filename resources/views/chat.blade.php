@extends('layouts.app')

@section('title', trans('casino.frq'))

@section('content')
    <div class="page-content-block disabledBoxShadow"
         style="background: #000 url('/media/images/bg/faq_bg.jpg') center no-repeat; background-size: cover;">
        <div class="block-heading">
            <h1 class="page-title">{{ mb_convert_case(trans('casino.frq'), MB_CASE_UPPER) }}</h1>
            <div class="breadcrumbs">
                <a href="/">CasinoBit</a>
                <span class="bredDelim">/</span>
                <span class="lastBred">{{ trans('casino.frq') }}</span>
            </div>  
        </div>
        <div class="page-content-container faqWrapper">
            <div class="page-content-entry">         
                <div class="page-entry">

                        <h2>{{ trans('casino.support_most_popular') }}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_most_popular_content') !!}
                        </div>
                        <h2>{{ trans('casino.support_registration') }}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_registration_content') !!}
                        </div>
                        <h2>{{ trans('casino.support_deposit_and_withdrawals')}}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_deposit_and_withdrawals_content') !!}
                        </div>
                        <h2>{{ mb_convert_case(trans('casino.the_casino'), MB_CASE_TITLE) }}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_the_casino_content') !!}
                        </div>
                        <h2>{{ trans('casino.support_bonuses')}}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_bonuses_content') !!}
                        </div>
                        <h2>{{ trans('casino.support_technical_issues')}}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_technical_issues_content') !!}
                        </div>
                        <h2>{{ mb_convert_case(trans('casino.security'), MB_CASE_TITLE) }}</h2>
                        <div class="textWrapper">
                            {!! trans('casino.support_security_content') !!}
                        </div>

                    </div>
                </div>
            </div>
            <div class="supportWrapper" style="display: none;">
                <h3>{{ trans('casino.support_title') }}</h3>
                <p>{{ trans('casino.support_subtitle') }}</p>
            </div>
        </div>

     @include('footer_main')
@endsection


@section('js')
@endsection