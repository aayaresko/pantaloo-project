"use strict";

let listGameParamsDefault = {
    typeId: 0,
    categoryId: 0,
    search: '',
    page: 1
};

let statusGameRoom = 0;
let statusTypes = 0;

let listGameParams = JSON.parse(JSON.stringify(listGameParamsDefault));

let events = function () {

    $('.video-popup .exit-btn').click(function () {
        //off insert new games
        statusGameRoom = statusGameRoom + 1;

        $(this).parents('.video-popup').removeClass('active');
        $('header.header').removeClass('active');
        $('.video-popup .game-entry').html(
            ` <div class="gameLoadingWrapper">
                    <h1 class = "gameLoading">
                        <span class="let1">l</span>
                        <span class="let2">o</span>
                        <span class="let3">a</span>
                        <span class="let4">d</span>
                        <span class="let5">i</span>
                        <span class="let6">n</span>
                        <span class="let7">g</span>
                    </h1>
                </div>`
        );
        return false;
    });

    $('body').on('click', 'a.open_game', function (e) {
        e.preventDefault();
        if (auth) {
            let url = String(this.getAttribute('href'));
            $('.expand-game').addClass('not-allowed');
            getGame(url);
        }
        else {
            $('.log-popup').addClass('active');
        }
        return false;
    });

    $('body').on('click', '.paginationGame a', function (e) {
        e.preventDefault();
        let url = new URL(this.getAttribute('href'));
        let page = Number(url.searchParams.get('page'));
        listGameParams.page = page;
        getListGames();
    });


    $('.type_of_game').on('change', function (e) {
        e.preventDefault();
        listGameParams.typeId = Number($(this).val());
        listGameParams.page = 1;
        getListGames();
        setDefaultTitle();
        $('html,body').scrollTop(0);
    });


    $('.filter_provider').on('change', function (e) {
        e.preventDefault();
        listGameParams.categoryId = Number($(this).val());
        listGameParams.page = 1;
        getListGames();
        setDefaultTitle();
        $('html,body').scrollTop(0);
    });

    $(document).on('submit', '.block-filter form', function (e) {
        e.preventDefault();
        listGameParams.search = $(this).find('input[name="search"]').val();
        listGameParams.page = 1;
        getListGames();
        setDefaultTitle();
        $('html,body').scrollTop(0);
    });

};

function handleImage(img) {
    $(img).attr("src", dummy);
}

function getListGames() {
    $('.preloaderCommon').show();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: '/integratedGamesJson',
        data: listGameParams,
        success: function (response) {
            //clear
            //$(".insertGames").empty();
            //$(".insertGamesMobile").empty();
            //insert
            $('html,body').scrollTop(0);
            $(".insertGames").html(response.desktop);
            $(".insertGamesMobile").html(response.mobile);
            $('.preloaderCommon').hide();
            resizeIframe();
        }
    });
}

var button = document.querySelector('.expand-game');

function getGame(url) {
    //$('.preloaderCommon').show();
    let statusGameRoomLocal = statusGameRoom;
    statusGameRoom++;
    //console.log(statusGameRoom);
    $('.video-popup').addClass('popup-slot');
    $('.video-popup').addClass('active');
    $('header.header').addClass('active');
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: url,
        data: {},
        success: function (html) {
            //clear
            //insert games link
            //console.log(statusGameRoom);
            //console.log(statusGameRoomLocal + 1);
            if (statusGameRoom === (statusGameRoomLocal + 1)) {
                $('.video-popup .game-entry').html(html);
                $('.expand-game').removeClass('not-allowed');
                button.addEventListener('click', fullscreen);
                //$('.preloaderCommon').hide();
            }
        },
        statusCode: {
            500: function() {
                let errorMessage = "<h3>Something went wrong. Try to refresh page. Thanks</h3>";
                $('.video-popup .game-entry').html(errorMessage);
            }
        }
    });
}

function setDefaultFilter() {

    var url_string = window.location.href;
    var url = new URL(url_string);
    var type_id = url.searchParams.get("type_id");
    var category_id = url.searchParams.get("category_id");

    if (type_id !== null) {
        $('.type_of_game').val(type_id).trigger('change');
    } else {
        type_id = 0;
    }

    if (category_id !== null) {
        $('.type_of_game').val(category_id).trigger('change');
    } else {
        category_id = 0;
    }

    listGameParams.typeId = type_id;
    listGameParams.category_id = category_id;
}

function setDefaultTitle() {
    statusTypes = statusTypes + 1;
    if (statusTypes > 0) {
        $('.tittlePage').text(defaultTitle);
    }
}

$(function () {
    setDefaultFilter();
    events();
    getListGames();
});


// when you are in fullscreen, ESC and F11 may not be trigger by keydown listener.
// so don't use it to detect exit fullscreen
document.addEventListener('keydown', function (e) {
    console.log('key press' + e.keyCode);
});
// detect enter or exit fullscreen mode
document.addEventListener('webkitfullscreenchange', fullscreenChange);
document.addEventListener('mozfullscreenchange', fullscreenChange);
document.addEventListener('fullscreenchange', fullscreenChange);
document.addEventListener('MSFullscreenChange', fullscreenChange);

function fullscreen() {
    // check if fullscreen mode is available
    if (document.fullscreenEnabled ||
        document.webkitFullscreenEnabled ||
        document.mozFullScreenEnabled ||
        document.msFullscreenEnabled) {

        // which element will be fullscreen
        var iframe = document.querySelector('.game-entry iframe');
        // Do fullscreen
        if (iframe.requestFullscreen) {
            iframe.requestFullscreen();
        } else if (iframe.webkitRequestFullscreen) {
            iframe.webkitRequestFullscreen();
        } else if (iframe.mozRequestFullScreen) {
            iframe.mozRequestFullScreen();
        } else if (iframe.msRequestFullscreen) {
            iframe.msRequestFullscreen();
        }
    }
    else {
        document.querySelector('.error').innerHTML = 'Your browser is not supported';
    }
}

function fullscreenChange() {
    if (document.fullscreenEnabled ||
        document.webkitIsFullScreen ||
        document.mozFullScreen ||
        document.msFullscreenElement) {
        console.log('enter fullscreen');
    }
    else {
        console.log('exit fullscreen');
    }

    var iframe = document.querySelector('iframe');
    iframe.src = iframe.src;
}

function resizeIframe()
{
    var
        w = $(window).width(),
        h = $(window).height(),
        gameContainer = $('.video-popup .game-entry'),
        iframe = $('iframe', gameContainer);

    if(iframe.length && w <= 812 && w > h)
    {
        gameContainer.addClass('is-mobile');
        iframe.css({width: w, height: h});
    }
    else
    {
        gameContainer.removeClass('is-mobile');
    }
}

$(window).on('resize', resizeIframe);

$('body').ready(function () {
    $(document).on("click", function() {
        $('.single-game-hover').removeClass('single-game-hover');
    });

    $(document).on("click", ".single-game", function (e) {
        e.stopPropagation();
        $(this).toggleClass('single-game-hover').siblings().removeClass('single-game-hover');
    });
});