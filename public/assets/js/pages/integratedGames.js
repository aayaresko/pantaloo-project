"use strict";

let listGameParamsDefault = {
    typeId: 0,
    categoryId: 0,
    search: '',
    page: 1
};

let listGameParams = JSON.parse(JSON.stringify(listGameParamsDefault));

let events = function () {

    $('body').on('click', 'a.open_game', function (e) {
        e.preventDefault();
        if(auth) {
            let url = String(this.getAttribute('href'));
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
        $('html,body').scrollTop(0);
    });

    $('#type_of_game').on('change', function (e) {
        e.preventDefault();
        listGameParams.typeId = Number($("#type_of_game").val());
        listGameParams.page = 1;
        getListGames();
        $('html,body').scrollTop(0);
    });

    $('#filter_provider').on('change', function (e) {
        e.preventDefault();
        listGameParams.categoryId = Number($("#filter_provider").val());
        listGameParams.page = 1;
        getListGames();
        $('html,body').scrollTop(0);
    });

    $(document).on('submit', '.block-filter form', function (e) {
        e.preventDefault();
        listGameParams.search = $("input[name='search']").val();
        listGameParams.page = 1;
        getListGames();
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
            $(".insertGames").html(response.desktop);
            $(".insertGamesMobile").html(response.mobile);
            $('.preloaderCommon').hide();
        }
    });
}


function getGame(url) {
    //$('.preloaderCommon').show();
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
            $('.video-popup .game-entry').html(html);
            //$('.preloaderCommon').hide();
        }
    });
}

$(function () {
    events();
    getListGames();
});

var button = document.querySelector('.expand-game');
button.addEventListener('click', fullscreen);
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

$('body').ready(function () {

});