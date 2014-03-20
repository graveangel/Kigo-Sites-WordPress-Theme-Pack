/**
 * 
 *
 * 
 * 
 */

$(document).ready(function() {	
	
	/*$('#insta-top-fixed .bapi-logo img').attr('src', 'http://instatheme04.lodgingcloud.com/wp-content/uploads/sites/3/2013/06/Untitled-31.png');*/
	
	/* initializes the home slideshow which is in the background */
	if($('body').hasClass('home'))
	{	
    $('#maximage').maximage({
        cycleOptions: {
            fx: 'fade',
            slideResize:0,
            speed: 800,
            timeout: 4000,
            before: function(currSlideElement, nextSlideElement, options, forwardFlag){
                $('#slide-caption').fadeOut(function(){                    
                    $(this).html($(nextSlideElement).attr('title'));
                });
            },
            after: function(){
                $('#slide-caption').fadeIn();
            }
        },
        onFirstImageLoaded: function() {
            $('#maximage').fadeIn('fast');
        }
    });
	
	$('#maximage').css('display','block');

	}   
    

	/* adds a class so the widget titles in the home page show only a icon*/
	$('.home .tab-pane .widget-title').addClass('hidden-desktop');
	
	/* replaces the tabs titles with the widget titles if there is one */	
	var theID;
	$('.home ul.nav-tabs li a').each(function(index) {
		theID = $(this).attr('href') + ' .widget-title';
		if($(theID).length > 0 )
		{			
			$('span span', this).text($(theID).text());
		}
	});
	
		
	/* calling the generic setter for attractions page */
	setRowsAndFlexslider(".poi-results",".poi-results > .span4",false,true,3);
	
	/* calling the generic setter for specials page */
	setRowsAndFlexslider(".specials-results",".specials-results > .span4",false,true,3);
	
	/* calling the generic setter for property finders page */
	setRowsAndFlexslider(".propertyfinders-results",".propertyfinders-results > .span4",false,true,3);
	
	/* calling the generic setter for property finders page */
	setRowsAndFlexslider("#results",".gallery-view-page > .span6",true,true,2);
	
	
	
	if($('.phone-filters').css('display') == 'block')
		{
			$('#qs2, #filter').removeClass('module shadow');	
			$('#qs2').appendTo('#filters');
			$('#filter').appendTo('#filters');	  
		}
	

});

/* Functions that makes the layout work 
---------------------------------------------------------------------*/

/* this function sets a timer that calls another function until it sets the rows if it is needed or the flexslider if there is one, pages that use this are:
Attractions, Property Finders, Specials, Gallery View, List View */

function setRowsAndFlexslider(findThis,wrapthis,needFlex,needWrapRows,howManyWrap){
	if($(findThis).length > 0){	
	  var timer = setInterval(function(){
		  	   
		  if ($(".showmore").length > 0) {
		  initRowsAndFlexslider(wrapthis,needFlex,needWrapRows,howManyWrap);
		  }
		  if ($(".nomore").length > 0) {
		   initRowsAndFlexslider(wrapthis,needFlex,needWrapRows,howManyWrap);
		   clearInterval(timer);
   		  }
	  }, 200);
	}
}	
	
function initRowsAndFlexslider(wrapthis,needFlex,needWrapRows,howManyWrap) {
	if(needFlex){
		$('.flexslider').flexslider({
    	animation: "slide",
		slideshow: false
  	});
	}
	if(needWrapRows){
		var divs = $(wrapthis);
		for(var i = 0; i < divs.length; i+=howManyWrap) {
		  divs.slice(i, i+howManyWrap).wrapAll("<div class='row-fluid'></div>");
		}
	}
	
}