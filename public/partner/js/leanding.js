"use strict";

let showError = 5000;

function login() {
    let login = $("#login-form");
    login.submit(function (event) {
        clearErrorMsg('login-form');
        event.preventDefault();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/login',
            data: $(this).serialize(),
            success: function (response) {
                if (response['status'] === true) {
                    window.location.replace(response['message']['redirect']);
                } else {
                    //show error
                    $.each(response['message']['errors'], function (i, val) {
                        $('.error-lists ul').append('<li>' + val + '</li>');
                    });

                    $("#login-form + div.error-lists").show(500);
                    setTimeout(function () {
                        $("#login-form + div.error-lists").hide();
                    }, showError);
                }
            }
        });
    });
}


function clearErrorMsg(classForm) {
    $(`#${classForm} + div.error-lists ul`).html('');
}

function registr() {
    let register = $("#register-form");
    register.submit(function (event) {
        clearErrorMsg('register-form');
        event.preventDefault();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/register',
            data: $(this).serialize(),
            success: function (response) {
                if (response['status'] === true) {
                    window.location.replace(response['message']['redirect']);
                } else {
                    //show error
                    $.each(response['message']['errors'], function (i, val) {
                        $('.error-lists ul').append('<li>' + val + '</li>');
                    });

                    $("#register-form + div.error-lists").show(500);
                    setTimeout(function () {
                        $("#register-form + div.error-lists").hide();
                    }, showError);
                }
            }
        });
    });
}

function resetPassword() {
    let reset = $("#reset-password-form");
    reset.submit(function (event) {
        event.preventDefault();
        clearErrorMsg('reset-password-form');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/password/email',
            data: $(this).serialize(),
            success: function (response) {
                console.log(response);
                alert('Ok');
            }
        });
    });
}

function resetPasswordFinish() {
    let reset = $("#reset-password-finish-form");
    reset.submit(function (event) {
        clearErrorMsg('reset-password-finish-form');
        event.preventDefault();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/password/reset',
            data: $(this).serialize(),
            success: function (response) {
                console.log(response);
                alert('Ok');
            }
        });
    });
}

$(function () {
    login();
    registr();
    resetPassword();
    resetPasswordFinish();
});

$(document).ready(function () {
    //something
});

