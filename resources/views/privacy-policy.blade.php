@extends('layouts.app')

@section('title', trans('casino.frq'))

@section('content')
    <div class="page-content-block disabledBoxShadow"
         style="background: #000 url('/media/images/bg/faq_bg.jpg') center no-repeat; background-size: cover;">
        <div class="block-heading">
        <h1 class="page-title"> {!! trans('casino.privacy_policy_title') !!}</h1>
            <div class="breadcrumbs">
                <a href="/">CasinoBit</a>
                <span class="bredDelim">/</span>
                <span class="lastBred">{!! trans('casino.privacy_policy_title') !!}</span>
            </div>  
        </div>
         <div class="page-content-container privacyPolicy">
            <div class="page-content-entry">
                    <div class="page-entry">
                        {!! trans('casino.privacy_policy') !!}
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