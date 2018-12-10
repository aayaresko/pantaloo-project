"use strict";

function getToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function handleImage(img) {
    $(img).attr("src", dummy);
}