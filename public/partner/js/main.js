
function main() {

(function () {
   'use strict';
   
  	$('a.page-scroll').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
          var target = $(this.hash);
          target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
          if (target.length) {
            $('html,body').animate({
              scrollTop: target.offset().top -30
            }, 900);
            $('.menu-btn').removeClass('showNav')
            return false;
          }
        }
      });


    $('body').scrollspy({ 
        target: '.topNav',
        offset: 80
    });


	
  $(".itemTitle").on("click", function() {
       $(this).next().slideToggle(300);
       $(this).find('.fa-angle-down').toggleClass("rotate")
   })
		
}());


}
main();



$(function(){

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
animLogo();
setInterval(function() {
  animLogo() 
}, 15000);	



    $('#mainVal').on('input', function() {
      
      $(this).val($(this).val().replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'))

      if($(this).val().length > 7) {
        $(this).val($(this).val().slice(0,7))
        return false;
      }


        let mainVal = $(this).val()

        let valTo

        if (mainVal > 20000) {
            valTo = 165    

        } else {
            valN = mainVal / 20000  
            valTo = (valN * 150) + 15
            // console.log(valTo);
        }

        $('.rangeCicleWrap').css('transform', 'rotate('+valTo+'deg)')

        if (mainVal < 1000) {
            calc(50)
        } else if ((mainVal >= 1000) && (mainVal < 2000)) {
            calc(55)
        } else if ((mainVal >= 2000) && (mainVal < 5000)) {
            calc(60)
        } else if ((mainVal >= 5000) && (mainVal < 10000)) {
            calc(65)
        } 
        else if (mainVal >= 10000)  {
            calc(70)
        } 

        function calc(percent){
            $('#gainPercent').html(percent)
            let gainProfit = mainVal / 100 * percent
            $('#profit').html(gainProfit.toFixed(0))
        }

    })

    $(window).scroll(function(){
     var scrollTop = $(window).scrollTop();
     if ( scrollTop > $('#payouts').offset().top  - 200) { 
      $('#mainVal').addClass('activeInput')
     }
    });



    //FAQ
    $('.question').on('click', function () {
        $(this).parent('.faqItem').toggleClass('show')
    })

    //LANG
    // $('.currlang').on('click', function () {
    //     $(this).toggleClass('show')
    // })

    //MENU MOB
    $('.menu-btn').on('click', function () {
        $(this).toggleClass('showNav')
        return false
    })
    $('.btn').on('click', function () {    
      $('.menu-btn').removeClass('showNav')
    })

});