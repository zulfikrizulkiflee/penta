//Manipulate DOM elements

function generateSidebarBorder()
{
	var a = jQuery('#sidebar');
	var b = jQuery('#sidebarBG');
	var location = a.css('float');

	if(location == 'left') b.css({'border-right':'1px solid #D5D5D5'});
	if(location == 'right') b.css({'border-left':'1px solid #D5D5D5'});
}


function generateSidebarMargin()
{
	var a = jQuery('#sidebar');
	var location = a.css('float');

	if(location == 'left') a.css({'margin-right':'15px'});

	if(location == 'right'){
		a.css({'margin-left':'15px'});
		
		var marginRight = jQuery('#content').css('margin-right');
		jQuery('#content').css({'margin-left':marginRight});
	}

	if(a.css('display') == 'none') //topmenu
	{
		var marginRight = jQuery('#content').css('margin-right');
		jQuery('#content').css({'margin-left':marginRight});
	}
}

//execute when document ready
jQuery(document).ready(function(){

	generateSidebarBorder();
	generateSidebarMargin();

});
