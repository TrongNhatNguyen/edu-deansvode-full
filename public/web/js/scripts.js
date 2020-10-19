 
(function($) {
	'use strict';
	
	jQuery(document).ready(function(){ 
		function toggleIcon(e) {
		  	$(e.target)
		  	.prev('.panel-heading')
		  	.find(".more-less")
		  	.toggleClass('glyphicon-plus glyphicon-minus');
		  }
		  $('.panel-group').on('hidden.bs.collapse', toggleIcon);
		  $('.panel-group').on('shown.bs.collapse', toggleIcon);

		/*START MENU JS*/
			$('a.page-scroll').on('click', function(e){
				var anchor = $(this);
				var href = anchor.attr('href').substring(1);
				if($(href).length > 0)
				{
					$('html, body').stop().animate({
						scrollTop: $(href).offset().top - 120
					}, 1000);
				}
				e.preventDefault();
			});	

			var hash = window.location.hash;
		    //console.log(hash)
		    if (hash != '') {
		        //hash = hash.substring(1);
		        if(hash.length > 0)
		        {
		            $('html, body').animate({
		                'scrollTop': $(hash).offset().top - 120
		            }, 1000);
		        }
		    };

		    
			// Responsive -> Closes Menu on Menu Item Click 	
			if ($(window).width() < 768) {
				$('.navbar-collapse ul li a.page-scroll').on('click', function(event) {
					$(this).closest('.collapse').collapse('toggle');
				});
			} 

			$(window).scroll(function() {
			  if ($(this).scrollTop() > 100) {
				$('.navbar-fixed-top').addClass('menu-shrink');
			  } else {
				$('.navbar-fixed-top').removeClass('menu-shrink');
			  }
			}); 


			// $(".list-btns a").click(function(e) {
			//   e.preventDefault();
			//   $("#itemFrame").attr("src", $(this).attr("href"));
			//   $(".list-btns a").removeClass("active");
			//   $(this).addClass("active");
			// })

	}); 	
		
	/*START WOW ANIMATION JS*/
	  new WOW().init();	  
				
})(jQuery); 