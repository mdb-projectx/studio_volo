/**
 * jQuery Unveil
 * A very lightweight jQuery plugin to lazy load images
 * http://luis-almeida.github.com/unveil
 *
 * Licensed under the MIT license.
 * Copyright 2013 Luís Almeida
 * https://github.com/luis-almeida
 */

;(function($) {

  $j.fn.unveil = function(threshold, callback) {

    var $w = $j(window),
        th = threshold || 0,
        retina = window.devicePixelRatio > 1,
        attrib = retina? "data-src-retina" : "data-src",
        images = this,
        loaded;

    this.one("unveil", function() {
      var source = this.getAttribute(attrib);
      source = source || this.getAttribute("data-src");
      if (source) {
        this.setAttribute("src", source);
        if (typeof callback === "function") callback.call(this);
      }
    });

    function unveil() {
      var inview = images.filter(function() {
        var $e = $j(this);
        if ($e.is(":hidden")) return;

        var wt = $w.scrollTop(),
            wb = wt + $w.height(),
            et = $e.offset().top,
            eb = et + $e.height();

        return eb >= wt - th && et <= wb + th;
      });

      loaded = inview.trigger("unveil");
      images = images.not(loaded);
    }

    $w.on("scroll.unveil resize.unveil lookup.unveil", unveil);

    unveil();

    return this;

  };

})(window.jQuery || window.Zepto);


