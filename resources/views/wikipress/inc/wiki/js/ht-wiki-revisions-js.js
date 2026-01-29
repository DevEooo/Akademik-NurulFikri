console.log('ht-wiki-revisions-script loaded');

jQuery(document).ready(function($) {
	window.wp = window.wp || {};

	//revisions not used
	return;
	
	//override the updateUrl function
	//we can stop listening to any changes to update the url
	//wp.revisions.view.frame.model.router.stopListening( wp.revisions.view.frame.model, 'update:diff' );
	//wp.revisions.view.frame.model.router.stopListening( wp.revisions.view.frame.model, 'change:compareTwoMode' );


});