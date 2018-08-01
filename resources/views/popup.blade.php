@if (session('popup'))
    <div class="simple-popup active">
        <div class="popup-entry active">
            <span class="side-title">{{translate(session('popup')[0])}}</span>
            <a href="#" class="close-icon"></a>
            <div class="popup-heading">
                <h2 class="popup-title">{{translate(session('popup')[0])}}</h2>
                <span class="subtitle">{{translate(session('popup')[1])}}</span>
            </div>
            <div class="popup-text-block">
                <p class="text">{{translate(session('popup')[2])}}</p>
            </div>
            <div class="btn-block">
                <a href="#" class="push-btn close-button">{{translate('Close')}}</a>
            </div>
        </div>
    </div>
@endif