"use strict";

let listGameParamsDefault = {
    typeId: 0,
    categoryId: 0,
    search: '',
    page: 1
};

let statusGameRoom = 0;
let statusTypes = 0;
let currPage = 2;

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

        let paramsGame = {
                ga: {
                    category: $(this).attr('data-category'),
                    name: $(this).attr('data-name').replace(/\b\w/g, l => l.toUpperCase()).replace(/[^\w]/gi, ''),
                    type: $('.type_of_game').select2().find(':selected').data('type')
                }
        }

        //console.log($(this).attr('data-name'));
        let url = String(this.getAttribute('href'));
        e.preventDefault();
        if (auth) {

            $('.expand-game').addClass('not-allowed');
            getGame(url, paramsGame);
        }
        else {
            $('.log-popup').addClass('active');
            window.localStorage.setItem('gameUrl', url);
            window.localStorage.setItem('authOpenGame', true);

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

    
    $('body').on('click', '.moreGames', function (e) {
        let append = true;
        listGameParams.page = currPage;
        getListGames(append);
    });

    $('a.getFreeSpins').on('click', function (e) {
        e.preventDefault();
        $('.type_of_game').val($('.js-example-basic-single option:first-child').val()).trigger('change');
    })

    $('.type_of_game').on('change', function (e) {
        e.preventDefault();
        listGameParams.typeId = Number($(this).val());
        listGameParams.page = 1;
        listGameParams.freeSpins = 0;
        // $('.tittlePage').text($(this).find(":selected").text())
        let gameTypeLink = $(this).find(":selected").data("link");
        let splitedUrl = window.location.href.split("/");

        let lastUrlSegment = splitedUrl.pop()

        if ($(this).find(":selected").val() == 'free_spins') {
            freeSpinGames();
            return false;
        }

            
        if (gameTypeLink === undefined) {
            if (lastUrlSegment.split('#')[0] == 'games') {
                window.location.reload();
            } else {
                // history.pushState({ 'page': 'games'}, "", splitedUrl.join("/").split('#')[0])
                window.location = splitedUrl.join("/").split('#')[0]

            }

           
        } else {
            if (lastUrlSegment.split('#')[0] == 'games') {
                // history.pushState({ 'page': gameTypeLink}, "", window.location.href.split('#')[0] + '/' + gameTypeLink);
                window.location = window.location.href.split('#')[0] + '/' + gameTypeLink;
            } else {
                // history.pushState({ 'page': gameTypeLink}, "", splitedUrl.join("/").split('#')[0] + '/' + gameTypeLink);
                window.location = splitedUrl.join("/").split('#')[0] + '/' + gameTypeLink;
            }
        }
        // console.log(gameTypeLink);
        // console.log(location);
        // console.log(lastUrlSegment);
        
        
        // getListGames();
        // setDefaultTitle();
        $('html,body').scrollTop(0);
    });


    $('.filter_provider').on('change', function (e) {
        e.preventDefault();
        listGameParams.categoryId = Number($(this).val());
        listGameParams.page = 1;
        listGameParams.freeSpins = 0;
        getListGames();
        // setDefaultTitle();
        $('html,body').scrollTop(0);
    });

    $(document).on('submit', '.block-filter form', function (e) {
        e.preventDefault();
        listGameParams.search = $(this).find('input[name="search"]').val();
        listGameParams.page = 1;
        getListGames();
        // setDefaultTitle();
        $('html,body').scrollTop(0);
    });

    
    function freeSpinGames() {
        
        if ((location.href).indexOf("games/") >= 0) {
            let splitedUrl = window.location.href.split("/")
            splitedUrl.pop()
            // console.log(splitedUrl.join('/').split('#')[0]);
            history.pushState({}, "", splitedUrl.join('/').split('#')[0])
            
        } 
        // e.preventDefault();
        listGameParams.typeId = 0;
        listGameParams.categoryId = 0;
        listGameParams.page = 1;
        listGameParams.freeSpins = 1;
        getListGames();
        setDefaultFilter(1);
        setDefaultTitle();
        $('html,body').scrollTop(0);
    }


};

function handleImage(img) {
    $(img).attr("src", dummy);
    $(img).prev().show();
    $(img).parents('.single-game').addClass("imageNotFound")
}


let mobile = false
if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
    mobile = true
}


window.onpopstate = function(event) {
    
    var gamesLoaded = localStorage.getItem('gamesLoaded')

    var urlParams = new URLSearchParams(window.location.hash.replace("#","?"));
    var hash = urlParams.get('page');
    // console.log('hash page:' + hash);
    
    if (hash == null) {
        // console.log("STOP");
        hash = 1
    }

    if (hash + 1 == currPage) {
        // console.log("STOP2");
        history.back()
        return;
    }

    // console.log(currPage);


    if (hash <= currPage - 1) {
        
        // console.log("url page less than curr");
        $('.single-game').slice(-gamesLoaded).remove();
        // $('.tittlePage').get(0).scrollIntoView();
        
        $('html, body').animate({
            scrollTop: $(".games-entry .firstGame").last().offset().top-20
        }, 500);

        if (mobile) {
            localStorage.setItem('gamesLoaded', gamesToShowMob)
        } else {
            localStorage.setItem('gamesLoaded', gamesToShowDesk)
        }
       
        // console.log($('.single-game').length);
        currPage--
        $('.moreGames').show()
    } else if (hash == currPage) {
        // console.log('else IF');
        listGameParams.page = currPage
        getListGames(true, true);
    } else {
        // console.log('else');
        window.location.reload()
    }
 
};

