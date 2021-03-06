"use strict";

function eventCheckBox() {

    $('#nameStatus').change(function () {
        let input = $(this);
        let checked = input.attr('checked');
        let element = $(`input[name='name']`);

        if (checked === undefined) {

            input.attr('checked', true);

            element.val(item.name);

            element.removeClass('elementReadOnly');
        } else {
            input.attr('checked', false);

            element.val(item.default_name);

            element.addClass('elementReadOnly');
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

