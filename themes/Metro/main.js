//Manipulate DOM elements

function generateSidebarBorder()
{
	var a = jQuery('#sidebar');
	var b = jQuery('#sidebarBG');
	var location = a.css('float');

	if(location == 'left') b.css({'border-right':'7px solid #918E8E'});
	if(location == 'right') b.css({'border-left':'7px solid #918E8E'});
}


function generateSidebarMargin()
{
	var a = jQuery('#sidebar');
	var location = a.css('float');

	if(location == 'left') a.css({'margin-right':'15px'});
	if(location == 'right') a.css({'margin-left':'15px'});

	if(a.css('display') == 'none') //topmenu
	{
		var marginRight = jQuery('#content').css('margin-right');
		jQuery('#content').css({'margin-left':marginRight});
	}
}

function colorfulMenu()
{
	var colors = new Array('3366cc','cc3333','339900','cc9900','cc0099','00cc99','996633');
	var menuLevel1 = jQuery('ul.menuLevel1 li > a').not('ul li ul a');
	
	var n = 0; //untuk color
	menuLevel1.each(function(){
		jQuery(this).css({'background-color':'#'+colors[n]});
		n++;
		n = (n==7 ? 0 : n);
	});
}

function colorfulTable()
{
	var darkColors  = new Array('3366cc','cc3333','339900','cc9900','cc0099','00cc99','996633'); //kaler pekat, tulisan putih
	var lightColors = new Array('e4ecfb','fbe0e0','e6f7dd','fcf4db','fee4f8','dff9f2','fdeedf'); //kaler light, tulisan gelap
	var tables = jQuery('#content').find('table').not('table table');

	var n = 0; //untuk color
	tables.each(function(){
		if( !jQuery(this).parent().parent().hasClass('notification') ) //notification punya table terkecuali
		{
			jQuery(this).css({'background-color':'#'+lightColors[n]});
			jQuery(this).css({'border-color':'#'+darkColors[n]});
			jQuery(this).find('th').not('th th').css({'background-color':'#'+darkColors[n]});
			jQuery(this).find('.inputButton').each(function(){
				jQuery(this).css({'background-color':'#'+darkColors[n]});
			});
			n++;
			n = (n==7 ? 0 : n);
		}
	});
}

function addSearchBar()
{
	jQuery('#sidebar').prepend('<div id="searchBar"><input id="searchinput" placeholder="Search" type="text"><input id="searchbutton" type="button"></div>');
}

//dim background
function DimBackground()
{
	jQuery('body').prepend('<div id="dimBlack" style="background-color:#000000; position:fixed; top:0; bottom:0; left:0; right:0; z-index:999998"></div>');
	jQuery('#dimBlack').fadeTo(0,0.35);	
}

//execute when document ready
jQuery(document).ready(function(){

	generateSidebarBorder();
	generateSidebarMargin();
	addSearchBar();
	colorfulMenu();
	colorfulTable();

	var isNotificationExist = jQuery('.notification').length;

	if(isNotificationExist > 0)
	{
		DimBackground();
	}

});
