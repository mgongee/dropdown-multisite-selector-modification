jQuery(document).ready(function(){
	//Change the page on changing of the select

	jQuery(".dms2_dms-select").on('change', function(){
		console.log(this)
		var x = jQuery(this).val() ;
		window.location = x;
	});
});