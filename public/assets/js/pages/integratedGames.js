"use strict";

let listGameParamsDefault = {
    typeId: 0,
    categoryId: 0,
    search: '',
    page: 1
};

let listGameParams = JSON.parse(JSON.stringify(listGameParamsDefault));

let events = function () {

    $('body').on('click', '.games-block__buttons a', function(e){
        let url = String(this.getAttribute('href'));
        getGame(url);
    });

    $('body').on('click', '.paginationGame a', function(e){
        e.preventDefault();
        let url = new URL(this.getAttribute('href'));
        let page = Number(url.searchParams.get('page'));
        listGameParams.page = page;

        getListGames();
        $('html,body').scrollTop(0);
    });

    $('#type_of_game').on('change', function (e) {
        e.preventDefault();
        listGameParams.typeId =  Number($("#type_of_game").val());

        getListGames();
        $('html,body').scrollTop(0);
    });

    $('#filter_provider').on('change', function (e) {
        e.preventDefault();
        listGameParams.categoryId =  Number($("#filter_provider").val());

        getListGames();
        $('html,body').scrollTop(0);
    });

    $(document).on('submit', '.block-filter form', function (e) {
        e.preventDefault();
        listGameParams.search =  $("input[name='search']").val();

        getListGames();
        $('html,body').scrollTop(0);
    });
};


function getListGames() {
    $('.preloaderCommon').show();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: '/integratedGamesJson',
        data: listGameParams,
        success: function (response) {
            //clear
            $(".insertGames").empty();
            $(".insertGamesMobile").empty();
            //insert
            $(".insertGames").append(response.desktop);
            $(".insertGamesMobile").append(response.mobile);
            $('.preloaderCommon').hide();
        }
    });
}


function getGame(url) {
    //$('.preloaderCommon').show();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: url,
        data: {},
        success: function (html) {
            //clear
            //insert games link
            $('.video-popup .game-entry').html(html);
            //$('.preloaderCommon').hide();
        }
    });
}

$(function () {
    events();
    getListGames();
});

$('body').ready(function() {

});