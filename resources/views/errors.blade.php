
@if(count($errors) > 0)
<div class="simple-popup active">

        <div class="activBonusPoUpWrap active">
            <button class="close-icon">×</button>
            <div class="popup-text-block">
                @foreach($errors->all() as $error)
                    {{translate($error)}}<br>
                @endforeach
        </div>
        <div class="btn-block">
            <button class="push-btn close-button">{{translate('Close')}}</button>
        </div>
        </div>
    
</div>
@endif