// $('#resetGames').on('click', function(e){
//     e.preventDefault()
//     console.log('lol');
    
//     $('#gamesFiterForm')[0].reset()
//     listGameParams.search = ''
//     listGameParams.typeId = 0;
//     listGameParams.categoryId = 0;
//     $('.select2-selection__rendered').html($('.js-example-basic-single option:first-child').html());
//     // $('.type_of_game').val($('.js-example-basic-single option:first-child').val()).trigger('change');
//     $('.filter_provider').val($('.js-example-basic-single option:first-child').val()).trigger('change');
    
// })


function getListGames(append, hist) {
    $('.preloaderCommon').show();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'GET',
        url: '/integratedGamesJson',
        data: listGameParams,
        success: function (response) {

            // if (response)
            // console.log(response);
            // console.log(response.desktop.length);

           
            
            //clear
            //$(".insertGames").empty();
            //$(".insertGamesMobile").empty();
            //insert
            // $('html,body').scrollTop(0);

            function parsePesponse(device, gamesCount) {
                var parser = new DOMParser();

                var games = parser.parseFromString(device, 'text/html')

                if (append) {                  
                    // $(".insertGames .games-entry").append('<div class="lol">' +device+ '</div>');
                    $(".insertGames .games-entry").append(device);
                    if (!hist) {
                          history.pushState({
                              page: currPage
                          }, "", '#page=' + currPage)
                    }
                  
                    localStorage.setItem('gamesLoaded', $(games).find('.single-game').length);
                    currPage++
                } else {     
                    $(".insertGames .games-entry").html(device);
                    if ((location.href).indexOf("games/") <= 0) {
                         history.pushState({}, "", 'games')
                    } else {
                        // location.hash = '';
                        history.replaceState({}, "", window.location.href.split('#')[0])
                        
                    }              
                     currPage = 2
                }

                let gamesShown = $('.single-game').length
                      
                if (gamesShown < gamesCount) {
                    $('.moreGames').show()
                } else {
                    $('.moreGames').hide()
                }
               
                if (device.length == 0 && $('.games-entry').is(':empty') === true) {
                    console.log('No games found');
                    $('.noGamesFound').show()
                } else {
                    $('.noGamesFound').hide()
                }

            }

                      

            if (mobile) {
                parsePesponse(response.mobile, response.totalGames)              
            } else {
                parsePesponse(response.desktop, response.totalGames)             
            }                 
            $('.preloaderCommon').hide();
            //resizeIframe();
        }
    });
}

var button = document.querySelector('.expand-game');

var showLoader = window.location.href;
    if ((showLoader).indexOf("showLoader") >= 0 ) {
        $('.video-popup').addClass('popup-slot');
        $('.video-popup').addClass('active');
        $('.bottom-nav').hide();
        $('._agile-lc-parent').hide();
        // $('header.header').addClass('active');
    }


function getGame(url, params = {}) {
    //$('.preloaderCommon').show();
    let statusGameRoomLocal = statusGameRoom;
    statusGameRoom++;
    //console.log(statusGameRoom);
    

    

    if(!mobile) {
        $('.video-popup').addClass('popup-slot');
        $('.video-popup').addClass('active');
        $('header.header').addClass('active');
    } else {

        var windowReference = window.open('https://casinobit.io/en/games?showLoader=1');
    }

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
                // console.log(html);

                let gameUrl = $(html).attr('src');

                if (mobile) {
                 
                    windowReference.location = gameUrl;
                    // window.open(gameUrl, '_blank');
                    // window.open('https://google.com', '_blank');
                } else {
                    $('.video-popup .game-entry').html(html);
                    $('.expand-game').removeClass('not-allowed');
                    button.addEventListener('click', fullscreen);
                    //$('.preloaderCommon').hide();
                }

                //ga
                if (typeof params.ga !== 'undefined') {
                    window.dataLayer.push({
                        event: "Starting Game", GameCategory: params.ga.type, GameTitle: params.ga.name
                    });
                }
                //ga
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

function setDefaultFilter(full = 0)
{
    let type_id, category_id;
    let url_string = window.location.href;
    let url = new URL(url_string);
    // console.log(url);
    
    if (document.jsBridge.games_type_id){
        url.searchParams.set("type_id", document.jsBridge.games_type_id);
    }

    if (full === 1) {
        type_id = 0;
        category_id = 0;
    } else {
        type_id = url.searchParams.get("type_id");
        category_id = url.searchParams.get("category_id");
    }
    // console.log(type_id);
    
    if ((type_id !== null) && !full){
        //$('.type_of_game').val(type_id).trigger('change');
        $('.type_of_game').val(type_id).trigger('change.select2');
    } else {  
        type_id = 0;
    }

    if (category_id !== null) {
        //$('.filter_provider').val(category_id).trigger('change');
        $('.filter_provider').val(category_id).trigger('change.select2');
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
// document.addEventListener('keydown', function (e) {
//     console.log('key press' + e.keyCode);
// });
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



$(function(){

    // if (auth)

    let authOpenGame = window.localStorage.getItem('authOpenGame');

    if(authOpenGame === 'true' && auth){
        let authGameUrl = window.localStorage.getItem('gameUrl');
        getGame(authGameUrl);

        window.localStorage.setItem('authOpenGame', 'false');
        
    }else{
        console.log("error localstorage");
    }

    console.log(authOpenGame)

});