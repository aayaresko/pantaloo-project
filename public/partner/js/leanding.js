"use strict";

function login() {
    let login = $("#login-form");
    login.submit(function( event ) {
        event.preventDefault();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/login',
            data: $(this).serialize(),
            success: function (response) {
                console.log(response);
                if (response['status'] = true) {
                    window.location.replace(response['message']['redirect']);
                } else {
                    //show error
                }
            }
        });
    });

}

function logout() {
}

function resetPassword() {
}

$(function () {
    login();
    logout();
    resetPassword();
});

$(document).ready(function () {
    //something
});

