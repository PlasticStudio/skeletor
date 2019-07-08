/**
 * On page load
 **/
$(document).ready( function(){
	var popup = $(document).find('.popup').first();

	if( $(document).find('.section_popup').length > 0  ){
		console.log('test');

		popup.fadeIn(100);		

		$.ajax({
				url: $('.section_popup').data('url'),
				method: 'POST',
				success: function(response) {
					popup.find('.popup_liner').html(response);
					$('body').css('overflow-y','hidden');					
				}, 
				error: function(response) {
					alert('There was a problem, please try again');
				}
			});

	}

	// Video share link handling
	var urlSearch = window.location.search.slice(1);

	// If page is loaded with video id in url - pre-popped up
	var query = urlSearch.split('=');

	// test that the query string is for video
	if( query[0] === 'video') {

		// Get url of video with ID from URL
		var videoID = window.location.search.split('=');
		var elem = $('.resource[data-id="' + videoID[1] + '"]');

		// cannot find video with that ID
		if( !elem.length ) {
			alert('Sorry, there is no video that matches this url');
			// reload the page without the video search
			window.location.href = window.location.href.split('?')[0];
		}

		var url = elem.find('a.popup_trigger_video').data('url');

		popup.fadeIn(100);

		popup.find('.popup_content').css({'background':'transparent','text-align':'center'});
		
		var w = window.innerWidth * 0.8; // 9/10 of the screen width
		var h = w / 1.777777778; // aspect ratio 16:9

        var video = "<iframe width='"+w+"px' height='"+h+"px' class='frame' src='"+url+"?rel=0&showinfo=0&modestbranding=1&autoplay=1' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
		
		popup.find('.popup_liner').html(video);

		$(window).resize(function(){ // when window is resized the iframe scales as well
		
			w = window.innerWidth * 0.8; // 9/10 of the screen width
			h = w / 1.777777778; // aspect ratio 16:9
			
			var iframe = document.getElementsByTagName("iframe")[0];
			
			iframe.style.width = w + "px";
			iframe.style.height = h + "px";
		});
	}


	if( $(document).find('.popup').length > 0 ){

		/* General Popups */

	    $('.popup_trigger').click(function(evt){
	    	evt.preventDefault();
	        popup.fadeIn(100);

			//update browser address so ppl can share the URL			
			history.replaceState('', '', $(this).data('shareurl') );

	        $.ajax({
				url: $(this).data('url'),
				method: 'POST',
				success: function(response) {
					popup.find('.popup_liner').html(response);
					$('body').css('overflow-y','hidden');
				}, 
				error: function(response) {
					alert('There was a problem, please try again');
				}
			});
		});
		
		/* Video Popups */

	    $('.popup_trigger_video').click(function(evt){
	    	evt.preventDefault();
	        popup.fadeIn(100);

	        //update browser address so ppl can share the URL
			history.replaceState('', '', $(this).data('shareurl') );			

	        popup.find('.popup_content').css({'background':'transparent','text-align':'center'});
			var url = $(this).data('url');
			
			var w = window.innerWidth * 0.8; // 9/10 of the screen width
			var h = w / 1.777777778; // aspect ratio 16:9

	        var video = "<iframe width='"+w+"px' height='"+h+"px' class='frame' src='"+url+"?rel=0&showinfo=0&modestbranding=1&autoplay=1' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
	 
			popup.find('.popup_liner').html(video);

			$(window).resize(function(){ // when window is resized the iframe scales as well
			
				w = window.innerWidth * 0.8; // 9/10 of the screen width
				h = w / 1.777777778; // aspect ratio 16:9
				
				var iframe = document.getElementsByTagName("iframe")[0];
				
				iframe.style.width = w + "px";
				iframe.style.height = h + "px";
			});
		});

		/* Applies to all Popups - Close Popup on cross */

	    popup.find('.popup_close').click(function(){
	        popup.fadeOut(100);
	       	popup.find('.popup_liner').html('');
	       	$('body').css('overflow-y','auto');

	       	//remove page name from browser address (just showing parent path)
	       	history.replaceState('', '', $(this).data('parenturl') );
		});

		/* Applies to all Popups - Close Popup when clicking away */
		
		/*$(document).mouseup(function(e) {
			var container = $(".popup_content");

			// If the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) 
			{
				//remove page name from browser address (just showing parent path)
	       		history.replaceState('', '', $('.popup_close').data('parenturl') );
				popup.fadeOut(100);
				popup.find('.popup_liner').html('');
				$('body').css('overflow-y','auto');
			}
		});*/
	}
});