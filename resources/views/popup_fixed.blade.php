@if (session('popup_fixed'))
    <div class="fixed-popup active">
        <div class="popup-fixed-entry active">
            <span class="side-title">{{translate('E-mail confirmation')}}</span>
            <div class="popup-heading">
                <h2 class="popup-title">{{translate('E-mail confirmation')}}</h2>
                <span class="subtitle">{{translate('Required')}}</span>
            </div>
            <div class="popup-text-block">
                <p class="text"></p>
            </div>
            <div class="btn-block">
                <form method="POST" action="{{route('email.confirm')}}">
                    {{csrf_field()}}
                    <a href="#" class="push-btn" onclick="$(this).closest('form').submit();">{{translate('Send mail')}}</a>
                </form>
            </div>
        </div>
    </div>
@endif