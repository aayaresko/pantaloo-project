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
                                    @php
                                        $name = $user->name;

                                        $birthDayParams  = [
                                            'year' => 0, 'month' => 0, 'day' => 0
                                        ];

                                        $value = null;
                                        if ($extraUser) {
                                            $value = json_decode($extraUser->value);
                                        }
                                        //dump($value);

                                        $lastName = '';
                                            if ($value) {
                                                if (isset($value->lastName)) {
                                                    $lastName = $value->lastName;
                                                }
                                             }
                                    @endphp
                                    <div class="halfCol">
                                        <label>{{ trans('casino.account_first_name') }}<span>*</span></label>
                                        <input type="text" name="firstName" value="{{ $name }}" required>
                                    </div>

                                    <div class="halfCol">
                                        <label>{{ trans('casino.account_last_name') }}</label>
                                        <input type="text" name="lastName" value= {{ $lastName }}>
                                    </div>

                                    <div class="fullCol">
                                        <label>{{ trans('casino.date_of_birth') }}<span>*</span></label>
                                        <select id="days" name="day" required></select>
                                        <select id="months" name="month" required></select>
                                        <select id="years" name="year" required></select>
                                    </div>


                                    @php
                                        $gender = 0;
                                            if ($value) {
                                                if (isset($value->gender)) {
                                                    $gender = $value->gender;
                                                }
                                             }

                                    @endphp
                                    <div class="fullCol flexStart">
                                        <p class="genderTxt">{{ trans('casino.account_gender') }}</p>
                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="1" name="gender"
                                                   id="gender1" {{ ($gender == 1)  ? "checked='checked'" : '' }}>
                                            <label for="gender1">{{ trans('casino.account_male') }}</label>
                                        </div>

                                        <div class="inputRadioWrapper">
                                            <input type="radio" value="2" name="gender"
                                                   id="gender2" {{ ($gender == 2)  ? "checked='checked'" : '' }}>
                                            <label for="gender2">{{ trans('casino.account_female') }}</label>
                                        </div>
                                    </div>

                                    <div class="halfCol">
                                        <label for="country">{{ trans('casino.account_country') }}
                                            :<span>*</span></label>
                                        <!-- <select name="country" id="country">Country</select> -->
                                        <input type="text" id="country">
                                    </div>

                                    @php
                                        $city = '';
                                            if ($value) {
                                                if (isset($value->city)) {
                                                    $city = $value->city;
                                                }
                                             }

                                    @endphp
                                    <div class="halfCol">
                                        <label for="city">{{ trans('casino.account_city') }}:</label>
                                        <input type="text" name="city" id="city" value='{{ $city }}'>
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

    <div class="alertWrapper">
        <div class="alertText">
            <ul class="showMessage"></ul>
        </div>
    </div>
@endsection

@section('js')

    @php
        $birthDayParams  = [
            'year' => 0, 'month' => 0, 'day' => 0
        ];

        if ($value) {
            if (isset($value->birthDay)) {
                $birthDayParams = date_parse($value->birthDay);
            }
         }
    @endphp
    <script src="/vendors/countrySelect.min.js"></script>
    <script>
        //prepare
        let params = {
            lang: '{{ $lang }}',
            country: '{{ $user->country }}'.toLowerCase(),
            birthDay: {
                day: {{ $birthDayParams['day'] }},
                month: {{ $birthDayParams['month'] }},
                year: {{ $birthDayParams['year'] }}
            }
        };

        //visualization
        let monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

        $('#years').append($('<option />').attr('disabled', true).attr('selected', true).val('-1').html('Year'));
        $('#months').append($('<option />').attr('disabled', true).attr('selected', true).val('-1').html('Month'));

        for (let i = new Date().getFullYear(); i > 1900; i--) {

            if (params.birthDay.year == i) {
                $('#years').append($('<option />').attr('selected', true).val(i).html(i));
            } else {
                $('#years').append($('<option />').val(i).html(i));
            }

        }

        for (let i = 1; i < 13; i++) {
            if (params.birthDay.month == i) {
                $('#months').append($('<option />').attr('selected', true).val(i).html(monthNames[i - 1]));
            } else {
                $('#months').append($('<option />').val(i).html(monthNames[i - 1]));
            }
        }

        function updateNumberOfDays(clearAll = 0) {
            $('#days').html('');
            $('#days').append($('<option />').attr('disabled', true).attr('selected', true).val('-1').html('Day'));
            let month = $('#months').val();
            let year = $('#years').val();
            let days = daysInMonth(month, year);

            for (let i = 1; i < days + 1; i++) {
                if (clearAll === 1) {
                    $('#days').append($('<option />').val(i).html(i));
                } else {
                    if (params.birthDay.day == i) {
                        $('#days').append($('<option />').attr('selected', true).val(i).html(i));
                    } else {
                        $('#days').append($('<option />').val(i).html(i));
                    }
                }

            }
        }

        function daysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        }


        updateNumberOfDays();

        $('#years, #months').on("change", function () {
            updateNumberOfDays(1);
        });


        $('.accountFormWrapper select').select2({
            minimumResultsForSearch: Infinity
        });

        $("#country").countrySelect({
            defaultCountry: params.country,
        });
        //visualization

        //action
        function objectifyForm(formArray) {
            let returnArray = {};
            for (let i = 0; i < formArray.length; i++) {
                returnArray[formArray[i]['name']] = formArray[i]['value'];
            }
            return returnArray;
        }

        function setMessage(messages, classView) {
            let element = $(".showMessage");
            element.html('');
            messages.forEach(function (item) {
                element.append(`<li>${item}</li>`);
            });

            $(".alertWrapper").addClass(classView);
            $(".alertWrapper").addClass("showAlert");

            setTimeout(function () {
                $(".alertWrapper").removeClass(classView);
                $(".alertWrapper").removeClass("showAlert");
            }, 3000);
        }


        $('#userDataForm').on('submit', function (e) {
            e.preventDefault();
            let userDateForm = objectifyForm($(this).serializeArray());
            userDateForm.countryCode = $("#country").countrySelect("getSelectedCountryData").iso2.toUpperCase();
            userDateForm.birthDay = `${userDateForm.year}-${userDateForm.month}-${userDateForm.day}`;

            $.ajax({
                method: 'post',
                url: `/${params.lang}/updateUserExtra`,
                data: userDateForm,
            }).done(function (res) {

                if (res.status === true) {
                    setMessage(res.message, 'seccuses');
                } else {
                    setMessage(res.message.errors, 'error');
                }
            });

        });
        //action
    </script>

@endsection