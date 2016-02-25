$(function() {
	$("input.stars").before('<div class="rating"><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span><i class="glyphicon glyphicon-remove-circle"></i></div>');

	$(".rating span, .rating i").on('click', function() {
		$(this).parent('div').attr('data-stars',( 5-$(this).index()) ).next('input.stars').val( 5 - ($(this).index()) );
	});
});