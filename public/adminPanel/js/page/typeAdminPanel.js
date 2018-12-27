"use strict";

function eventCheckBox() {

    $('#nameStatus').change(function () {
        let input = $(this);
        let checked = input.attr('checked');
        let element = $(`input[name='name']`);

        if (checked === undefined) {
            input.attr('checked', true);

            element.val(game.name);

            element.attr('disabled', false);
        } else {
            input.attr('checked', false);

            element.val(game.default_name);

            element.attr('disabled', true);
        }
    });

}

function laodImage() {
    $('#laodImage').change(checkFile);
}

$(function () {
    laodImage();
});

$(document).ready(function () {
    //something
    eventCheckBox();
});

