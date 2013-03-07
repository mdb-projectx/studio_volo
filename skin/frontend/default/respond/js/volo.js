$j(document).ready(function() {

	/* MENU */
	
    $j("#menu li").hover(function(){    
        $j(this).addClass("hover");
        $j('ul:first',this).stop(true,true).fadeIn(150);
    }, function(){    
        $j(this).removeClass("hover");
		$j('ul:first',this).stop(true,true).fadeOut(0);
    });
	
	/* MENU SEARCH EXTENSION */
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

	/* STICKY HEADER */
	
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

	/* NEWSLETTER POPUP */

	$j('#newsletter-popupbutton').on('mouseenter', function()	{
		$j(this).addClass('hovered');
		$j(this).clearQueue().stop().animate({
			left: '0px'
		}, 500)
	})

	$j('#newsletter-popupbutton').on('mouseleave', function()  {
                $j(this).removeClass('hovered');
		$j(this).clearQueue().stop().animate({
                        left: '-82px'
                }, 500)
        })

	
	/****************************************
	HOMEPAGE
	****************************************/

	/* SLIDER */
	
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
	
	/****************************************
	CATEGORY PAGE & SEARCH
	****************************************/
	
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

});
