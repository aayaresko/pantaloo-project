@extends('layouts.app')

@section('title', trans('casino.frq'))

@section('content')
    <div class="page-content-block disabledBoxShadow"
         style="background: #000 url('/media/images/bg/faq_bg.jpg') center no-repeat; background-size: cover;">
        <div class="block-heading">
        <h1 class="page-title">{{ mb_convert_case(trans('casino.contact_us_header'), MB_CASE_UPPER) }}</h1>
            <div class="breadcrumbs">
                <a href="/">CasinoBit</a>
                <span class="bredDelim">/</span>
                <span class="lastBred">{{ trans('casino.contact_us_header') }}</span>
            </div>  
        </div>
         <div class="page-content-container faqWrapper">
            <div class="page-content-entry">
                <div class="page-entry">
                    <p class="underHeadTxt">{!!trans('casino.contact_us_text')!!}</p> 
                    <div class="contacFormWrap">
                        <form id="contForm" action="#" method="post" enctype="multipart/form-data">
                            <label for="contEmail"> {{trans('casino.contact_us_email')}}: <span class="red">*</span></label>
                            <input type="email" required id="contEmail" name="email">
                            <label for="contText">{{trans('casino.contact_us_message')}}: <span class="red">*</span></label>
                            <textarea id="contText" required name="message"></textarea>
                            <div class="addFileWrap">
                                <label for="contFile" id="fileLabel">
                                    <span class="fileControl" id="attachTxt">{{trans('casino.contact_us_file')}}</span>                                
                                </label>
								<span class="fileControl" id="contFilesWrap"></span>
                                <span class="removeTxt">{{trans('casino.contact_us_remove_file')}}</span>
                                <input type="file"  id="contFile" accept=".jpg, .jpeg, .png" name="files" multiple>
                            </div>
                            <div class="contactError success">
                                <p class="alertText">{{trans('casino.contact_us_success')}}</p>                             
                            </div>
                            <div class="contactError error max_file_count">
                                <p class="alertText">{{trans('casino.contact_us_max_file_count')}}</p>                             
                            </div>
                            <div class="contactError error not_valid_ext">
                                <p class="alertText">{{trans('casino.contact_us_not_valid_ext')}}</p>
                            </div>
                            <div class="contactError error max_file_size">
                                <p class="alertText">{{trans('casino.contact_us_max_file_size')}}</p>
                            </div>
                            <div class="contactError error cantSendErr">
                                <p class="alertText">{{trans('casino.contact_us_cant_send')}}</p>
                            </div>
                            <div class="contactError error otherErr">
                                <p class="alertText"></p>
                            </div>
                            <button class="submitBtn">{{trans('casino.contact_us_send')}}</button>
                         </form>
                     </div>  
                </div>
            </div>          
        </div>
    </div>
    @include('footer_main')
@endsection


@section('js')
<script src="/assets/js/pages/contactForm.js?v={{ config('sentry.release') }}"></script>
@endsection