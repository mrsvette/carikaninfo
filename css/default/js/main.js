(function ($) {
 "use strict";
 
	$(document).ready(function(){
		$('#mobile-menu').slicknav();
		
		/* SPECIAL-PRODUCT-ALL ACTIVE JS*/
		$('.special-product-all').owlCarousel({
			//loop:true,
			margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				769:{
					items:3
				},
				
				992:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		/* BESTSELER-PRODUCT-ALL ACTIVE JS*/
		 $('.best-product-all').owlCarousel({
			loop:true,
			margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				769:{
					items:3
				},
				
				992:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		
		/* FEATURED-PRODUCT-CORUSOL ACTIVE JS*/
		$('.featured-product-corusol').owlCarousel({
			loop:true,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				769:{
					items:3
				},
				1000:{
					items:3
				}
			}
		})
		/* FEATURED-PRODUCT-CORUSOL-home-4 ACTIVE JS*/
		$('.featured-product-corusol-home-4').owlCarousel({
			loop:true,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-left'></i>","<i class='fa fa-arrow-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				769:{
					items:3
				},
				1000:{
					items:4
				}
			}
		})
		 
		 /* BLOAG-CORUSOL ACTIVE JS*/
		 $('.blog-corusol').owlCarousel({
			loop:true,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				769:{
					items:2
				},
				1000:{
					items:2
				}
			}
		})
		/* BLOAG-CORUSOL Home 4 ACTIVE JS*/
		 $('.blog-corusol-4').owlCarousel({
			loop:true,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				769:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		/* LOGO-CORUSOL ACTIVE JS*/
		 $('.logo-area').owlCarousel({
			loop:false,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:1500,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:2
				},
				769:{
					items:3
				},
				1000:{
					items:5
				}
			}
		})
		
		/* LOGO-CORUSOL Home 4 ACTIVE JS*/
		 $('.logo-area-4').owlCarousel({
			loop:false,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:3
				},
				1000:{
					items:4
				}
			}
		})
		/* TOP CETEGORY ACTIVE JS*/
		$('.top-category-menu').owlCarousel({
			loop:true,
			//margin:5,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-circle-o-left'></i>","<i class='fa fa-arrow-circle-o-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:3
				},
				1000:{
					items:5
				}
			}
		})
		/* BLOG_TITLE_CAROSUL ACTIVE JS*/
		 $('.blog_title_carosul').owlCarousel({
			loop:true,
			//margin:10,
			nav:true,
			autoplay:false,
			smartSpeed:3000,
			navText: ["<i class='fa fa-arrow-left'></i>","<i class='fa fa-arrow-right'></i>"],
			responsive:{
				0:{
					items:1
				},
				600:{
					items:1
				},
				1000:{
					items:1
				}
			}
		})
		
	/*---------------------
	  scrollUp
	--------------------- */	
		$.scrollUp({
			scrollText: '<i class="fa fa-angle-up"></i>',
			easingType: 'linear',
			scrollSpeed: 900,
			animation: 'fade'
		});	
		
		
		
		
	});
	  
	 /*  CHECHOUT PAGE ACCORDION */
	  jQuery('.panel-heading a').on('click', function() {
		$('.panel-heading').removeClass('actives');
		$(this).parents('.panel-heading').addClass('actives');
	});
	
})(jQuery);
