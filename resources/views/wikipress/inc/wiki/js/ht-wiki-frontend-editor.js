jQuery(document).ready(function($) {
	$( document ).on( 'tinymce-editor-init', function( event, editor ) {
		//temp fix to hide the trash button
		$('button.editor-post-trash').hide();
	});

	//listen to link changes
	var currentLink = wp.data.select('core/editor').getCurrentPostAttribute('link');
	wp.data.subscribe( function() {
	  	var newLink = wp.data.select('core/editor').getEditedPostAttribute('link');
		if(newLink != currentLink){
			$('.wkp-article__btn--bta a').attr('href', newLink);
		}
	});
});
