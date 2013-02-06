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

	
	/****************************************
	HOMEPAGE
	****************************************/

	/* SLIDER */
	
	if($j('body').hasClass('cms-home')) {
		if($j('#slider').length > 0) {	
			var slides = $j('#slider').length;
			
			$j('#slider').append('<a href="#" class="prev">Prev</a>').append('<a href="#" class="next">Next</a>').append('<div class="prevcover"></div>').append('<div class="nextcover"></div>');
			
			$j('#slider li:eq(0)').addClass('current');
			$j('#slider li:eq(3)').addClass('one');
			$j('#slider li:eq(4)').addClass('two');
			$j('#slider li:eq(1)').addClass('three');
			$j('#slider li:eq(2)').addClass('four');
			$j('#slider li').css({'display':'block'});
			
			$j('#slider').mouseenter(function() {
				$j('#slider .prev, #slider .next').stop(true,true).fadeIn(300);
			}).mouseleave(function() {
				$j('#slider .prev, #slider .next').stop(true,true).fadeOut(150);		
			});
		
			$j('#slider .prev').click(function(e) {
				
				if(!$j('#slider').hasClass('inMotion')) {
					$j('#slider li').each(function() {
						$j(this).animate({
							'margin-left': '+=960'
						}, 750, 'easeInOutQuad');
					});
				
					$j('#slider li').promise().done(function() {			
						$j('#slider li:eq(4)').detach().prependTo($j('#slider ul'));			
						$j('#slider li').css({'margin-left':0}).attr('class','');
						$j('#slider li:eq(0)').addClass('current');
						$j('#slider li:eq(3)').addClass('one');
						$j('#slider li:eq(4)').addClass('two');
						$j('#slider li:eq(1)').addClass('three');
						$j('#slider li:eq(2)').addClass('four');		
						$j('#slider').removeClass('inMotion');
					});
				}
				
				$j('#slider').addClass('inMotion');
			
				e.preventDefault();
			}).mouseenter(function() {
				$j('#slider .prevcover').animate({
					'opacity': 0.5
				},300);
			}).mouseleave(function() {
				$j('#slider .prevcover').animate({
					'opacity': 1
				},75);
			});
			
			$j('#slider .next').click(function(e) {
				
				if(!$j('#slider').hasClass('inMotion')) {
					$j('#slider li').each(function() {
						$j(this).animate({
							'margin-left': '-=960'
						}, 750, 'easeInOutQuad');
					});
				
					$j('#slider li').promise().done(function() {			
						$j('#slider li:eq(0)').detach().appendTo($j('#slider ul'));			
						$j('#slider li').css({'margin-left':0}).attr('class','');
						$j('#slider li:eq(0)').addClass('current');
						$j('#slider li:eq(3)').addClass('one');
						$j('#slider li:eq(4)').addClass('two');
						$j('#slider li:eq(1)').addClass('three');
						$j('#slider li:eq(2)').addClass('four');		
						$j('#slider').removeClass('inMotion');
					});
				}
				
				$j('#slider').addClass('inMotion');
			
				e.preventDefault();
			}).mouseenter(function() {
				$j('#slider .nextcover').animate({
					'opacity': 0.5
				},300);
			}).mouseleave(function() {
				$j('#slider .nextcover').animate({
					'opacity': 1
				},75);
			});
		
		}
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
