//
// Nav JS File
//

// Start Wrapper
jQuery(document).ready(function ($) {

	let navArea = $('#wkp-mainnav');

	console.log('doing nav magic');

	/*
	wp.data.dispatch('core/notices').createNotice(
		'success', // Can be one of: success, info, warning, error.
		'Something happend published.', // Text string to display.
		{
			isDismissible: true, // Whether the user can dismiss the notice.
			// Any actions the user can perform.
			actions: [
				{
					url: '#',
					label: 'View post'
				}
			]
		}
	);
	*/

	wp.api.loadPromise.done( function() {
		if(typeof(wp.data) === 'undefined'){
			return;
		}

		//only load if editor active
		if( null == wp.data.select('core/editor') ){
			return;
		}

		const { subscribe } = wp.data;

		let currentPostCategories = wp.data.select('core/editor').getCurrentPostAttribute('categories');
		let currentPostTitle = wp.data.select('core/editor').getCurrentPostAttribute('title');

		console.log('Categories');
		console.log(currentPostCategories);
		console.log('Title');
		console.log(currentPostTitle);

		const titleUnssubscribe = subscribe( () => {
			const newPostTitle = wp.data.select( 'core/editor' ).getCurrentPostAttribute('title');
			if ( newPostTitle != currentPostTitle ) {
				console.log('new post title');
				console.log(newPostTitle);
				if(typeof(currentPostTitle) !== 'undefined'){
					//trigger menu update
					menuUpdate();
				}
				currentPostTitle = newPostTitle;
			}
		});

		const categoriesUnssubscribe = subscribe( () => {
			const newPostCategories = wp.data.select( 'core/editor' ).getCurrentPostAttribute('categories');
			if ( newPostCategories != currentPostCategories ) {
				console.log('new post categories');
				console.log(newPostCategories);
				if(typeof(currentPostCategories) !== 'undefined'){
					//trigger menu update
					menuUpdate();
				}
				currentPostCategories = newPostCategories;
			}
		});

		function menuUpdate(){
			console.log('menu update');
			var data = {
				'action': 'update_menu_handle'
			};

			$.ajax(	{ 	url: ajax_information.ajaxurl, 
						data: data,
						dataType: 'json' 
			} )
				.success( function(response, textStatus, jqXHR) {
					if(response && 'success' === response.state){
						$('#wkp-mainnav').replaceWith(response.nav);
					} else if(response && 'error' === response.state){
						console.log(response.error);
					}
				})
				.fail( function( jqXHR, textStatus, errorThrown ) {
					console.log('error');
					console.log(errorThrown);
				})
		}
	});


	// Watch for the publish event.
	/*
	const unssubscribe = subscribe( () => {
		const currentPostStatus = wp.data.select( 'core/editor' ).getEditedPostAttribute( 'status' );
		if ( 'publish' === currentPostStatus ) {
			console.log('POST PUBLISHED YEAH');
		}
	} );
	*/

});
