@extends('layouts.app')

@section('title')
    {{translate('Withdraw')}}
@endsection


@section('content')

<div class="cabinet-block"
         style="background: #000 url('/media/images/bg/deposit-bg-dark.jpg') center no-repeat; background-size: cover;">
        <div class="cabinet-entry cabinetMod">
            <div class="main-content">
                <div class="page-heading">
                    <h1 class="page-title">{{ trans('casino.account_title')}}</h1>
                </div>

                @include('main_parts.header_account')

                <div class="main-content-entry">
                    <div class="withdraw-entry">
                        <div class="middle-block">
                            <div class="accountFormWrapper">
                                <form id="userDataForm">
                                    <div class="halfCol">
                                    <label>{{ trans('casino.account_first_name') }}<span>*</span></label>
                                    <input type="text" name="firstName" required>
                                    </div>
                                    
                                    <div class="halfCol">
                                        <label>{{ trans('casino.account_last_name') }}</label> 
                                        <input type="text" name="lastName">
                                    </div>
                                    <div class="fullCol">
                                        <label>{{ trans('casino.date_of_birth') }}<span>*</span></label>  
                                        <select id="days" name="day" required></select>
                                        <select id="months" name="month" required></select>
                                        <select id="years" name="year" required></select>
                                    </div>
                                    <div class="fullCol flexStart">
                                        <p class="genderTxt">{{ trans('casino.account_gender') }}</p>
                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="male" name="gender" id="gender1"> 
                                            <label for="gender1">{{ trans('casino.account_male') }}</label>
                                        </div>

                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="female" name="gender" id="gender2"> 
                                            <label for="gender2">{{ trans('casino.account_female') }}</label>
                                        </div>
                                    </div>
                                    <div class="halfCol">
                                        <label for="country">{{ trans('casino.account_country') }}:<span>*</span></label>
                                        <!-- <select name="country" id="country">Country</select> -->
                                        <input type="text" id="country">
                                    </div>
                                    
                                    <div class="halfCol">
                                        <label for="city">{{ trans('casino.account_city') }}:</label> 
                                        <input type="text" name="city" id="city">
                                    </div>
{{--                                    <div class="fullCol">--}}
{{--                                        <label>{{ trans('casino.account_email') }}:</label>--}}
{{--                                        <div class="emailWrapper confirmd">--}}
{{--                                            <input type="email" name="email" placeholder="{{ trans('casino.email') }}" value="{{ $user->email }}">--}}
{{--                                            <a href="{{route('settings', ['lang' => $currentLang])}}" class="editEmailBtn">{{ trans('casino.account_edit') }}</a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <button class="updateUserDataBtn">
                                    {{ trans('casino.account_update') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('settings')
        </div>
    </div>

    @include('footer_main')
@endsection


@section('js')

    <script>
        //prepare
        let params = {
            lang: '{{ $lang }}'
        };

        //visualization
        let monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        $('#years').append($('<option />').attr('disabled', true).attr('selected', true).val('').html('Year'));
        $('#months').append($('<option />').attr('disabled', true).attr('selected', true).val('').html('Month'));

        for (let i = new Date().getFullYear(); i > 1900; i--){

            $('#years').append($('<option />').val(i).html(i));
        }

        for (let i = 1; i < 13; i++){
            $('#months').append($('<option />').val(i).html(monthNames[i - 1]));

        }

        function updateNumberOfDays(){
            $('#days').html('');
            $('#days').append($('<option />').attr('disabled', true).attr('selected', true).val('').html('Day'));
            let month=$('#months').val();
            let year=$('#years').val();
            let days=daysInMonth(month, year);

            for(let i=1; i < days+1 ; i++){
                $('#days').append($('<option />').val(i).html(i));
            }
        }

        function daysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        }


        updateNumberOfDays();

        $('#years, #months').on("change", function(){
            updateNumberOfDays();
        });


        $('.accountFormWrapper select').select2({
            minimumResultsForSearch: Infinity
        });

        $("#country").countrySelect();
        //visualization

        //action
        function objectifyForm(formArray) {
            let returnArray = {};
            for (let i = 0; i < formArray.length; i++) {
                returnArray[formArray[i]['name']] = formArray[i]['value'];
            }
            return returnArray;
        }

        $('#userDataForm').on('submit', function(e) {
            e.preventDefault();
            let userDateForm = objectifyForm($(this).serializeArray());
            userDateForm.countryCode = $("#country").countrySelect("getSelectedCountryData").iso2;
            userDateForm.birthDay = new Date(userDateForm.year, userDateForm.month,  userDateForm.day).getTime();


            console.log(userDateForm);
            $.ajax({
                method: 'post',
                url: `/${params.lang}/updateUserExtra`,
                data: userDateForm,
            }).done(function(response){

                $(".alertWrapper").addClass("seccuses");
                $(".alertWrapper").addClass("showAlert");

                setTimeout(function(){
                    $(".alertWrapper").removeClass("seccuses");
                    $(".alertWrapper").removeClass("showAlert");
                },2000);

            }).fail(function(){
                $(".alertWrapper").addClass("error");
                $(".alertWrapper").addClass("showAlert");

                setTimeout(function(){
                    $(".alertWrapper").removeClass("error");
                    $(".alertWrapper").removeClass("showAlert");
                },2000);
            });

            return false;
        });
        //action
    </script>

@endsection