/* DevianScript */
'use strict';

$(document).ready(function () {
    console.log('init');
    ParallaxSections();
    lettering();
    gamesSlider();
    mobMenuInit();
    logOut();
    animationInit();
    gamePopup();
    bonusSlider();
    controlsInit();
	blockFilter();
	animLogo();
    // resizeIframe();
    // $(window).on('resize', resizeIframe);
	// preloader();
	
function animLogo() {                   
	let step = 0
	let i = 0
	for(i; i < 5; i++){ 
		(function(){               
			let svgWrap = document.querySelector('.svgWrap')
			let logo = document.getElementById('anim')

			setTimeout(function() {
				logo.classList.add("move");
				svgWrap.style.transform = 'scaleX(1.5)'
				logo.style.filter = 'blur(1.5px)';    
				logo.style.transform = 'translateX(-'+step+'px)';
				setTimeout(function() {
					logo.classList.remove("move");
					svgWrap.style.transform = 'scale(1)'
					logo.style.filter = 'blur(0)';
				}, 200)

				step +=40

			}, 100 + (3000*i));              
		})(i)

	}
}

setInterval(function() {
	animLogo() 
}, 15000);	


function controlsInit() {
    if ($(window).width() > 1080) {
        $('.setting-tabs').each(function () {
            $(this).tabs({
                show: {effect: "blind", duration: 800}
            });
        })
    } else {
        $('.setting-accordion').each(function () {
            $(this).accordion({
                active: false,
                collapsible: true
            });
        })
    }
}

function mobMenuInit() {
    if ($(window).width() < 1080) {
        $('.header-right-part .menu-btn').click(function (e) {
            e.preventDefault();
            $('body, html').toggleClass('cropped');
            $('.mobile-menu').toggleClass('active');
            $('.overlayMenu').toggleClass('active');
            // return false;
        })
        $('.mobile-menu .close-icon').click(function (e) {
            e.preventDefault();
            $('body, html').removeClass('cropped');
            $('.mobile-menu').removeClass('active');
            $('.overlayMenu').removeClass('active');
            // return false;
        })
    }
    if ($(window).width() < 1080) {
        $('.slots-block .live-btn, .live-block .live-btn').click(function () {
            $(this).toggleClass('active');
            $('.games-listing-block').toggleClass('hidden-mob');
            return false;
        })
    }
}

function gamePopup() {
    if ($(window).width() > 1080) {
        /*
        $('.slots-block.desk').each(function(){
            var cont = $(this);
            cont.find('.single-game a').click(function(){
                var name = $(this).attr('rel');
                $('.video-popup').attr('id', name).addClass('active');
                return false;
            })
        });
        */
    }
    /*
    $('.video-popup .exit-btn').click(function(){
        $(this).parents('.video-popup').removeClass('active');
        $('header.header').removeClass('active');
        //$('.video-popup .game-entry').html('<img src="media/images/game.jpg" alt="game">');
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
    });*/
}

	function animationInit() {
		$('.reg-btn').each(function () {
			$(this).click(function (e) {
				// ga('send','pageview','/registr');
				$('.reg-popup').addClass('active');
				return false;
			});
		});



		$('.account-btn').click(function(){
			//alert(100);

			$(".reg-popup, .log-popup, .popup-entry").removeClass("active");
			setTimeout(function(){
				$('.simple-popup').removeClass('active');
				$('.reg-popup').addClass('active');
			}, 300);

			return false
		})

		$('.login-btn').each(function(){
			$(this).click(function(e){
				// ga('send','pageview','/login');
				$(".reg-popup").removeClass('active');
				setTimeout(function(){
					$('.log-popup').addClass('active');
				},300);

				return false;
			});
		});

		// var ll;

		$("#login").on("submit", function (e) {



			let loginForm = $(this);

			$(".loginError ").html('');
			$(".errorMessage ").removeClass("showErrorMsg");
			loginForm.find('input').removeClass("showErrorMsg");
			
			$.ajax({
				method: 'post',
				dataType: 'json',
				url: '/login',
				data: loginForm.serialize()
			}).done(function (response) {

				let resultLogin = response;

				if(resultLogin.status == false){

					loginForm.find('input').addClass("showErrorMsg");
				
					let loginResult = $(".loginError").addClass("showErrorMsg");
					
					// loginResult.html(resultLogin.message.errors);

					let itemsError = resultLogin.message.errors;
		
					for(let i = 0; i < itemsError.length; i++){

						loginResult.append($('<p>').html(itemsError[i]));
						
					}

				}else{
					window.location.reload();

				}

			}).fail(function(){

				$(".loginError").html('There is some problem with your request');

				$(".loginError").addClass("showErrorMsg");

			});

			return false;
		});


		$("#registr").on("submit", function () {

			let regForm = $(this);

			$(".registrError").html('');
			$(".errorMessage").removeClass("showErrorMsg");
			regForm.find('input').removeClass("showErrorMsg");

			$.ajax({
				method: 'post',
				dataType: 'json',
				url: '/register',
				data: regForm.serialize()
			}).done(function (response) {

				let resultRegistr = response;

				if (resultRegistr.status == false) {

					regForm.find('input').addClass("showErrorMsg");

					let registrResult = $(".registrError").addClass("showErrorMsg");
					// registrResult.html(resultRegistr.message);

					let itemsError = resultRegistr.message.errors;

					for(let i = 0; i < itemsError.length; i++){
						
						// var item = $('<p>').html(itemsError[i]);
						registrResult.append($('<p>').html(itemsError[i]));
						
					}

					// registrResult.append(item);

				} else{

					window.location.reload();
					
				}
			}).fail(function(){

				$(".registrError").html('There is some problem with your request');
				$(".registrError").addClass("showErrorMsg");

			});
			return false;
		});

		

		/*
		$('.promo-action-btn').each(function(){
			$(this).click(function(e){
				$('.simple-popup').addClass('active');
				setTimeout(function(){
					$('.simple-popup .popup-entry').addClass('active');
				}, 300);
				return false
			})
		})
		*/
		$('.page-content-container .btn-block.mobile .live-btn').click(function(){
			$(this).toggleClass('active');
			$('.page-content-navigation.mobile').slideToggle();
			return false;
		})
		$('.simple-popup .close-icon').click(function(){
			$('.popup-entry').removeClass('active');
			setTimeout(function(){
				$('.simple-popup').removeClass('active');
			}, 300);
		})
		$('.simple-popup .close-button').click(function(){
			$('.popup-entry').removeClass('active');
			setTimeout(function(){
				$('.simple-popup').removeClass('active');
			}, 300);
		})
		// $("body").click(function(e){
		// 	if($(e.target).parents('.mfp-wrap').length || ($(e.target).attr('class') && $(e.target).attr('class').indexOf('mfp-') == 0)) return;
		//     $(".reg-popup, .log-popup, .popup-entry").removeClass("active");
		//     setTimeout(function(){
		//         $('.simple-popup').removeClass('active');
		//     }, 300);
		// });

		$(".close-icon").on("click", function(){
			$(".reg-popup, .log-popup, .popup-entry").removeClass("active");
		});

		$(".reg-popup .popup-container, .log-popup .popup-container, .simple-popup .popup-entry").click(function(e){
			e.stopPropagation();
		});
		$('button.close-icon').click(function () {
			$(".reg-popup, .log-popup, .popup-entry").removeClass("active");
		});
		$(document).keydown(function(e) {
			// ESCAPE key pressed
			if (e.keyCode == 27) {
				$(".reg-popup, .log-popup, .popup-entry").removeClass("active");
				setTimeout(function(){
					$('.simple-popup').removeClass('active');
				}, 300);
			}
		});
		$('.btn-history-block .open-history-link').click(function(){
			$('.bottom-block').slideToggle();
			return false;
		})
	}

	function logOut() {
		/*
		$('.logout-btn').click(function(){
			$('.header').removeClass('usr');
		})
		*/
	}

	function lettering() {
		$('.word-split').each(function () {
			// fix th lang on home page headers
			if (($(this).html() == 'Casinobit') || ($('html').attr('lang') != 'th')) {
				$(this).lettering();
			}
		})
	}

	function ParallaxSections() {
		let lock = false
		if ($(window).width() < 1080) {
			lock = true
		}
		$('.sections-container').fullpage({
			menu: '.sections-nav',
			anchors: ['', 'blackjack', 'roulette', 'slots'],
			responsiveWidth: 1080,
			lockAnchors: lock,
			afterLoad: function (origin, destination, direction) {
				let event = new CustomEvent('sectionAfterLoad', {
					detail: {origin: origin, destination: destination, direction: direction}
				});
				window.dispatchEvent(event);
			}
		});
	}

	function gamesSlider() {
		if ($('.games-block').length > 0 && $(window).width() < 1080) {
			var owl = $('.games-slider');
			owl.owlCarousel({
				items: 1,
				addClassActive: true,
				loop: true,
				nav: true,
				navContainer: '.nav-block',
				dotsContainer: '.dots-block'
			})
		}
	}

	function bonusSlider() {
		if ($('.bonuses-listing').length > 0 && $(window).width() < 1080) {
			var owl = $('.bonuses-listing');
			owl.owlCarousel({
				items: 1,
				addClassActive: true,
				loop: true,
				nav: true,
				navContainer: '.middle-block',
				dotsContainer: '.nav-block',
				navText: ''
			})
		}
	}

	function blockFilter() {
		if ($('.block-filter select').length > 0) {
			$('.block-filter select').select2({
				minimumResultsForSearch: Infinity
			});
		}
	}



    $(window).on('sectionAfterLoad', function (event) {
        if ($(window).width() >= 1080) {
            if (event.detail.origin == 'slots') {
                $('.fp-enabled .footer.footer-home .footer-copyrights').addClass("showFooterLink");
            } else {
                $('.fp-enabled .footer.footer-home .footer-copyrights').removeClass("showFooterLink");
            }
        } else {
            $('.fp-enabled .footer.footer-home .footer-copyrights').addClass("showFooterLink");
        }
    })

    $(".faqWrapper .page-entry > h2").on('click', function () {
        $(this).next().toggleClass('showFaq');
		$(this).toggleClass("rotateArrow");
	});


	$(".textWrapper h1, .textWrapper h2, .textWrapper h3, .textWrapper h4, .textWrapper h5, .textWrapper h6").on("click", function(){
		$(this).nextUntil('h2').toggleClass("showFaq");
	});

	
	let inputType = 1;

	$('.showPasBtn').on("click", function(){

		if(inputType == 0){
			inputType = 1;
			$(this).prev().attr('type', 'password');
		}else{
			inputType = 0;
			$(this).prev().attr('type', 'text');
		}

	});


    $(window).on('scroll', function () {

        if ($(window).scrollTop() > 400) {
            $(".toTop").addClass("hideBtnToTop");
            // console.log('lol1')
        } else {
            $(".toTop").removeClass("hideBtnToTop");
            // console.log('lol2')
        }

    });

    $(".toTop").on("click", function () {
        $("html, body").animate({scrollTop: 0}, 1000);
    });


    // function bonusTerms() {
    // 	$('.block-bonus-buttons .usl-link').on('click', function (e) {
    // 		let linkBonus = $(this).attr('data-bonus-url');
    // 		$('.tempateBonusActive .bonusActiveTerms').attr('href', linkBonus);
    //
    // 		let tempateBonusActive = $('.tempateBonusActive').html();
    // 		$('#uls').append(tempateBonusActive);
    // 	});
    //
    // 	$('.usl-link').on('mfpClose', function (e) {
    // 		$("#uls .popUpTermForm").remove();
    // 	});
    // }
    //
    // bonusTerms();
    //
    //
    // $('#uls').on('click','.popUpBtnBonus', function(e){
    //
    // 	if($('#terms').prop('checked') == false){
    //
    // 		$(".errorMessage").addClass("showErrorMsg");
    //
    // 		$(this).prev().addClass("showErrorMsg");
    //
    // 		e.preventDefault();
    // 	}
    // });
    //
    // $("#uls").on("click", '.mfp-close' ,function(){
    //
    // 	$(".errorMessage").removeClass('showErrorMsg');
    //
    // });




// let pageCount = location.href;


// if(pageCount.match('block-4')){
// 	console.log(1)
// }

// console.log(pageCount)


// function cached(url){
// 	var test = document.createElement("img");
// 	test.src = url;
// 	return test.complete || test.width+test.height > 0;
// }
//
// function preloader() {
// 	if(!cached('/images/pixel.png')) {
// 		$('.preloader-block').show();
// 		$(window).on('load', function () {
// 			$('.preloader-block').hide();
// 		});
// 	}
// }
//
// preloader();

});