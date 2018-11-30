//"use strict";

let listGameParamsDefault = {
    type_id: null,
    categoty_id: null,
    search: null
};

let listGameParams = JSON.parse(JSON.stringify(listGameParamsDefault));

function getListGames() {
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: '/integratedGamesJson',
        date: listGameParams,
        success: function (response) {
            //clear
            $(".insertGames").empty();
            $(".insertGamesMobile").empty();
            //insert
            $(".insertGames").append(response.desktop);
            $(".insertGamesMobile").append(response.mobile);
        }
    });
}

getListGames();
$( document ).ready(function() {

    $('body').on("click", "a", function(){
        $(this).text("It works!");
    });
});