"use strict";

let showError = 5000;

function login() {
    let login = $("#login-form");
    login.submit(function( event ) {
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
                    setTimeout(function(){
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
    let login = $("#register-form");
    login.submit(function( event ) {
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
                    setTimeout(function(){
                        $("#register-form + div.error-lists").hide();
                    }, showError);
                }
            }
        });
    });
}

function resetPassword() {
}

$(function () {
    login();
    registr();
    resetPassword();
});

$(document).ready(function () {
    //something
});

