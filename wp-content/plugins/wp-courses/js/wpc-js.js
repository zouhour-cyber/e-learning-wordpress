function capFirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

jQuery(document).ready(function($){

	$(document).on('click', '.wpc-lightbox-close', function(){
    	$('.wpc-lightbox-container, .wpc-award-lightbox-wrapper').fadeOut();
    });

	function setLessonNavOverflowY(){

		var container = $('#wpc-single-lesson-content');

		var contentY = container.outerHeight(true);

		if( container.length ) {

			if(contentY > 300 && $(document).width() > 767) {
				$('.lesson-nav').css('max-height', contentY);
			} else {
				$('.lesson-nav').css('max-height', 600);
			}	
		}

	}

	function fixIframeSize(){
		var video = $('.wpc-video-wrapper iframe');
		$.each(video, function(key, val){
			var w = $(this).parent().width();
			var h = w * 0.5625;
			$(this).width(w);
			$(this).height(h);	
		});
	}

	fixIframeSize();
	setLessonNavOverflowY();

	$(window).resize(function(){
		fixIframeSize();
		setLessonNavOverflowY();
	});	

	// toolbar functionality
	$('#wpc-viewed-lessons-toggle').click(function(){
		$('#wpc-viewed-lessons-content').slideToggle();
		$('#wpc-attachments-content').fadeOut();
	});

	$('#wpc-attachments-toggle').click(function(){
		$('#wpc-attachments-content').fadeToggle();
		$('#wpc-viewed-lessons-content').fadeOut();
	});

	// scroll to active lesson
	if ($('.active-lesson-button').length) {
        var pos = $('.active-lesson-button:first').position();
        var nav = $('.lesson-nav');
        nav.animate({
            scrollTop: pos.top,
        }, 1000);
    }

    // datatables

    $('.wpc-sortable-table').DataTable( {
            // Disable sort on load
            "aaSorting": [],
            "language": {
		        
			    "emptyTable":     WPCTranslations.emptyTable,
			    "info":           "_START_ - _END_ / _TOTAL_",
			    "infoEmpty":      WPCTranslations.infoEmpty,
			    "infoFiltered":   "(" + WPCTranslations.infoFiltered + " _MAX_)",
			    "lengthMenu":     WPCTranslations.lengthMenu + ": " + "_MENU_",
			    "loadingRecords": WPCTranslations.loadingRecords,
			    "processing":     WPCTranslations.processing,
			    "search":         WPCTranslations.search + ":",
			    "zeroRecords":    WPCTranslations.zeroRecords,
			    "paginate": {
			        "first":      WPCTranslations.first,
			        "last":       WPCTranslations.last,
			        "next":       WPCTranslations.next,
			        "previous":   WPCTranslations.previous
			    },
			    "aria": {
			        "sortAscending":  ": " + WPCTranslations.sortAscending,
			        "sortDescending": ": " + WPCTranslations.sortDescending
				    }
				
		    }
        });

});