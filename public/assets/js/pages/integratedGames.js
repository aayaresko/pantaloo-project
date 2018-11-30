"use strict";

let listGameParamsDefault = {
    typeId: 0,
    categoryId: 0,
    search: '',
    page: 1
};
let listGameParams = JSON.parse(JSON.stringify(listGameParamsDefault));

let events = function () {
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
        console.log(listGameParams);
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

$(function () {
    events();
    getListGames();
});

$('body').ready(function() {

});