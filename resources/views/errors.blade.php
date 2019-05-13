
@if(count($errors) > 0)
<div class="simple-popup active">
    <div class="popup-entry active">
        <a href="#" class="close-icon"></a>
        <div class="popup-text-block">
            @foreach($errors->all() as $error)
                {{translate($error)}}<br>
            @endforeach
    </div>
    <div class="btn-block">
        <a href="#" class="push-btn close-button">{{translate('Close')}}</a>
        </div>
    </div>
</div>
@endif