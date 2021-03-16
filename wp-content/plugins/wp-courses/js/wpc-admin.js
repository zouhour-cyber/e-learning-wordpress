function fixIframeSize(){
    var video = jQuery('.wpc-video-wrapper iframe');
    jQuery.each(video, function(key, val){
        var w = jQuery(this).parent().width();
        var h = w * 0.5625;
        jQuery(this).width(w);
        jQuery(this).height(h);  
    });
}

jQuery(document).ready(function(){
    fixIframeSize(); 
});

jQuery(window).resize(function(){
    fixIframeSize();
}); 

function wpcLessonTableData(){
	var $lessonRows = jQuery('.wpc-order-lesson-list-lesson');
    var posts = [];
    $lessonRows.each(function(key, value){
    	var dataID = jQuery(this).attr('data-id');
        var postType = jQuery(this).attr('data-post-type');
    	posts.push({
    		'postID': dataID,
    		'menuOrder': key,
            'postType' : postType,
    	});			        	
    });
	return posts;
}

function wpcShowAjaxIcon(){
    $saveIconWrapper = jQuery('#wpc-ajax-save');
    $saveIcon = $saveIconWrapper.children();
    $saveIcon.removeClass();
    $saveIcon.addClass('fa fa-spin fa-spinner');
    $saveIconWrapper.fadeIn();
}

function wpcHideAjaxIcon(){
    $saveIcon.removeClass();
    $saveIcon.addClass('fa fa-check');
    $saveIconWrapper.delay(750).fadeOut(750);
}

jQuery(document).ready(function($){

    // requirements meta box display logic

    $(document).on('change', '.wpc-requirement-action', function(){

        var value = $(this).val();

        if(value == 'scores') {

            $(this).siblings('.wpc-requirement-type').children('option[value="any-quiz"]').attr('selected','selected');
            $(this).parent().children('.wpc-requirement-courses-select').hide();
            $(this).parent().children('.wpc-percent').show();
            $(this).parent().children('.wpc-percent-label').show();

            $(this).parent().children('.wpc-requirement-times').show();
            $(this).parent().children('.wpc-times-label').show();

            $(this).siblings('.wpc-requirement-type').children('option[value="any-course"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-course"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-lesson"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-lesson"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-module"]').hide();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-module"]').hide();

            $(this).parent().children('.wpc-requirement-lesson-select').hide();

        } else {

            $(this).siblings('.wpc-requirement-type').children('option[value="any-course"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-course"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-lesson"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-lesson"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="any-module"]').show();
            $(this).siblings('.wpc-requirement-type').children('option[value="specific-module"]').show();

        }

        var requirementType = jQuery(this).parent().children('.wpc-requirement-type').val();
        var requirementAction = jQuery(this).val();

        var $requirementPercent = jQuery(this).parent().children('.wpc-percent');
        var $percentLabel = jQuery(this).parent().children('.wpc-percent-label');

        if(requirementType ==  'specific-quiz' || requirementType == 'any-quiz') {
            if(requirementAction == 'completes' || requirementAction == 'views') {
                $requirementPercent.val(0);
                $requirementPercent.hide();
                $percentLabel.hide();
            } else {
                $requirementPercent.val(0);
                $requirementPercent.show();
                $percentLabel.show();
            }
        }

    });

    $(document).on('change', '.wpc-requirement-type', function(){

        var requirementType = jQuery(this).val();
        var requirementAction = jQuery(this).parent().children('.wpc-requirement-action').val();

        var $requirementTimes = jQuery(this).parent().children('.wpc-requirement-times');
        var $requirementPercent = jQuery(this).parent().children('.wpc-percent');

        var $timesLabel = jQuery(this).parent().children('.wpc-times-label');
        var $percentLabel = jQuery(this).parent().children('.wpc-percent-label');

        var $requirementCoursesSelect = jQuery(this).parent().children('.wpc-requirement-courses-select');
        var $requirementLessonSelect = jQuery(this).parent().children('.wpc-requirement-lesson-select');

        $requirementLessonSelect.hide();

        if(requirementType == 'specific-lesson' || requirementType == 'any-lesson'){
            $requirementPercent.val(0);
            $requirementPercent.hide();
            $percentLabel.hide();
        } else {
            $requirementPercent.show();
            $percentLabel.show();
        }

        if(requirementType == 'specific-course' || requirementType == 'specific-lesson' || requirementType == 'specific-module' || requirementType ==  'specific-quiz'){
            $requirementCoursesSelect.show();
            $requirementCoursesSelect.val('none');
            $timesLabel.hide();
            $requirementTimes.hide();
        } else {
            $requirementCoursesSelect.hide();
            $timesLabel.show();
            $requirementTimes.show();
        }

        if(requirementType ==  'specific-quiz' || requirementType == 'any-quiz') {
            if(requirementAction == 'completes' || requirementAction == 'views') {
                $requirementPercent.val(0);
                $requirementPercent.hide();
                $percentLabel.hide();
            } else if (requirementAction == 'scores') {
                $requirementPercent.val(0);
                $requirementPercent.show();
                $percentLabel.show();
            }
        }

    });

    // Add Color Picker to all inputs that have 'color-field' class
    $('.color-field').wpColorPicker();

    $('.wpc-question-btn').click(function(){
    	$('.wpc-lightbox-container').show();
    	$('.wpc-lightbox').html($(this).attr('data-content'));
    });

    $('.wpc-lightbox-close').click(function(){
    	$('.wpc-lightbox-container').hide();
    });

    // admin submenu display logic
    $(document).on('click', '.wpc-submenu-toggle', function(e){
        $(this).siblings().children('.wpc-admin-submenu').fadeOut('fast');
        $(this).children('.wpc-admin-submenu').fadeToggle('fast');
        $(this).toggleClass('wpc-admin-menu-item-active');
        $(this).siblings().removeClass('wpc-admin-menu-item-active');
    });

    // hide submenu on click outside of submenu
    $(document).on('click',function(e){
        if( !$(e.target).closest('.wpc-admin-submenu, .wpc-submenu-toggle, .wpc-submenu-toggle a').length ){
            $('.wpc-admin-submenu').fadeOut('fast');
            $('.wpc-submenu-toggle').removeClass('wpc-admin-menu-item-active');
        }
    });

    $('.wpc-nav-tab').click(function(e){
        $('.wpc-nav-tab').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
        $('.wpc-tab-content').hide();
        $('.wpc-tab-content').eq($(this).index()).fadeIn('fast');
        e.preventDefault();
    });

    $('.wpc-sortable-table').DataTable(
        {
            // Disable sort on load
            "aaSorting": []
        }
    );

});