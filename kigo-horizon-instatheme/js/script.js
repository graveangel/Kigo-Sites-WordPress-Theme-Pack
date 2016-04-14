$(window).load(function () {			
		var dotheFix = setInterval(function(){		  
			doAtInit();
			if ($(".qsFixed").length > 0) {
				clearInterval(dotheFix);
			}
		}, 500);
	
	$(window).resize(function() {
	  doAtInit();
	});
});
function doAtInit() {
	try {
	updateTopAndBottom();
	positionQuickSearch();
	} catch(err){}
}
function updateTopAndBottom () {	
/* calculate the height of the footer and apply value to pushdown */
        $('.pushdown').css('padding-bottom', $('#insta-footer').height());
/* calculate the height of the top fixed widget area and apply value to the top ehader home block so it doens overlaps */
        $('.top-header-home').css('margin-top', $('#insta-top-fixed').height());
        $('.pushdown').css('margin-top', $('#insta-top-fixed').height() + 10);
        $('.home .pushdown').css('margin-top', 0);
}
function positionQuickSearch() {	
        var slideshowWidth, slideshowHeight, qsWidth, qsHeight, positionLeft, positionTop;
        slideshowWidth  = $('.home-slideshow').width();
        slideshowHeight = $('.home-slideshow').height();
        qsWidth         = $('.home-qsearch').width();
        qsHeight        = $('.home-qsearch').height();
        positionLeft    = (slideshowWidth - qsWidth) / 2;
        positionTop     = (slideshowHeight - qsHeight);
		
		if(positionTop > 0){
	        $('.home-qsearch').css({'top': positionTop, 'visibility': 'visible'});
		      $('.home .flexslider .flex-caption').css('top', slideshowHeight / 2)
    }
		if(positionLeft > 0){
    	    $('.home-qsearch').css('left', positionLeft);
		}
		$('.home-qsearch').addClass('qsFixed');	 
}