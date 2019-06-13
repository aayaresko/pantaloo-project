/* DevianScript */
'use strict';

$(document).ready(function(){
	ParallaxSections();
	lettering();
	gamesSlider();
	mobMenuInit();
	logOut();
	animationInit();
	gamePopup();
	// bonusSlider();
	controlsInit();
	blockFilter();
    // resizeIframe();
    // $(window).on('resize', resizeIframe);
	// preloader();
});

function controlsInit(){
	
		$('.setting-tabs').each(function(){
			$(this).tabs();
		})
	
		// $('.setting-accordion').each(function(){
		// 	$(this).accordion({
		// 		active: true,
		// 		collapsible: false
		// 	});
		// })
	
}

function mobMenuInit(){
	if( $(window).width() < 1080 ){
		$('.header-right-part .menu-btn').click(function(e){
			e.preventDefault();
			$('body, html').addClass('cropped');
			$('.mobile-menu').addClass('active');
			$('.overlayMenu').addClass('active');
			// return false;
		})
		$('.mobile-menu .close-icon').click(function(e){
			e.preventDefault();
			$('body, html').removeClass('cropped');
			$('.mobile-menu').removeClass('active');
			$('.overlayMenu').removeClass('active');
			// return false;
		})
	}
	if( $(window).width() < 1080 ){
		$('.slots-block .live-btn, .live-block .live-btn').click(function(){
			$(this).toggleClass('active');
			$('.games-listing-block').toggleClass('hidden-mob');
			return false;
		})
	}
}

