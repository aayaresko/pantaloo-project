@extends('layouts.app')

@section('title', trans('casino.frq'))

@section('content')
    <div class="page-content-block disabledBoxShadow"
         style="background: #000 url('/media/images/bg/faq_bg.jpg') center no-repeat; background-size: cover;">
        <div class="page-content-container faqWrapper">
            <div class="page-content-entry">
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.terms'), MB_CASE_UPPER) }}</h1>
                </div>
                <div class="page-entry">
                    <p class="underHeadTxt">
                    {!!trans('casino.contact_us_text')!!}
                    <!-- Our support team agents are glad to help you 24/7. Email us at <a href="mailto: support@casinobit.io">support@casinobit.io</a>  or use the form below.  We will contact you back within 24 hours. 
                    Please, fill the information below. -->
                </p> 
                    <div class="contacFormWrap">
                        <form id="contForm" action="#" method="post" enctype="multipart/form-data">
                            <label for="contEmail"> {{trans('casino.contact_us_email')}}: <span class="red">*</span></label>
                            <input type="email" id="contEmail" name="email">
                            <label for="contText">{{trans('casino.contact_us_message')}}: <span class="red">*</span></label>
                            <textarea id="contText" name="message"></textarea>
                            <div class="addFileWrap">
                                <label for="contFile" id="fileLabel">
                                    <span class="fileControl" id="attachTxt">{{trans('casino.contact_us_file')}}</span>                                
                                </label>
								<span class="fileControl" id="contFilesWrap"></span>
                                <span class="removeTxt">{{trans('casino.contact_us_remove_file')}}</span>
                                <input type="file"  id="contFile" name="files" multiple>
                            </div>
                            <div class="contactError success">
                                <!-- Success -->
                                <p class="alertText">{{trans('casino.contact_us_success')}}</p>                             
                            </div>
                            <div class="contactError error max_file_count">
                                <!-- You can upload up to 5 files. -->
                                <p class="alertText">{{trans('casino.contact_us_max_file_count')}}</p>                             
                            </div>
                            <div class="contactError error not_valid_ext">
                                <!-- Images should be in .jpeg .jpg or .png format. -->
                                <p class="alertText">{{trans('casino.contact_us_not_valid_ext')}}</p>
                            </div>
                            <div class="contactError error max_file_size">
                                <!-- The size limit for each file is 1 MB. -->
                                <p class="alertText">{{trans('casino.contact_us_max_file_size')}}</p>
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

@endsection