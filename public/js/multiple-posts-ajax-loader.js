jQuery('#peaw-trigger-loader').on('click', function(btnLoader){
	var pluginUri		= peawPHPInfo.pluginUri;
	var responserPHPUrl = peawPHPInfo.responserPHPUrl;
	var instance 		= peawPHPInfo.instance;
	var args			= peawPHPInfo
	var postsDisplayed 	= parseInt(jQuery('p.widget-displayed-counter').last().attr('name'));
	var totalToDisplay	= parseInt(peawPHPInfo.instance.number_of_posts);
	console.log(totalToDisplay);

	jQuery.ajax({
		method : "POST",
		cache : false,
		url : responserPHPUrl,
		data : { 
			action : 'peaw_ajax_loader',
			pluginUri : pluginUri,
			instance : instance,
			args	: args,
			postsDisplayed : postsDisplayed
		}
	}).done(function(response){
		console.log(response);
		//console.log(totalToDisplay);
		jQuery('#peaw-multiple-posts-container').append(response);
		var widgets = jQuery('.peaw-original-layout');

		for(var i = 0; i < widgets.length ; i++){
			var widget = widgets[i];
			if(jQuery(widget).css('display') == 'none'){
				jQuery(widget).fadeIn('slow');
			}
		}

		var newPostsDisplayed = parseInt(jQuery('p.widget-displayed-counter').last().attr('name'));
		var expected = postsDisplayed + parseInt(peawPHPInfo.instance.posts_first_shown);

		if (newPostsDisplayed == totalToDisplay){
			jQuery('#peaw-trigger-loader').fadeOut('slow');
		}else{
			if(expected != newPostsDisplayed){
				jQuery('#peaw-trigger-loader').fadeOut('slow');	
			}
		}
	});
});

