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


    $('#typeStatus').change(function () {
        let input = $(this);
        let checked = input.attr('checked');
        let element = $(`select[name='type_id']`);

        if (checked === undefined) {
            input.attr('checked', true);

            element.val(game.type_id);

            element.attr('disabled', false);
        } else {
            input.attr('checked', false);

            element.val(game.default_type_id);

            element.attr('disabled', true);
        }
    });


    $('#categoryStatus').change(function () {
        let input = $(this);
        let checked = input.attr('checked');
        let element = $(`select[name='category_id']`);

        if (checked === undefined) {
            input.attr('checked', true);

            element.val(game.category_id);

            element.attr('disabled', false);
        } else {
            input.attr('checked', false);

            element.val(game.default_category_id);

            element.attr('disabled', true);
        }
    });


    $('#imageStatus').change(function () {
        let input = $(this);
        let checked = input.attr('checked');
        let element = $(`input[name='image']`);

        if (checked === undefined) {
            input.attr('checked', true);
            $(".games-block__image").attr("src", game.image);

            $(`input[name='default_provider_image']`).attr('checked', false);
            element.attr('disabled', false);
        } else {
            input.attr('checked', false);
            $(".games-block__image").attr("src", game.default_image);

            $(`input[name='default_provider_image']`).attr('checked', 'checked');
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