function gamePopup(){
	if ($(window).width()>1080){
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

function animationInit(){
	$('.reg-btn').each(function(){
		$(this).click(function(e){
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
			$('.log-popup').addClass('active');
			return false
		})
	})
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
	$("body").click(function(e){
		if($(e.target).parents('.mfp-wrap').length || ($(e.target).attr('class') && $(e.target).attr('class').indexOf('mfp-') == 0)) return;
        // $(".reg-popup, .log-popup, .popup-entry").removeClass("active");
        setTimeout(function(){
            $('.simple-popup').removeClass('active');
        }, 300);
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

function logOut(){
	/*
	$('.logout-btn').click(function(){
		$('.header').removeClass('usr');
	})
	*/
}

function lettering(){
	$('.word-split').each(function(){
		// fix th lang on home page headers
		if (($(this).html() == 'Casinobit') || ($('html').attr('lang') != 'th')) {
			$(this).lettering();
		} 
	})
}

function ParallaxSections(){
	// if ( $(window).width() > 1080 ){
		$('.sections-container').fullpage({
			menu: '.sections-nav',
			anchors: ['', 'blackjack', 'roulette', 'slots'],
			responsiveWidth: 1080
		});
	// }
}

function gamesSlider(){
	if ($('.games-block').length>0 && $(window).width() < 1080){
		var owl = $('.games-slider');
		owl.owlCarousel({
			items:1,
			addClassActive : true,
			loop: true,
			nav: true,
			navContainer: '.nav-block',
			dotsContainer: '.dots-block'
		})
	}
}

// function bonusSlider(){
// 	if ($('.bonuses-listing').length>0 && $(window).width() < 1080){
// 		var owl = $('.bonuses-listing');
// 		owl.owlCarousel({
// 			items:1,
// 			addClassActive : true,
// 			loop: true,
// 			nav: true,
// 			navContainer: '.middle-block',
// 			dotsContainer: '.nav-block',
// 			navText: ''
// 		})
// 	}
// }

function blockFilter() {
	if($('.block-filter select').length > 0){
		$('.block-filter select').select2({
			minimumResultsForSearch: Infinity
		});
	}
}


$(document).ready(function(){
	
	function getCurrentScreen(){

		let fullpageWrapper = $('.fullpage-wrapper');

		fullpageWrapper.length

		let pageCount = location.href;

		if($(window).width() > 1080){

			if(pageCount.match('#slots')){
				$('.fp-enabled .footer.footer-home .footer-copyrights').addClass("showFooterLink");
			}else{
				$('.fp-enabled .footer.footer-home .footer-copyrights').removeClass("showFooterLink");
			}

		}else{
			$('.fp-enabled .footer.footer-home .footer-copyrights').addClass("showFooterLink");
		}

	}

	getCurrentScreen()

	$(window).on('mousewheel', getCurrentScreen);


	$("#btnKey").on("click", function(){

		let el = $('.generated-key').select();
		
		document.execCommand("copy");

		$(".copied").addClass("showCopied");

		setTimeout(function(){
			$(".copied").removeClass("showCopied");
		},1200);

	});

	getCurrentScreen()

	let table = $('#transactionsTable').DataTable({
		"searching": false,
		"bPaginate": true,
		"info":     false,
		"ajax": "/vendors/test.json",
		"iDisplayLength" : 10,
		"columns": [
            { "data": "date" },
            { "data": "id" },
            { "data": "status" },
            { "data": "amount" }
		],
		
		"columnDefs": [
			{ "orderable": false, "targets": 1 },
			{ "orderable": false, "targets": 2 }
		  ],
		drawCallback: function(){
			// $('#btn-example-load-more').toggle(this.api().page.hasMore());
		},
		createdRow: function( row, data, dataIndex ) {
			// Set the data-status attribute, and add a class
			let tdStatus = $( row ).find('td:eq(2)')
			tdStatus.addClass('statustransAction');
				
			if(data.status === 'Confirmed' ){
				tdStatus.addClass('confirm')
			}else{
				tdStatus.addClass('notConfirm')
			}
	
		}

	});

	$('.loadMoredataTableBtn').on('click', function(){  

		table.page.loadMore();

	 });




	 $(".unavailInfo").on("click", '#popUpBonus',function(){
		 $(this).parents('.single-bonus').find('.popUpBonusUnavail').addClass("showPoUp");
	 });

	 $(".popUpHideBtn").on("click", function(){
		$(".popUpBonusUnavail").removeClass("showPoUp");
	 });


	 let monthNames = [ "January", "February", "March", "April", "May", "June",
		    "July", "August", "September", "October", "November", "December" ];

		$('#years').append($('<option />').attr('disabled', true).attr('selected', true).html('Year'));

		$('#months').append($('<option />').attr('disabled', true).attr('selected', true).html('Month'));

		for (let i = new Date().getFullYear(); i > 1900; i--){

		    $('#years').append($('<option />').val(i).html(i));
		}
		    
		for (let i = 1; i < 13; i++){

		    $('#months').append($('<option />').val(i).html(monthNames[i - 1]));

		}

		updateNumberOfDays(); 
		    
		$('#years, #months').on("change", function(){
		    updateNumberOfDays(); 
		});

		function updateNumberOfDays(){
		    $('#days').html('');
		    $('#days').append($('<option />').attr('disabled', true).attr('selected', true).html('Day'));
		    let month=$('#months').val();
		    let year=$('#years').val();
		    let days=daysInMonth(month, year);

		    for(let i=1; i < days+1 ; i++){
		       $('#days').append($('<option />').val(i).html(i));
		    }
		    // $('#message').html(monthNames[month-1]+" in the year "+year+" has <b>"+days+"</b> days");
		}

		function daysInMonth(month, year) {
		    return new Date(year, month, 0).getDate();
		}


	$('.accountFormWrapper select').select2({
		minimumResultsForSearch: Infinity
	});

	$("#country").countrySelect();

	$('#userDataForm').on('submit', function(){

		let userData = $(this).serialize();

		let res = true;

		$.ajax({
			method: 'POST',
			url: '1111',
			data: userData,
		}).done(function(response){
			
				$(".alertWrapper").addClass("seccuses");
				$(".alertWrapper").addClass("showAlert");
				
				setTimeout(function(){
					$(".alertWrapper").removeClass("seccuses");
					$(".alertWrapper").removeClass("showAlert");
				},2000);

		}).fail(function(){
				$(".alertWrapper").addClass("error");
				$(".alertWrapper").addClass("showAlert");
				
				setTimeout(function(){
					$(".alertWrapper").removeClass("error");
					$(".alertWrapper").removeClass("showAlert");
				},2000);
		});

		return false;
	});

});




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