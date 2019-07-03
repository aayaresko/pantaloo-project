<footer class="footer footer-home">
    <div class="bitcoin-block">
        <span class="bitcoin-msg"><i class="bitcoin-icon"></i>{{ trans('casino.work_bitcoin') }}</span>
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
        <ul class="footerLinks">
            <li class="rightReservedTxt">© All rights reserved</li>
            <li><a href="{{$partnerPage}}" class="afiliate" target="_blank">{{ trans('casino.affiliates') }}</a></li>
            <li><a target="_blank" href="{{route('support', ['lang' => $currentLang])}}" class="support">{{ trans('casino.frq') }}</a></li>
            <li><a href="{{route('privacy-policy', ['lang' => $currentLang])}}" target="_blank">Privacy Policy</a></li>
            <li><a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a></li>
            <li><a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a></li>
            @if(App::getLocale() == 'jp') 
            <li><IFRAME SRC="https://licensing.gaming-curacao.com/validator/?lh=625d61ed52ae40b378494428c1137099&template=seal" WIDTH=150 HEIGHT=50 STYLE="border:none;"></IFRAME> </li>
            @endif
        </ul>
    </div>

</footer>
<button class="toTop">
<!DOCTYPE svg  PUBLIC '-//W3C//DTD SVG 1.1//EN'  'http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd'>
<svg width="20px" height="20px" enable-background="new 0 0 444.819 444.819" version="1.1" viewBox="0 0 444.819 444.819" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
	<path d="m352.02 196.71l-186.14-185.86c-6.855-7.233-15.415-10.848-25.697-10.848s-18.842 3.619-25.697 10.848l-21.698 21.416c-7.044 7.043-10.566 15.604-10.566 25.692 0 9.897 3.521 18.56 10.566 25.981l138.75 138.47-138.76 138.76c-7.042 7.043-10.564 15.604-10.564 25.693 0 9.896 3.521 18.562 10.564 25.98l21.7 21.413c7.043 7.043 15.612 10.564 25.697 10.564 10.089 0 18.656-3.521 25.697-10.564l186.14-185.86c7.046-7.423 10.571-16.084 10.571-25.981 1e-3 -10.088-3.525-18.654-10.571-25.697z"/>
</svg>

    </button>
 <div class="hidden">
    <div id="uls">
        <div class="termWrapperInner">  

            {!! trans('casino.bonus__term') !!}
        </div> 
    </div>
</div>

<div class="hidden">
    <div id="reg-terms">
        <div class="termWrapperInner"> 
            {!! trans('casino.terms_conditions') !!}
        </div>
    </div>
</div>

<div class="cookieWarningWrapper">
        <div class="cookieWarningText">
            <p>{!! trans('casino.privacy_policy_cookie') !!} <a href="{{route('privacy-policy', ['lang' => $currentLang])}}" target="_blank">{!! trans('casino.privacy_policy_link') !!}</a> and <a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a></p>
        </div>
        <button class="cookieBtn">{!! trans('casino.cookie_ok') !!}</button>
</div>