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
            <li><a href="#reg-terms" class="reg-terms">{{ trans('casino.accept_the_terms_link') }}</a></li>
            <li><a href="#uls" class="usl-link">{{ trans('casino.terms') }}</a></li>
            @if(App::getLocale() == 'jp') 
            <li><IFRAME SRC="https://licensing.gaming-curacao.com/validator/?lh=625d61ed52ae40b378494428c1137099&template=seal" WIDTH=150 HEIGHT=50 STYLE="border:none;"></IFRAME> </li>
            @endif
        </ul>
    </div>

</footer>
 <div class="hidden">
    <div id="uls">
        {!! trans('casino.bonus.term') !!}
    </div>
</div>

<div class="hidden">
    <div id="reg-terms">
        {!! trans('casino.terms_conditions') !!}
    </div>
</div>