"use strict";

let showError = 5000;
let emailCurrent;

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

                    $("#login-form + div.error-lists").show();
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

function clearNotificationMessage() {
    $(`#notificationMessage h4`).empty();
    $(`#notificationMessage .modal-body`).empty();
}

function fillotificationMessage(title = `Error`, body = `<p>Something is wrong</p>`) {
    $(`#notificationMessage h4`).html(title);
    $(`#notificationMessage .modal-body`).html(body);
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
                    //window.location.replace(response['message']['redirect']);
                    //show popup
                    clearNotificationMessage();
                    //insert text and tittle
                    emailCurrent = response['message']['email'];
                    fillotificationMessage(response['message']['title'], response['message']['body']);
                    $("#myModal2").modal('hide');
                    $("#notificationMessage").modal();

                } else {
                    //show error
                    $.each(response['message']['errors'], function (i, val) {
                        $('.error-lists ul').append('<li>' + val + '</li>');
                    });

                    $("#register-form + div.error-lists").show();
                    setTimeout(function () {
                        $("#register-form + div.error-lists").hide();
                    }, showError);
                }
            }
        });
    });
}

function registrSmall() {
    let register = $(".regForm");
    register.submit(function (event) {
        $('.regForm .error-lists ul').html('');
        event.preventDefault();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/register',
            data: $(this).serialize(),
            success: function (response) {
              
                if (response['status'] === true) {
                    //window.location.replace(response['message']['redirect']);
                    //show popup
                    clearNotificationMessage();
                    //insert text and tittle
                    emailCurrent = response['message']['email'];
                    fillotificationMessage(response['message']['title'], response['message']['body']);
                    $("#myModal2").modal('hide');
                    $("#notificationMessage").modal();

                } else {
                    //show error
                    $.each(response['message']['errors'], function (i, val) {
                        $('.error-lists ul').append('<li>' + val + '</li>');
                    });

                    $(event.target).find('.error-lists').show();
                    setTimeout(function () {
                        $(".regForm .error-lists").hide();
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
                if (response['status'] === true) {
                    clearNotificationMessage();
                    //insert text and tittle
                    emailCurrent = response['message']['email'];
                    fillotificationMessage(response['message']['title'], response['message']['body']);
                    $("#notificationMessage").modal();
                    $("#myModal3").modal('hide');
                } else {
                    //show error
                    $.each(response['message']['errors'], function (i, val) {
                        $('.error-lists ul').append('<li>' + val + '</li>');
                    });

                    $("#reset-password-form + div.error-lists").show();
                    setTimeout(function () {
                        $("#reset-password-form + div.error-lists").hide();
                    }, showError);
                }
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
                if (response['status'] === true) {
                    window.location.replace(response['message']['redirect']);
                } else {                    
                      $.each(response['message']['errors'], function (i, val) {
                        $('.error-lists ul').append('<li>' + val + '</li>');
                    });

                    $("#reset-password-finish-form + div.error-lists").show();
                    setTimeout(function () {
                        $("#reset-password-finish-form + div.error-lists").hide();
                    }, showError);
                }
            },
            error: function (response) {
                $.each(response.responseJSON['errors']['password'], function (i, val) {
                    $('.error-lists ul').append('<li>' + val + '</li>');
                });

                $("#reset-password-finish-form + div.error-lists").show();
                setTimeout(function () {
                    $("#reset-password-finish-form + div.error-lists").hide();
                }, showError);
            }
        });
    });
}


function sendFormFeedBack() {
    let contactForm = $("#contactForm");
    contactForm.submit(function (event) {
        event.preventDefault();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/feedback',
            data: $(this).serialize(),
            success: function (response) {
                clearNotificationMessage();
                if (response['status'] === true) {
                    clearNotificationMessage();
                    //insert text and tittle
                    fillotificationMessage(response['message']['title'], response['message']['body']);
                    $("#notificationMessage").modal();
                } else {
                    clearNotificationMessage();
                    //insert text and tittle
                    fillotificationMessage(response['message']['title'], response['message']['body']);
                    $("#notificationMessage").modal();
                }
            }
        });
    });
}