$j(document).ready(function() {

	$j("img").unveil();
	
    /*
	var useragent = navigator.userAgent.toLowerCase();

	if (useragent.indexOf("mobile") > -1)
	{
		$j('#fb .shadow').css("right", "-5px");
	}
    */

	/* MENU
	
    $j("#menu li").hover(function(){    
        $j(this).addClass("hover");
        $j('ul:first',this).stop(true,true).fadeIn(150);
    }, function(){    
        $j(this).removeClass("hover");
		$j('ul:first',this).stop(true,true).fadeOut(0);
    });
	
	/* MENU SEARCH EXTENSION 
	$j(".search #search").on("focus", function()	{
		$j(this).animate({
			width: "90%"
		}, 500);
	})

	 $j(".search #search").on("focusout", function()    {
                $j(this).animate({
                        width: "75px"
                }, 500);
        })

	/* STICKY HEADER 
	
	$j('#menu').waypoint(function(event, direction) {
		if (direction === 'down' && $j('#header-wrap2').length <= 0) {	
			$j('#header-wrap').clone().attr('id', 'header-wrap2').insertAfter($j("#header-wrap")).stop(true,true).fadeIn(250);
		}
		else {
			$j('#header-wrap2').fadeOut(100, function() {
				$j(this).remove();
			});
		}
	}, {
		offset: -40
	});
    */

	/* NEWSLETTER POPUP */

	$j('#newsletter-popupbutton').on('mouseenter', function()	{
		$j(this).addClass('hovered');
		$j(this).clearQueue().stop().animate({
			left: '0px'
		}, 250)
	})

	$j('#newsletter-popupbutton').on('mouseleave', function()  {
        $j(this).removeClass('hovered');
		$j(this).clearQueue().stop().animate({
            left: '-82px'
        }, 250)
    })

	var popupDelayed = false;
	$j('#newsletter-validate-detail').submit(function(e) {
		var popupEmail = $j('#newsletter').val();
		
		if(popupDelayed === false) {
			popupDelayed = true;
			
			if(location.hostname.substring(0,7) != 'staging') { 
				mixpanel.track('Newsletter Signup');
				mixpanel.identify(popupEmail);
				mixpanel.people.set({
					"$email": popupEmail
				});
				ga('set', '&uid', popupEmail);
			} else {
				console.log('mixpanel.identify('+popupEmail+');' + '\n' + 
				'mixpanel.people.set({' + '\n' + 
				'	"$email": ' + popupEmail + '\n' + 
				'});');
				console.log("ga('set', '&uid', "+popupEmail+");");
			}
			
			setTimeout(function() {
				$j('#newsletter-validate-detail').trigger('submit');
			}, 250);
			
			return false;
		} else {
			return true;		
		}	
	});		
	
	var footerDelayed = false;
	$j('#footer-newsletter-validate-detail').submit(function(e) {
		var footerEmail = $j('#footer-newsletter').val();
		
		if(footerDelayed === false) {
			footerDelayed = true;
			
			if(location.hostname.substring(0,7) != 'staging') { 
				mixpanel.track('Newsletter Signup');
				mixpanel.identify(footerEmail);
				mixpanel.people.set({
					"$email": footerEmail
				});
				ga('set', '&uid', footerEmail);
			} else {
				console.log('mixpanel.identify('+footerEmail+');' + '\n' + 
				'mixpanel.people.set({' + '\n' + 
				'	"$email": ' + footerEmail + '\n' + 
				'});');
				console.log("ga('set', '&uid', "+footerEmail+");");
			}
			
			setTimeout(function() {
				$j('#footer-newsletter-validate-detail').trigger('submit');
			}, 250);
			
			return false;
		} else {
			return true;		
		}	
	});	
	
	/****************************************
	HOMEPAGE
	****************************************/

	/* Subscription */

	$j( '#newsletter, #footer-newsletter' ).on( 'change', function() {
	    $j( '#newsletter, #footer-newsletter' ).val( $j( this ).val() );
	})

	/* SLIDER */
	/*
	if($j('body').hasClass('cms-home') && $j('#slider').length > 0) {			
		$j('#slider').append('<ul class="nav">');
		$j('#slider .nav').append('<li class="first current">1</li>');
		$j('#slider .nav').append('<li class="second">2</li>');
		$j('#slider .nav').append('<li class="third">3</li>');
		$j('#slider .nav').append('<li class="fourth">4</li>');
		$j('#slider').append('<div id="handle">&nbsp;</div>');	
		
		var sliderTimer; 
		var pauseSlider = false;
		
		function doSlider() {
			sliderTimer = setTimeout(function() {
				var nextSlide;
				if ($j('#slider .nav li.current').index() == 3) {
					nextSlide = 0;
				} else {
					nextSlide = $j('#slider .nav li.current').index() + 1;
				}
				
				if(pauseSlider === false) {
					$j('#slider .nav li:eq(' + nextSlide + ')').trigger('click');
				}
				
				doSlider();
			}, 3000);
		}		
		doSlider();		
		
		$j('#slider .nav li').mouseenter(function() {			
			$j('#slider .nav li').removeClass('current');
			$j(this).addClass('current');
			
			var leftDist;
			var currClass;
			if(  $j(this).hasClass('first') ) {
				leftDist = 0;
				currClass = 'first';
			} else if(  $j(this).hasClass('second') ) {
				leftDist = 240;
				currClass = 'second';
			} else if(  $j(this).hasClass('third') ) {
				leftDist = 480;
				currClass = 'third';
			} else if(  $j(this).hasClass('fourth') ) {
				leftDist = 720;
				currClass = 'fourth';
			}
			
			$j('#handle').stop(true,false).animate({
				left: leftDist
			}, 650, 'easeOutCirc');		
			
			$j('#slides li:not(.' + currClass + ')').fadeOut();
			$j('#slides li.' + currClass).fadeIn();			
		});

		$j('#slider .nav li').click(function() {			
			$j('#slider .nav li').removeClass('current');
			$j(this).addClass('current');
			
			var leftDist;
			var currClass;
			if(  $j(this).hasClass('first') ) {
				leftDist = 0;
				currClass = 'first';
			} else if(  $j(this).hasClass('second') ) {
				leftDist = 240;
				currClass = 'second';
			} else if(  $j(this).hasClass('third') ) {
				leftDist = 480;
				currClass = 'third';
			} else if(  $j(this).hasClass('fourth') ) {
				leftDist = 720;
				currClass = 'fourth';
			}
			
			$j('#handle').stop(true,false).animate({
				left: leftDist
			}, 650, 'easeOutCirc');		
			
			$j('#slides li:not(.' + currClass + ')').fadeOut();
			$j('#slides li.' + currClass).fadeIn();	
			
			return false;
		});
		
		$j('#slider').mouseenter(function() {
			pauseSlider = true;			
		}).mouseleave(function() {
			pauseSlider = false;			
		});
	}
	*/
    
	/****************************************
	CATEGORY PAGE & SEARCH
	****************************************/
	
    /*
	if($j('body').hasClass('catalog-category-view') || $j('#search').length > 0) { 
		
		$j('#category a.ro, #search a.ro').mouseenter(function() {
			$j(this).children().last().stop(true.true).animate({
				opacity: 0
			}, 300);
		}).mouseleave(function() {
			$j(this).children().last().stop(true.true).animate({
				opacity: 1
			}, 150);
		});
	
	}
    */
	
	
	/****************************************
	PRODUCT PAGE
	****************************************/
	
	if($j('body').hasClass('catalog-product-view')) { 
		
		/* Gallery */
		$j('#thumbs li a').bind('click', function() {
			return false;
		}).bind('hover', function() {
			$j('#photo img').attr('src', $j(this).attr('href'));
			$j('#thumbs li').removeClass('current');
			$j(this).parent().addClass('current');
		});
	
		$j('.recommended li a').mouseenter(function() {
			$j(this).parents('li').addClass('hover');
		}).mouseleave(function() {
			$j(this).parents('li').removeClass('hover');
		});
	
	}
	
	
	
	/****************************************
	CHECKOUT
	****************************************/
	
	if($j('body').hasClass('checkout-cart-index')) { 
		$j('.spinner').spinner({
			min: 0
		});
		
		$j('#discount .value').html($j('#discountAmount .value').html());
	}
	
	/****************************************
	WISHLIST 
	****************************************/
	
	if($j('body').hasClass('wishlist-index-index')) { 
		$j('input.qty').spinner({
			min: 0
		});
		
		/*
		$j('#wishlist-table textarea').each(function() {
			if( $j(this).val() == '') {
				$j(this).val('Comments...');
			}
		});
		
		$j('#wishlist-table textarea').focus(function() {
			if( $j(this).val() == 'Comments...') {
				$j(this).val('');
			}
		}).blur(function() {
			if( $j(this).val() == '') {
				$j(this).val('Comments...');
			}
		});
		*/
	}
	
	/****************************************
	SIGN UP 
	****************************************/
	
	var signupDelayed = false;
	$j('.account-create #form-validate').submit(function(e) {
		var signupEmail = $j(this).find('#email_address').first().val();
		var signupFirstname = $j(this).find('#firstname').first().val();
		var signupLastname = $j(this).find('#lastname').first().val();
		
		var d = new Date();
		var signupDate = d.getFullYear() + '-' + ('0' + (d.getMonth()+1)).slice(-2) + '-' + ('0' + d.getDate()).slice(-2) + 'T' + ('0' + d.getHours()).slice(-2) + ':' + ('0' + d.getMinutes()).slice(-2) + ':' + ('0' + d.getSeconds()).slice(-2);
		
		if(signupDelayed === false) {
			signupDelayed = true;
			
			if(location.hostname.substring(0,7) != 'staging') { 
				mixpanel.identify(signupEmail);
				mixpanel.people.set({
					"$first_name": signupFirstname,
					"$last_name": signupLastname,
					"$email": signupEmail,
					"$created": signupDate
				});
				ga('set', '&uid', signupEmail);
			} else {
				console.log('mixpanel.identify('+signupEmail+');' + '\n' + 
				'mixpanel.people.set({' + '\n' + 
				'	"$first_name": ' + signupFirstname + ','+ '\n' + 
				'	"$last_name": ' + signupLastname + ','+ '\n' + 
				'	"$email": ' + signupEmail + ','+ '\n' + 
				'	"$created": ' + signupDate + '\n' + 
				'});');
				console.log("ga('set', '&uid', "+signupEmail+");");
			}
			
			setTimeout(function() {
				$j('.account-create #form-validate').trigger('submit');
			}, 250);
			
			return false;
		} else {
			return true;		
		}	
	});

});

$j(window).load(function() {
	$j("img").trigger("unveil");
});

