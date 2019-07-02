@extends('layouts.app')

@section('title', trans('casino.frq'))

@section('content')
    <div class="page-content-block disabledBoxShadow"
         style="background: #000 url('/media/images/bg/faq_bg.jpg') center no-repeat; background-size: cover;">
        <div class="page-content-container faqWrapper">
            <div class="page-content-entry">
                <div class="page-heading">
                    <h1 class="page-title">{{ mb_convert_case(trans('casino.contact'), MB_CASE_UPPER) }}</h1>
                </div>
                <div class="page-entry">
                    <p>Our support team agents are glad to help you 24/7. Email us at <a href="mailto: support@casinobit.io">support@casinobit.io</a>  or use the form below.  We will contact you back within 24 hours. 
                    Please, fill the information below. All fields are required.</p> 
                    <div class="contacFormWrap">
                        <form id="contForm" action="#" method="post" enctype="multipart/form-data">
                            <label for="contEmail">Email*</label>
                            <input type="email" id="contEmail" name="email">
                            <label for="contText">Message*</label>
                            <textarea id="contText" name="message"></textarea>
                            <div class="addFileWrap">
                                <label for="contFile" id="fileLabel">
                                    <span id="attachTxt">Attach file</span>
                                    
                                   
								</label>
								<span id="contFilesWrap">

								</span>
                                <input type="file" id="contFile" name="files" multiple>
                            </div>
                            <button class="submitBtn">Send</button>
                         </form>
                     </div>  
                </div>
            </div>          
        </div>
    </div>
    @include('footer_main')
@endsection


@section('js')
<script>
	let remove = 'remove'
</script>
	
@endsection