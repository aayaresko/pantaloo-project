@if (session('popup'))
    <div class="mfp-content simple-popup active">
        

        <div id="activatedPoUp" class="activBonusPoUpWrap">
        <button class="close-icon">×</button>
            <!-- <div class="icon">

            </div> -->
            <h3>{{translate(session('popup')[0])}}</h3>
            <p>{{translate(session('popup')[1])}}</p>
            <button class="push-btn close-button">{{translate('Close')}}</button>
        </div>


    </div>
@endif