function resendPassword() {
    $("body").on("click", "#resendPassword", function () {
        event.preventDefault();
        var $self = $(this)

        // if ($self.hasClass('blockToSend') == false) {
        //     $self.addClass('blockToSend');
        //     setTimeout(function() {
        //       $self.removeClass('blockToSend')
        //     }, 10000)
        //     return
        //   }

        $('.error-reset').hide();
        $('.success-reset').hide();
        $('.second-hide').hide();
        $('.second-show').show();

        var seconds = $('.seconds').text(),
            int;
        int = setInterval(function () {
            if (seconds > 0) {
                seconds--;
                $('.seconds').text(seconds);
            } else {
                clearInterval(int);
                $('.second-hide').show();
                $('.second-show').hide();
                $('.seconds').text('10');
            }
        }, 1000);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/password/email',
            data: {email: emailCurrent},
            success: function (response) {
                if (response['status'] === true) {
                    console.log('Ok');
                    //to do for front
                    $('.success-reset').show();
                    setTimeout(function () {
                        $('.success-reset').hide();
                    }, showError)
                    $('.error-reset').hide();
                } else {
                    //to do for front
                    $('.error-reset').show();
                    $('.success-reset').hide();

                }
            }
        });
    });
}


function reconfirmPassword() {
    $("body").on("click", "#reconfirmPassword", function () {
        event.preventDefault();
        var $self = $(this)

        // if ($self.hasClass('blockToSend') == false) {
        //     $self.addClass('blockToSend');
        //     setTimeout(function() {
        //       $self.removeClass('blockToSend')
        //     }, 10000)
        //     return
        //   }

        $('.error-reset').hide();
        $('.success-reset').hide();
        $('.second-hide').hide();
        $('.second-show').show();

        var seconds = $('.seconds').text(),
            int;
        int = setInterval(function () {
            if (seconds > 0) {
                seconds--;
                $('.seconds').text(seconds);
            } else {
                clearInterval(int);
                $('.second-hide').show();
                $('.second-show').hide();
                $('.seconds').text('10');
            }
        }, 1000);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/sendToken/' + emailCurrent,
            data: {userEmail: emailCurrent},
            success: function (response) {
                if (response['status'] === true) {
                    console.log('Ok');
                    //to do for front
                    $('.success-reset').show();
                    setTimeout(function () {
                        $('.success-reset').hide();
                    }, showError)
                    $('.error-reset').hide();
                } else {
                    //to do for front
                    $('.error-reset').show();
                    $('.success-reset').hide();

                }
            }
        });
    });
}


function activeMail() {
    //check url
    var url = new URL(window.location.href);
    var confirm = url.searchParams.get('confirm');
    var email = url.searchParams.get('email');
    if (confirm !== null) {
        //send request
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            url: '/affiliates/activate/' + confirm + '/email/' + email,
            data: {},
            success: function (response) {
                //clear url
                window.history.pushState({}, document.title, "/");
                let body;
                if (response['status'] === true) {
                    clearNotificationMessage();
                    fillotificationMessage('Info', response['message']['messages']);
                    $("#notificationMessage").modal();
                } else {
                    clearNotificationMessage();
                    fillotificationMessage('Info', response['message']['errors']);
                    $("#notificationMessage").modal();
                }
            }
        });
    }

    function generateUl(array) {
        var html = '<ul>';
        console.log(array);
        array.forEach(function (element) {
            html += `<li>${element}</li>`;
        });
        html += '</ul>';
        return html;
    }
}

$(function () {
    login();
    registr();
    registrSmall();
    resetPassword();
    resetPasswordFinish();
    sendFormFeedBack();
    resendPassword();
    reconfirmPassword();
    activeMail();
});

$(document).ready(function () {
    //something
});

